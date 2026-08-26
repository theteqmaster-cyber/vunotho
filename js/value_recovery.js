/**
 * VUNOTHO CIRCULAR VALUE-RECOVERY ENGINE
 * 4-Tier Non-Binary Produce Routing & Waste Diversion Metric Tracking
 */

class VunothoValueRecovery {
  constructor() {
    this.pathways = {
      FRESH_MARKET: {
        id: 'fresh_market',
        title: 'Tier 1: Fresh Market Primary',
        badgeClass: 'tier-fresh',
        desc: 'Grade-A commercial produce for retail supermarkets, wholesale distributors, and fresh markets.',
        valueRecoveryMultiplier: 1.0, // 100% full market value
        icon: '🛒'
      },
      PROCESSING: {
        id: 'processing',
        title: 'Tier 2: Value-Added Processing',
        badgeClass: 'tier-processing',
        desc: 'Surplus, irregular-sized, or cosmetically challenged produce converted into potato crisps, starch, flour, or dried foods.',
        valueRecoveryMultiplier: 0.75, // 75% market value recovered
        icon: '🍟'
      },
      ANIMAL_FEED: {
        id: 'animal_feed',
        title: 'Tier 3: Livestock & Animal Feed',
        badgeClass: 'tier-feed',
        desc: 'Sub-grade lots and clean processing peels/by-products routed directly to local pig and cattle farmers.',
        valueRecoveryMultiplier: 0.35, // 35% economic recovery vs total disposal loss
        icon: '🐄'
      },
      COMPOST_RECOVERY: {
        id: 'compost_recovery',
        title: 'Tier 4: Organic Bio-Compost & Soil',
        badgeClass: 'tier-compost',
        desc: 'Degraded biomass composted for organic bio-fertilizer, returning nutrients to the next farming cycle.',
        valueRecoveryMultiplier: 0.15, // 15% value retention
        icon: '🌱'
      }
    };
  }

  /**
   * Determine optimal value pathway based on cosmetic appearance and shelf-life
   */
  evaluateProduce(crop, qualityGrade, isSurplus = false) {
    if (qualityGrade === 'Grade A (Premium)' && !isSurplus) {
      return this.pathways.FRESH_MARKET;
    }
    if (qualityGrade === 'Grade B (Cosmetic Imperfections)' || isSurplus) {
      return this.pathways.PROCESSING;
    }
    if (qualityGrade === 'Grade C (Sub-grade / Blemished)') {
      return this.pathways.ANIMAL_FEED;
    }
    return this.pathways.COMPOST_RECOVERY;
  }

  /**
   * Log a value recovery diversion event into the database
   */
  async logDiversion(listing, pathwayKey, kgDiverted, estimatedRecoveredValue) {
    const record = {
      id: `VR-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
      listing_id: listing.id || 'direct',
      crop: listing.crop || 'Potatoes',
      farmer_id: listing.farmer_id || 'unassigned',
      farmer_name: listing.farmer_name || 'Smallholder Farmer',
      pathway: pathwayKey,
      kg_diverted: Number(kgDiverted),
      recovered_value_usd: Number(estimatedRecoveredValue.toFixed(2)),
      timestamp: new Date().toISOString()
    };

    await window.vunothoDB.put('value_recovery', record);
    return record;
  }
}

window.vunothoValueRecovery = new VunothoValueRecovery();
