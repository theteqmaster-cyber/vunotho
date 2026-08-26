/**
 * VUNOTHO SANDBOX SETTLEMENT & DIGITAL RECEIPT ENGINE
 * Realistic Mobile Money (EcoCash/InnBucks) and Cash on Delivery sandbox workflows.
 */

class VunothoSettlement {
  constructor() {
    this.currentPin = '';
    this.activeTxData = null;
  }

  /**
   * Initiate sandbox payment workflow
   * @param {Object} txDetails - Transaction parameters (farmer, buyer, amounts)
   */
  async initiatePayment(txDetails) {
    this.activeTxData = txDetails;
    if (txDetails.paymentMethod === 'ecocash') {
      this.openPinModal();
    } else {
      // Cash on Delivery
      await this.completeSettlement('Cash on Farmgate Collection', 'COD-SETTLED');
    }
  }

  openPinModal() {
    this.currentPin = '';
    this.updatePinDisplay();
    const modal = document.getElementById('payment-pin-modal');
    if (modal) {
      document.getElementById('pin-modal-amount').textContent = window.vunothoPricing.formatUSD(this.activeTxData.netTotal);
      document.getElementById('pin-modal-farmer').textContent = this.activeTxData.farmer_name || 'Farmer';
      modal.classList.add('active');
    }
  }

  closePinModal() {
    const modal = document.getElementById('payment-pin-modal');
    if (modal) modal.classList.remove('active');
    this.currentPin = '';
    this.activeTxData = null;
  }

  handlePinInput(digit) {
    if (this.currentPin.length < 4) {
      this.currentPin += digit;
      this.updatePinDisplay();
      if (this.currentPin.length === 4) {
        // Auto-submit after 4 digits
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
    // Simulate instant EcoCash network confirmation
    const pin = this.currentPin;
    this.closePinModal();

    if (window.vunothoApp) {
      window.vunothoApp.showToast('Verifying EcoCash PIN & Authorizing Transfer...', 'info');
    }

    setTimeout(async () => {
      const authRef = `ECO-${Math.floor(100000 + Math.random() * 900000)}`;
      await this.completeSettlement('EcoCash Mobile Wallet', authRef);
    }, 1000);
  }

  async completeSettlement(methodTitle, referenceCode) {
    const tx = {
      id: `TX-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
      reference: referenceCode,
      payment_method: methodTitle,
      farmer_id: this.activeTxData?.farmer_id || 'FARMER-1',
      farmer_name: this.activeTxData?.farmer_name || 'Farmer',
      buyer_id: this.activeTxData?.buyer_id || 'BUYER-1',
      buyer_name: this.activeTxData?.buyer_name || 'Commercial Buyer',
      crop: this.activeTxData?.crop || 'Potatoes',
      quantity_kg: Number(this.activeTxData?.quantity_kg || 0),
      gross_total: Number(this.activeTxData?.grossTotal || 0),
      transport_deduction: Number(this.activeTxData?.transportTotal || 0),
      platform_fee: Number(this.activeTxData?.platformFeeTotal || 0),
      net_payout: Number(this.activeTxData?.netTotal || 0),
      created_at: new Date().toISOString(),
      status: 'Settled'
    };

    // Save to PHP Database & local IndexedDB cache
    await window.vunothoAPI.createTransaction(tx);

    if (window.vunothoApp) {
      window.vunothoApp.showToast(`Payment Confirmed: ${window.vunothoPricing.formatUSD(tx.net_payout)} disbursed!`, 'info');
      this.displayReceipt(tx);
      window.vunothoApp.refreshCurrentView();
    }
  }

  displayReceipt(tx) {
    const receiptHtml = `
      <div class="receipt-box">
        <div style="text-align: center; margin-bottom: 1rem;">
          <h4 style="font-weight: 800; color: var(--navy-900);">VUNOTHO SETTLEMENT RECEIPT</h4>
          <p style="font-size: 0.75rem; color: var(--text-muted);">Ref: ${tx.reference} • ${new Date(tx.created_at).toLocaleDateString()}</p>
        </div>
        <div class="receipt-row"><span>Beneficiary Farmer:</span><strong>${tx.farmer_name}</strong></div>
        <div class="receipt-row"><span>Authorized Buyer:</span><strong>${tx.buyer_name}</strong></div>
        <div class="receipt-row"><span>Commodity:</span><strong>${tx.quantity_kg} kg ${tx.crop}</strong></div>
        <div class="receipt-row"><span>Gross Sale Value:</span><span>${window.vunothoPricing.formatUSD(tx.gross_total)}</span></div>
        <div class="receipt-row" style="color: var(--orange-600);"><span>Less: Transport Fee:</span><span>-${window.vunothoPricing.formatUSD(tx.transport_deduction)}</span></div>
        <div class="receipt-row" style="color: var(--text-muted);"><span>Less: Marketplace Fee (4%):</span><span>-${window.vunothoPricing.formatUSD(tx.platform_fee)}</span></div>
        <div class="receipt-row total"><span>Net Farmer Payout:</span><span style="color: var(--green-600);">${window.vunothoPricing.formatUSD(tx.net_payout)}</span></div>
        <div style="margin-top: 1rem; font-size: 0.75rem; color: var(--text-muted); text-align: center;">
          Payment Method: ${tx.payment_method} • Status: VERIFIED
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

window.vunothoSettlement = new VunothoSettlement();
