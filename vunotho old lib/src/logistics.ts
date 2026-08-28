/**
 * VUNOTHO LOGISTICS & LOAD AGGREGATION ENGINE (TypeScript)
 * Clustered Multi-Farmer 2.5 Tonne Rural Light Truck manifests.
 */

import { Listing, RouteManifest, ManifestStop } from './types';

export class VunothoLogistics {
  private STANDARD_TRUCK_CAPACITY_KG = 2500; // 2.5 Tonne Rural Light Truck

  aggregateListings(listings: Listing[]): RouteManifest[] {
    if (!listings || listings.length === 0) return [];

    const clusters: Record<string, {
      clusterId: string;
      crop: string;
      district: string;
      items: Listing[];
      totalWeightKg: number;
      stopsCount: number;
    }> = {};

    listings.forEach((item) => {
      const key = `${item.crop || 'Potatoes'}_${item.district || 'General'}`;
      if (!clusters[key]) {
        clusters[key] = {
          clusterId: `CLUST-${Date.now().toString(36).toUpperCase().slice(-4)}`,
          crop: item.crop || 'Potatoes',
          district: item.district || 'Nyanga',
          items: [],
          totalWeightKg: 0,
          stopsCount: 0
        };
      }
      clusters[key].items.push(item);
      clusters[key].totalWeightKg += Number(item.quantity_kg || 0);
      clusters[key].stopsCount += 1;
    });

    return Object.values(clusters).map((cluster) => {
      const loadUtilizationPct = Math.min(100, Math.round((cluster.totalWeightKg / this.STANDARD_TRUCK_CAPACITY_KG) * 100));
      const estTotalDistance = 20 + cluster.stopsCount * 6; // base km + waypoint detours
      const estTransporterPayout = Number((cluster.totalWeightKg * 0.05 + estTotalDistance * 0.45).toFixed(2));

      const stops: ManifestStop[] = cluster.items.map(item => ({
        farmerName: item.farmer_name || 'Smallholder Farmer',
        crop: item.crop || 'Produce',
        weightKg: Number(item.quantity_kg || 0),
        lat: Number(item.lat || -18.2167),
        lng: Number(item.lng || 32.7500),
        district: item.district || cluster.district
      }));

      const destination = cluster.district === 'Gwanda'
        ? 'Belmont Wholesale Hub (Bulawayo)'
        : (cluster.district === 'Mutare' ? 'Mutare Regional Depot' : 'Mbare Musika Wholesale Hub (Harare)');

      return {
        id: `MAN-${Date.now().toString(36).toUpperCase()}`,
        clusterId: cluster.clusterId,
        crop: cluster.crop,
        district: cluster.district,
        originDistrict: cluster.district,
        destination,
        totalWeightKg: cluster.totalWeightKg,
        stopsCount: cluster.stopsCount,
        stops,
        loadUtilizationPct,
        estTotalDistance,
        estimatedDistanceKm: estTotalDistance,
        estTransporterPayout,
        status: 'Pending Dispatch'
      };
    });
  }
}

export const vunothoLogistics = new VunothoLogistics();
if (typeof window !== 'undefined') {
  (window as any).vunothoLogistics = vunothoLogistics;
}
