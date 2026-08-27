/**
 * VUNOTHO PRICING INTELLIGENCE & NET-RETURN CALCULATOR (TypeScript)
 * Implements: Gross Price - Transport - 4% Platform Fee = Net Return
 */

import { NetReturnBreakdown } from './types';

export class VunothoPricing {
  private BASE_TRANSPORT_RATE_PER_KG_KM = 0.0015; // $0.0015 per kg per km
  private MIN_TRANSPORT_PER_KG = 0.04;            // Minimum transport cost floor
  private PLATFORM_FEE_PERCENTAGE = 0.04;          // 4% transparent marketplace coordination fee

  calculateNetReturn(
    grossPricePerKg: number,
    quantityKg: number,
    distanceKm = 35,
    isAggregated = true
  ): NetReturnBreakdown {
    const grossTotal = Number((grossPricePerKg * quantityKg).toFixed(2));

    let rawTransportPerKg = Math.max(
      this.MIN_TRANSPORT_PER_KG,
      distanceKm * this.BASE_TRANSPORT_RATE_PER_KG_KM
    );

    // Aggregated logistics discount (35% savings from pooled 2.5T truck loads)
    if (isAggregated) {
      rawTransportPerKg = rawTransportPerKg * 0.65;
    }

    const transportPerKg = Number(rawTransportPerKg.toFixed(3));
    const transportTotal = Number((transportPerKg * quantityKg).toFixed(2));

    const platformFeeTotal = Number((grossTotal * this.PLATFORM_FEE_PERCENTAGE).toFixed(2));
    const platformFeePerKg = Number((platformFeeTotal / Math.max(1, quantityKg)).toFixed(3));

    const netTotal = Number((grossTotal - transportTotal - platformFeeTotal).toFixed(2));
    const netReturnPerKg = Number((netTotal / Math.max(1, quantityKg)).toFixed(3));

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

  formatUSD(amount: number | string): string {
    return `$${Number(amount || 0).toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })}`;
  }
}

export const vunothoPricing = new VunothoPricing();
if (typeof window !== 'undefined') {
  (window as any).vunothoPricing = vunothoPricing;
}
