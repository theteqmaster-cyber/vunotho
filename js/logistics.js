/**
 * VUNOTHO LOGISTICS & LOAD AGGREGATION ENGINE
 * Transforms small, unprofitable smallholder lots into aggregated collection manifests.
 */

class VunothoLogistics {
  constructor() {
    this.STANDARD_TRUCK_CAPACITY_KG = 2500; // 2.5 Tonne Rural Light Truck
  }

  /**
   * Aggregate open farmer listings into optimal collection routes
   * @param {Array} listings - Open harvest listings
   */
  aggregateListings(listings) {
    if (!listings || listings.length === 0) return [];

    // Group listings by crop and district
    const clusters = {};

    listings.forEach((item) => {
      const key = `${item.crop || 'Potatoes'}_${item.district || 'General'}`;
      if (!clusters[key]) {
        clusters[key] = {
          clusterId: `CLUST-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
          crop: item.crop || 'Potatoes',
          district: item.district || 'General',
          items: [],
          totalWeightKg: 0,
          stopsCount: 0
        };
      }
      clusters[key].items.push(item);
      clusters[key].totalWeightKg += Number(item.quantity_kg || 0);
      clusters[key].stopsCount += 1;
    });

    // Create manifest proposals from clusters
    return Object.values(clusters).map((cluster) => {
      const loadUtilizationPct = Math.min(100, Math.round((cluster.totalWeightKg / this.STANDARD_TRUCK_CAPACITY_KG) * 100));
      const estTotalDistance = 15 + cluster.stopsCount * 8; // base km + waypoint detours
      const estTransporterPayout = Number((cluster.totalWeightKg * 0.05 + estTotalDistance * 0.4).toFixed(2));

      return {
        id: `MAN-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
        clusterId: cluster.clusterId,
        crop: cluster.crop,
        district: cluster.district,
        totalWeightKg: cluster.totalWeightKg,
        stopsCount: cluster.stopsCount,
        loadUtilizationPct,
        estTotalDistance,
        estTransporterPayout,
        items: cluster.items,
        status: 'Pending Dispatch' // Pending Dispatch, Dispatched, In Transit, Completed
      };
    });
  }
}

window.vunothoLogistics = new VunothoLogistics();
