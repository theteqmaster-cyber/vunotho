/**
 * VUNOTHO BUYER PORTAL VIEW (TypeScript)
 * Warm Modern Light Aesthetic with Rich Tinted Surfaces & Ambient Glassmorphism
 */

import { vunothoAPI } from '../api';
import { vunothoPricing } from '../pricing';
import { vunothoIcons } from '../icons';
import { vunothoAuth } from '../auth';

export class BuyerView {
  public activeTab: 'supply' | 'demands' | 'orders' = 'supply';
  private container: HTMLElement | null = null;

  async render(container: HTMLElement) {
    this.container = container;
    const demands = (await vunothoAPI.getDemands()) || [];
    const listings = (await vunothoAPI.getListings()) || [];
    const transactions = (await vunothoAPI.getTransactions()) || [];
    const user = vunothoAuth.getUser() || { id: 'USR-BUYER-01', name: 'Commercial Off-taker', district: 'Harare', province: 'Harare', role: 'buyer' as const, email_or_phone: 'buyer@freshwholesalers.co.zw' };

    const myDemands = demands.filter(d => d.user_id === user.id || d.buyer_name === user.name || !d.user_id);

    const iconBuilding = vunothoIcons.get('building', 'text-amber-600', 18);
    const iconPlus = vunothoIcons.get('plus', 'text-current', 16);
    const iconBox = vunothoIcons.get('box', 'text-slate-600', 18);
    const iconTruck = vunothoIcons.get('truck', 'text-orange-600', 18);
    const iconSprout = vunothoIcons.get('sprout', 'text-emerald-600', 18);

    container.innerHTML = `
      <!-- 1. IN-PORTAL HEADER CARD -->
      <div class="glass-panel p-6 md:p-8 mb-8 border border-slate-200/90 shadow-warm-md relative overflow-hidden bg-gradient-to-r from-white via-[#FAF8F5] to-[#F1F5F9]">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
          <div>
            <div class="flex items-center gap-2 flex-wrap mb-2">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100/90 text-amber-800 text-xs font-bold border border-amber-200">
                ${iconBuilding} Commercial Procurement Desk • ${user.district || 'Harare CBD'}, ${user.province || 'Harare'}
              </span>
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono">KYC: Verified Off-taker</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
              Commercial Sourcing Hub — <span class="text-amber-600">Verified Farmgate Supply</span>
            </h1>
            <p class="text-xs md:text-sm text-slate-600 mt-1 max-w-2xl">
              Source verified smallholder produce directly at transparent wholesale rates, with aggregated 2.5T truck fulfillment and guaranteed quality specs.
            </p>
          </div>

          <div class="flex items-center gap-3 flex-wrap">
            <button class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-warm-md transition-all flex items-center gap-2" onclick="document.getElementById('new-demand-modal')?.classList.add('active')">
              ${iconPlus} Post Sourcing Demand
            </button>
            <button class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-300 shadow-sm transition-all" onclick="window.buyerView.switchTab('supply')">
              Browse Farmgate Lots
            </button>
          </div>
        </div>

        <!-- Animated Live Supply Marquee Ticker -->
        <div class="mt-6 pt-4 border-t border-slate-200/80 ticker-wrap">
          <div class="ticker-track text-xs font-semibold text-slate-700 space-x-8">
            ${listings.map(l => `
              <span class="inline-flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-200">AVAILABLE LOT</span>
                <strong>${l.crop}</strong>: ${Number(l.quantity_kg).toLocaleString()} kg ready in <strong>${l.district || 'Nyanga'}</strong> (${l.quality || 'Grade A'})
              </span>
            `).join('')}
          </div>
        </div>
      </div>

      <!-- 2. QUICK PROCUREMENT METRICS -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="glass-panel p-5 border-l-4 border-l-amber-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Active Demands</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${myDemands.length} Demands</div>
          <div class="text-xs text-amber-700 font-medium mt-1">Target: ${myDemands.reduce((sum, d) => sum + Number(d.target_quantity_kg || 0), 0).toLocaleString()} kg</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-emerald-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Available Supply</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${listings.reduce((sum, l) => sum + Number(l.quantity_kg || 0), 0).toLocaleString()} kg</div>
          <div class="text-xs text-emerald-700 font-medium mt-1">Across ${listings.length} smallholder lots</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-teal-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Purchases Settled</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${vunothoPricing.formatUSD(transactions.reduce((sum, t) => sum + Number(t.gross_total || 0), 0))}</div>
          <div class="text-xs text-teal-700 font-medium mt-1">Total order volume</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-orange-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Fulfillment Reliability</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">98.4%</div>
          <div class="text-xs text-orange-700 font-medium mt-1">Verified farmgate collection</div>
        </div>
      </div>

      <!-- 3. PORTAL SUB-NAVIGATION TABS -->
      <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-200/70 mb-8 max-w-fit text-xs font-bold text-slate-600">
        <button type="button" class="px-4 py-2.5 rounded-xl transition-all ${this.activeTab === 'supply' ? 'bg-white text-slate-900 shadow-warm-sm font-extrabold' : ''}" id="tab-btn-buyer-supply" onclick="window.buyerView.switchTab('supply')">
          ${iconSprout} Farmgate Supply Exchange (${listings.length})
        </button>
        <button type="button" class="px-4 py-2.5 rounded-xl transition-all ${this.activeTab === 'demands' ? 'bg-white text-slate-900 shadow-warm-sm font-extrabold' : ''}" id="tab-btn-buyer-demands" onclick="window.buyerView.switchTab('demands')">
          ${iconBuilding} My Procurement Demands (${myDemands.length})
        </button>
        <button type="button" class="px-4 py-2.5 rounded-xl transition-all ${this.activeTab === 'orders' ? 'bg-white text-slate-900 shadow-warm-sm font-extrabold' : ''}" id="tab-btn-buyer-orders" onclick="window.buyerView.switchTab('orders')">
          ${iconTruck} Fulfillment Tracking (${transactions.length})
        </button>
      </div>

      <!-- 4. TAB 1: SUPPLY EXCHANGE -->
      <div id="tab-buyer-supply" style="display: ${this.activeTab === 'supply' ? 'block' : 'none'};">
        <div class="glass-panel p-6 mb-8">
          <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <div>
              <h3 class="font-extrabold text-base text-slate-900">Available Smallholder Farmgate Lots</h3>
              <p class="text-xs text-slate-500 mt-0.5">Direct aggregation lots registered by verified smallholders across Zimbabwe.</p>
            </div>
            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">${listings.length} Lots</span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            ${listings.map(item => `
              <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-warm-sm space-y-3 hover:border-amber-400 transition-all">
                <div class="flex justify-between items-start">
                  <strong class="text-slate-900 text-base font-extrabold">${item.crop}</strong>
                  <span class="px-2.5 py-0.5 rounded-full ${item.quality && item.quality.includes('Grade A') ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'} text-xs font-bold">${item.quality || 'Grade A'}</span>
                </div>
                <div class="text-sm font-extrabold text-slate-900 font-mono">
                  ${Number(item.quantity_kg || 0).toLocaleString()} kg available
                </div>
                <div class="text-xs text-slate-500">
                  Origin: <strong>${item.district || 'Nyanga'}, ${item.province || 'Manicaland'}</strong> • Farmer: <strong>${item.farmer_name}</strong>
                </div>
                <div class="pt-3 border-t border-dashed border-slate-200 flex justify-between items-center">
                  <span class="text-xs text-slate-400 font-mono">GPS: ${item.lat}, ${item.lng}</span>
                  <button class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs" onclick="window.buyerView.orderHarvestLot('${item.id}', '${item.crop}', ${item.quantity_kg})">
                    Order Lot →
                  </button>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      </div>

      <!-- 5. TAB 2: MY DEMANDS -->
      <div id="tab-buyer-demands" style="display: ${this.activeTab === 'demands' ? 'block' : 'none'};">
        <div class="glass-panel p-6 mb-8">
          <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <h3 class="font-extrabold text-base text-slate-900">My Posted Procurement Demands</h3>
            <button class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center gap-1.5" onclick="document.getElementById('new-demand-modal')?.classList.add('active')">
              ${iconPlus} Post New Demand
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            ${myDemands.map(d => `
              <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-warm-sm space-y-3">
                <div class="flex justify-between items-start">
                  <strong class="text-slate-900 text-base font-extrabold">${d.crop} Demand</strong>
                  <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold font-mono">$${Number(d.offered_price_per_kg || 0).toFixed(2)}/kg</span>
                </div>
                <div class="text-sm font-extrabold text-slate-900 font-mono">
                  Target: ${Number(d.target_quantity_kg || 0).toLocaleString()} kg
                </div>
                <div class="text-xs text-slate-500">
                  Destination: <strong>${d.delivery_hub || 'Harare Central Market'}</strong>
                </div>
                <div class="pt-3 border-t border-dashed border-slate-200 flex justify-between items-center">
                  <span class="text-xs font-semibold text-emerald-600">Active / Matching</span>
                  <button class="text-xs text-slate-700 font-bold underline" onclick="window.buyerView.switchTab('supply')">Browse Matching Crops →</button>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      </div>

      <!-- 6. TAB 3: FULFILLMENT & ORDERS -->
      <div id="tab-buyer-orders" style="display: ${this.activeTab === 'orders' ? 'block' : 'none'};">
        <div class="glass-panel p-6 mb-8">
          <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <h3 class="font-extrabold text-base text-slate-900">Fulfilled Purchases & Transporter Route Tracking</h3>
            <span class="px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800 text-xs font-bold">${transactions.length} Orders</span>
          </div>

          <div class="space-y-3">
            ${transactions.map(t => `
              <div class="p-4 rounded-xl bg-white border border-slate-200/90 shadow-warm-sm flex justify-between items-center">
                <div>
                  <strong class="text-slate-900 text-sm font-extrabold">${t.crop} Procurement (${Number(t.quantity_kg || 0).toLocaleString()} kg)</strong>
                  <div class="text-xs text-slate-500 mt-0.5">Order Ref: <code class="font-mono text-slate-700">${t.receipt_reference || t.reference || t.id}</code> • Farmer: <strong>${t.farmer_name}</strong></div>
                </div>
                <div class="text-right">
                  <div class="font-extrabold text-slate-900 text-base font-mono">${vunothoPricing.formatUSD(t.gross_total || 0)}</div>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">Dispatched</span>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      </div>

      <!-- 7. MODAL: POST SOURCING DEMAND -->
      <div id="new-demand-modal" class="vunotho-modal-backdrop">
        <div class="glass-panel-elevated max-w-md w-full p-6 md:p-8 relative">
          <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
            <h3 class="text-lg font-extrabold text-slate-900">Post Commercial Purchase Demand</h3>
            <button class="text-slate-400 hover:text-slate-700 font-bold" onclick="document.getElementById('new-demand-modal')?.classList.remove('active')">✕</button>
          </div>

          <form id="new-demand-form" onsubmit="window.buyerView.handleCreateDemand(event)">
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Required Commodity</label>
                <select id="demand-crop" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold" required>
                  <option value="Tomatoes" selected>Tomatoes (Round / Roma Sandak)</option>
                  <option value="Table Potatoes">Table Potatoes (15kg Mesh Pocket)</option>
                  <option value="Onions">Onions (10kg Pocket)</option>
                  <option value="Leafy Greens">Leafy Greens (Tsunga / Covo / Rape)</option>
                  <option value="Butternut Squash">Butternut Squash (10kg Pocket)</option>
                  <option value="Green Peppers">Green Peppers (20L Tin / Sacks)</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Target Volume (Kilograms)</label>
                <input type="number" id="demand-qty" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" placeholder="e.g. 1000" min="50" step="50" required />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Offered Farmgate Price ($/kg)</label>
                <input type="number" id="demand-price" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" placeholder="e.g. 0.55" min="0.10" max="3.00" step="0.01" required />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Quality Grading Spec</label>
                <select id="demand-quality" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold" required>
                  <option value="Grade A (Supermarket Spec)" selected>Grade A: Supermarket & Wholesale Spec</option>
                  <option value="Grade B (Agro-Processing)">Grade B: Agro-Processing</option>
                  <option value="Commercial Mixed">Commercial Mixed Standard</option>
                </select>
              </div>

              <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                <button type="button" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs" onclick="document.getElementById('new-demand-modal')?.classList.remove('active')">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs" id="btn-save-demand">Publish Demand Order</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    `;
  }

  switchTab(tabKey: 'supply' | 'demands' | 'orders') {
    this.activeTab = tabKey;
    ['supply', 'demands', 'orders'].forEach(t => {
      const btn = document.getElementById(`tab-btn-buyer-${t}`);
      const content = document.getElementById(`tab-buyer-${t}`);
      if (btn) {
        if (t === tabKey) {
          btn.className = 'px-4 py-2.5 rounded-xl transition-all bg-white text-slate-900 shadow-warm-sm font-extrabold';
        } else {
          btn.className = 'px-4 py-2.5 rounded-xl transition-all text-slate-600';
        }
      }
      if (content) content.style.display = (t === tabKey) ? 'block' : 'none';
    });
  }

  async handleCreateDemand(event: Event) {
    event.preventDefault();
    const crop = (document.getElementById('demand-crop') as HTMLSelectElement).value;
    const target_quantity_kg = Number((document.getElementById('demand-qty') as HTMLInputElement).value);
    const offered_price_per_kg = Number((document.getElementById('demand-price') as HTMLInputElement).value);
    const quality_tier = (document.getElementById('demand-quality') as HTMLSelectElement).value;

    const user = vunothoAuth.getUser();

    const payload = {
      crop,
      target_quantity_kg,
      offered_price_per_kg,
      quality_tier,
      quality_required: quality_tier,
      buyer_name: user ? (user.organisation || user.name) : 'Commercial Buyer',
      user_id: user ? user.id : undefined,
      delivery_hub: user?.district ? `${user.district} Wholesale Hub` : 'Harare Central Market',
      district: user ? user.district : 'Harare CBD',
      province: user ? user.province : 'Harare'
    };

    try {
      await vunothoAPI.createDemand(payload);
      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast(`Procurement demand for ${target_quantity_kg}kg ${crop} published!`, 'success');
      }
      document.getElementById('new-demand-modal')?.classList.remove('active');
      this.switchTab('demands');
      if (this.container) this.render(this.container);
    } catch (err: any) {
      alert(`Error publishing demand: ${err.message}`);
    }
  }

  async orderHarvestLot(listingId: string, crop: string, quantityKg: number) {
    const user = vunothoAuth.getUser();
    if ((window as any).vunothoApp) {
      (window as any).vunothoApp.showToast(`Locking ${quantityKg}kg ${crop} for commercial dispatch...`, 'info');
    }

    try {
      const ref = 'ECO-' + Math.floor(100000 + Math.random() * 900000);
      const gross = Number((quantityKg * 0.50).toFixed(2));
      const transport = Number((quantityKg * 35 * 0.0015 * 0.65).toFixed(2));
      const fee = Number((gross * 0.04).toFixed(2));
      const net = Number((gross - transport - fee).toFixed(2));

      await vunothoAPI.createTransaction({
        id: `TX-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
        reference: ref,
        receipt_reference: ref,
        payment_method: 'EcoCash Mobile Wallet',
        farmer_id: 'USR-1787828875-2A15',
        farmer_name: 'Sipho Moyo',
        buyer_id: user ? user.id : 'USR-BUYER-01',
        buyer_name: user ? (user.organisation || user.name) : 'Commercial Wholesaler',
        crop,
        quantity_kg: quantityKg,
        gross_total: gross,
        transport_deduction: transport,
        transport_cost: transport,
        platform_fee: fee,
        net_payout: net,
        status: 'Settled',
        created_at: new Date().toISOString()
      });

      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast(`Harvest lot locked! Dispatched to pooled transporter route.`, 'success');
      }
      this.switchTab('orders');
      if (this.container) this.render(this.container);
    } catch (e: any) {
      alert(`Order error: ${e.message}`);
    }
  }
}

export const buyerView = new BuyerView();
if (typeof window !== 'undefined') {
  (window as any).buyerView = buyerView;
}
