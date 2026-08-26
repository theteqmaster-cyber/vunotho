/**
 * VUNOTHO GEOLOCATION & DISTANCE ENGINE
 * GPS Coordinates capture, Haversine distance calculator, and District Hub mappings.
 */

class VunothoGeo {
  constructor() {
    this.cachedPosition = { lat: -18.2167, lng: 32.7500, accuracy: 'Nyanga Centroid' };
    // Reference agricultural hubs & markets
    this.hubs = [
      { name: 'Nyanga Ag Hub (Potatoes)', lat: -18.2167, lng: 32.7500, district: 'Nyanga' },
      { name: 'Marondera Fresh Depot', lat: -18.1856, lng: 31.5519, district: 'Marondera' },
      { name: 'Mbare Central Market (Harare)', lat: -17.8639, lng: 31.0428, district: 'Harare' },
      { name: 'Mutare Wholesale Terminal', lat: -18.9728, lng: 32.6694, district: 'Mutare' },
      { name: 'Goromonzi Farmgate Hub', lat: -17.8286, lng: 31.3400, district: 'Goromonzi' }
    ];
  }

  /**
   * Get instant coordinates without blocking rendering
   */
  getInstantPosition() {
    return this.cachedPosition;
  }

  /**
   * Acquire live device GPS coordinates with fast non-blocking timeout
   */
  async getCurrentPosition() {
    return new Promise((resolve) => {
      if (!navigator.geolocation) {
        resolve(this.cachedPosition);
        return;
      }

      navigator.geolocation.getCurrentPosition(
        (pos) => {
          const coords = {
            lat: Number(pos.coords.latitude.toFixed(5)),
            lng: Number(pos.coords.longitude.toFixed(5)),
            accuracy: `GPS (±${Math.round(pos.coords.accuracy)}m)`
          };
          this.cachedPosition = coords;
          resolve(coords);
        },
        (error) => {
          console.warn('Geolocation fast-timeout or denied, using regional centroid:', error.message);
          resolve(this.cachedPosition);
        },
        { enableHighAccuracy: false, timeout: 2500, maximumAge: 300000 }
      );
    });
  }

  /**
   * Calculate distance between two lat/lng coordinates in kilometers (Haversine formula)
   */
  calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Earth radius in km
    const dLat = this.deg2rad(lat2 - lat1);
    const dLon = this.deg2rad(lon2 - lon1);
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) *
      Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const d = R * c;
    return Number(Math.max(d, 5).toFixed(1)); // Minimum 5km local pickup distance
  }

  deg2rad(deg) {
    return deg * (Math.PI / 180);
  }

  /**
   * Find the closest central market/processing hub
   */
  getNearestHub(lat, lng) {
    let nearest = this.hubs[0];
    let minDistance = Infinity;

    for (const hub of this.hubs) {
      const dist = this.calculateDistance(lat, lng, hub.lat, hub.lng);
      if (dist < minDistance) {
        minDistance = dist;
        nearest = { ...hub, distanceKm: dist };
      }
    }
    return nearest;
  }
}

window.vunothoGeo = new VunothoGeo();
