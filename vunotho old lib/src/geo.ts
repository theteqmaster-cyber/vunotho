/**
 * VUNOTHO GEOLOCATION & DISTRICT MAPPING ENGINE (TypeScript)
 */

export interface GeoCoordinate {
  lat: number;
  lng: number;
}

export interface DistrictCenter {
  name: string;
  province: string;
  lat: number;
  lng: number;
}

export class VunothoGeo {
  private districts: Record<string, DistrictCenter> = {
    'Nyanga': { name: 'Nyanga', province: 'Manicaland', lat: -18.2167, lng: 32.7500 },
    'Mutasa': { name: 'Mutasa', province: 'Manicaland', lat: -18.6167, lng: 32.6667 },
    'Mutare': { name: 'Mutare', province: 'Manicaland', lat: -18.9728, lng: 32.6694 },
    'Chipinge': { name: 'Chipinge', province: 'Manicaland', lat: -20.1947, lng: 32.6225 },
    'Goromonzi': { name: 'Goromonzi', province: 'Mashonaland East', lat: -17.8333, lng: 31.3833 },
    'Marondera': { name: 'Marondera', province: 'Mashonaland East', lat: -18.1853, lng: 31.5519 },
    'Mazowe': { name: 'Mazowe', province: 'Mashonaland Central', lat: -17.5167, lng: 30.9667 },
    'Gwanda': { name: 'Gwanda', province: 'Matabeleland South', lat: -20.9333, lng: 29.0000 },
    'Masvingo': { name: 'Masvingo', province: 'Masvingo', lat: -20.0637, lng: 30.8277 },
    'Gweru': { name: 'Gweru', province: 'Midlands', lat: -19.4500, lng: 29.8167 },
    'Harare': { name: 'Harare CBD Hub', province: 'Harare', lat: -17.8292, lng: 31.0522 },
    'Bulawayo': { name: 'Bulawayo Belmont Hub', province: 'Bulawayo', lat: -20.1569, lng: 28.5806 }
  };

  getDistrictCenter(districtName: string): DistrictCenter {
    return this.districts[districtName] || this.districts['Nyanga'];
  }

  getInstantPosition(): GeoCoordinate {
    return { lat: -18.2167, lng: 32.7500 };
  }

  calculateDistanceKm(coord1: GeoCoordinate, coord2: GeoCoordinate): number {
    const R = 6371; // Earth radius in km
    const dLat = this.deg2rad(coord2.lat - coord1.lat);
    const dLng = this.deg2rad(coord2.lng - coord1.lng);
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(this.deg2rad(coord1.lat)) * Math.cos(this.deg2rad(coord2.lat)) *
      Math.sin(dLng / 2) * Math.sin(dLng / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return Math.max(10, Math.round(R * c));
  }

  private deg2rad(deg: number): number {
    return deg * (Math.PI / 180);
  }
}

export const vunothoGeo = new VunothoGeo();
if (typeof window !== 'undefined') {
  (window as any).vunothoGeo = vunothoGeo;
}
