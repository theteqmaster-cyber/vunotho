/**
 * VUNOTHO PRICING INTELLIGENCE & NET-RETURN CALCULATOR
 * Implements the core innovation: "Offer A = $X, Transport = $Y, Net Return = $Z"
 */

class VunothoPricing {
  constructor() {
    // Base logistics parameters
    this.BASE_TRANSPORT_RATE_PER_KG_KM = 0.0015; // $0.0015 per kg per km
    this.MIN_TRANSPORT_PER_KG = 0.04;            // Minimum transport cost floor
    this.PLATFORM_FEE_PERCENTAGE = 0.04;          // 4% transparent marketplace coordination fee
  }

  /**
   * Calculate detailed financial breakdown for a produce transaction
   * @param {number} grossPricePerKg - Buyer's offered price per kg in USD ($)
   * @param {number} quantityKg - Harvest lot volume in kilograms
   * @param {number} distanceKm - Distance from farmgate to buyer / delivery hub
   * @param {boolean} isAggregated - Whether the load is part of a pooled collection
   */
  calculateNetReturn(grossPricePerKg, quantityKg, distanceKm = 25, isAggregated = true) {
    const grossTotal = Number((grossPricePerKg * quantityKg).toFixed(2));

    // Calculate dynamic transport cost
    let rawTransportPerKg = Math.max(
      this.MIN_TRANSPORT_PER_KG,
      distanceKm * this.BASE_TRANSPORT_RATE_PER_KG_KM
    );

    // Aggregated logistics discount (35% savings from pooled truck loads)
    if (isAggregated) {
      rawTransportPerKg = rawTransportPerKg * 0.65;
    }

    const transportPerKg = Number(rawTransportPerKg.toFixed(3));
    const transportTotal = Number((transportPerKg * quantityKg).toFixed(2));

    // Vunotho marketplace coordination fee
    const platformFeeTotal = Number((grossTotal * this.PLATFORM_FEE_PERCENTAGE).toFixed(2));
    const platformFeePerKg = Number((platformFeeTotal / quantityKg).toFixed(3));

    // Net Return Calculation
    const netTotal = Number((grossTotal - transportTotal - platformFeeTotal).toFixed(2));
    const netReturnPerKg = Number((netTotal / quantityKg).toFixed(3));

    // Baseline solo transport comparison (showing value created)
    const soloTransportTotal = Number(((rawTransportPerKg / 0.65) * quantityKg).toFixed(2));
    const transportSavings = Number(Math.max(0, soloTransportTotal - transportTotal).toFixed(2));

    return {
      grossPricePerKg,
      grossTotal,
      transportPerKg,
      transportTotal,
      platformFeePerKg,
      platformFeeTotal,
      netReturnPerKg,
      netTotal,
      quantityKg,
      distanceKm,
      isAggregated,
      transportSavings,
      currency: 'USD'
    };
  }

  /**
   * Format currency values nicely ($X.XX)
   */
  formatUSD(amount) {
    return `$${Number(amount || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
  }
}

window.vunothoPricing = new VunothoPricing();
