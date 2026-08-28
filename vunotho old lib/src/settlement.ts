/**
 * VUNOTHO SANDBOX SETTLEMENT & DIGITAL RECEIPT ENGINE (TypeScript)
 * Interactive EcoCash PIN keypad and verifiable digital receipt printer.
 */

import { vunothoPricing } from './pricing';
import { vunothoAPI } from './api';

export class VunothoSettlement {
  private currentPin = '';
  private activeTxData: any = null;

  async initiatePayment(txDetails: any) {
    this.activeTxData = txDetails;
    if (txDetails.paymentMethod === 'ecocash') {
      this.openPinModal();
    } else {
      await this.completeSettlement('Cash on Farmgate Collection', 'COD-SETTLED');
    }
  }

  openPinModal() {
    this.currentPin = '';
    this.updatePinDisplay();
    const modal = document.getElementById('payment-pin-modal');
    if (modal) {
      const amountEl = document.getElementById('pin-modal-amount');
      const farmerEl = document.getElementById('pin-modal-farmer');
      if (amountEl) amountEl.textContent = vunothoPricing.formatUSD(this.activeTxData?.netTotal || 0);
      if (farmerEl) farmerEl.textContent = this.activeTxData?.farmer_name || 'Farmer';
      modal.classList.add('active');
    }
  }

  closePinModal() {
    const modal = document.getElementById('payment-pin-modal');
    if (modal) modal.classList.remove('active');
    this.currentPin = '';
    this.activeTxData = null;
  }

  handlePinInput(digit: string) {
    if (this.currentPin.length < 4) {
      this.currentPin += digit;
      this.updatePinDisplay();
      if (this.currentPin.length === 4) {
        setTimeout(() => this.submitPinPayment(), 300);
      }
    }
  }

  handlePinBackspace() {
    if (this.currentPin.length > 0) {
      this.currentPin = this.currentPin.slice(0, -1);
      this.updatePinDisplay();
    }
  }

  updatePinDisplay() {
    const dots = document.querySelectorAll('.pin-dot');
    dots.forEach((dot, index) => {
      if (index < this.currentPin.length) {
        dot.classList.add('filled');
      } else {
        dot.classList.remove('filled');
      }
    });
  }

  async submitPinPayment() {
    this.closePinModal();

    if ((window as any).vunothoApp) {
      (window as any).vunothoApp.showToast('Verifying EcoCash PIN & Authorizing Transfer...', 'info');
    }

    setTimeout(async () => {
      const authRef = `ECO-${Math.floor(100000 + Math.random() * 900000)}`;
      await this.completeSettlement('EcoCash Mobile Wallet', authRef);
    }, 800);
  }

  async completeSettlement(methodTitle: string, referenceCode: string) {
    const tx = {
      id: `TX-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
      reference: referenceCode,
      receipt_reference: referenceCode,
      payment_method: methodTitle,
      farmer_id: this.activeTxData?.farmer_id || 'FARMER-1',
      farmer_name: this.activeTxData?.farmer_name || 'Farmer',
      buyer_id: this.activeTxData?.buyer_id || 'BUYER-1',
      buyer_name: this.activeTxData?.buyer_name || 'Commercial Buyer',
      crop: this.activeTxData?.crop || 'Potatoes',
      quantity_kg: Number(this.activeTxData?.quantity_kg || 0),
      gross_total: Number(this.activeTxData?.grossTotal || 0),
      transport_deduction: Number(this.activeTxData?.transportTotal || 0),
      transport_cost: Number(this.activeTxData?.transportTotal || 0),
      platform_fee: Number(this.activeTxData?.platformFeeTotal || 0),
      net_payout: Number(this.activeTxData?.netTotal || 0),
      created_at: new Date().toISOString(),
      status: 'Settled' as const
    };

    await vunothoAPI.createTransaction(tx);

    if ((window as any).vunothoApp) {
      (window as any).vunothoApp.showToast(`Payment Confirmed: ${vunothoPricing.formatUSD(tx.net_payout)} disbursed!`, 'success');
      this.displayReceipt(tx);
      (window as any).vunothoApp.refreshCurrentView();
    }
  }

  displayReceipt(tx: any) {
    const receiptHtml = `
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-warm-lg max-w-md mx-auto">
        <div class="text-center pb-4 border-b border-slate-100 mb-4">
          <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 font-bold mb-2">V</div>
          <h4 class="text-base font-extrabold text-slate-900 tracking-tight">VUNOTHO SETTLEMENT RECEIPT</h4>
          <p class="text-xs text-slate-500 font-mono mt-0.5">Ref: ${tx.reference} • ${new Date(tx.created_at).toLocaleDateString()}</p>
        </div>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between text-slate-600"><span>Beneficiary Farmer:</span><strong class="text-slate-900">${tx.farmer_name}</strong></div>
          <div class="flex justify-between text-slate-600"><span>Authorized Buyer:</span><strong class="text-slate-900">${tx.buyer_name}</strong></div>
          <div class="flex justify-between text-slate-600"><span>Commodity Lot:</span><strong class="text-slate-900">${Number(tx.quantity_kg).toLocaleString()} kg ${tx.crop}</strong></div>
          <div class="flex justify-between text-slate-600 pt-2 border-t border-dashed border-slate-200"><span>Gross Sale Value:</span><span class="font-bold text-slate-900">${vunothoPricing.formatUSD(tx.gross_total)}</span></div>
          <div class="flex justify-between text-amber-600"><span>Less: Pooled Transport:</span><span>-${vunothoPricing.formatUSD(tx.transport_deduction)}</span></div>
          <div class="flex justify-between text-slate-400 text-xs"><span>Less: Platform Coordination (4%):</span><span>-${vunothoPricing.formatUSD(tx.platform_fee)}</span></div>
          <div class="flex justify-between items-center pt-3 border-t-2 border-slate-900 mt-2">
            <span class="font-extrabold text-slate-900 text-base">Net Farmer Payout:</span>
            <span class="font-extrabold text-emerald-600 font-mono text-xl">${vunothoPricing.formatUSD(tx.net_payout)}</span>
          </div>
        </div>
        <div class="mt-5 p-2.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-semibold text-center border border-emerald-100 flex items-center justify-center gap-1.5">
          <span>✓</span> Payment Method: ${tx.payment_method} • Verified by Ledger
        </div>
      </div>
    `;

    const container = document.getElementById('receipt-modal-content');
    if (container) {
      container.innerHTML = receiptHtml;
      const modal = document.getElementById('receipt-modal');
      if (modal) modal.classList.add('active');
    }
  }
}

export const vunothoSettlement = new VunothoSettlement();
if (typeof window !== 'undefined') {
  (window as any).vunothoSettlement = vunothoSettlement;
}
