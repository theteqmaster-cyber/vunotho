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

      const stops = cluster.items.map(item => ({
        farmerName: item.farmer_name || 'Smallholder Farmer',
        crop: item.crop || 'Produce',
        weightKg: Number(item.quantity_kg || 0),
        lat: item.lat || -18.2167,
        lng: item.lng || 32.7500,
        district: item.district || cluster.district
      }));

      return {
        id: `MAN-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
        clusterId: cluster.clusterId,
        crop: cluster.crop,
        district: cluster.district,
        originDistrict: cluster.district || 'Nyanga',
        destination: cluster.district === 'Gwanda' ? 'Belmont Hub (Bulawayo)' : (cluster.district === 'Mutare' ? 'Mutare Regional Depot' : 'Mbare Musika Wholesale Hub (Harare)'),
        totalWeightKg: cluster.totalWeightKg,
        stopsCount: cluster.stopsCount,
        stops,
        loadUtilizationPct,
        estTotalDistance,
        estimatedDistanceKm: estTotalDistance,
        estTransporterPayout,
        items: cluster.items,
        status: 'Pending Dispatch' // Pending Dispatch, Dispatched, In Transit, Completed
      };
    });
  }
}

window.vunothoLogistics = new VunothoLogistics();
