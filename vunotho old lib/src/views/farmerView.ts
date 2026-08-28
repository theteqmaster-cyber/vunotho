/**
 * VUNOTHO FARMER PORTAL VIEW (TypeScript)
 * Warm Modern Light Aesthetic with Rich Tinted Surfaces & Ambient Glassmorphism
 */

import { vunothoAPI } from '../api';
import { vunothoPricing } from '../pricing';
import { vunothoIcons } from '../icons';
import { vunothoAuth } from '../auth';
import { vunothoGeo } from '../geo';

export class FarmerView {
  public activeTab: 'market' | 'listings' | 'wallet' = 'market';
  private container: HTMLElement | null = null;

  async render(container: HTMLElement) {
    this.container = container;
    const listings = (await vunothoAPI.getListings()) || [];
    const demands = (await vunothoAPI.getDemands()) || [];
    const transactions = (await vunothoAPI.getTransactions()) || [];
    const user = vunothoAuth.getUser() || { id: 'USR-1787828875-2A15', name: 'Smallholder Farmer', district: 'Nyanga', province: 'Manicaland', kycStatus: 'Verified', role: 'farmer' as const, email_or_phone: '0773878836' };

    const myListings = listings.filter(l => l.user_id === user.id || l.farmer_name === user.name || !l.user_id);
    const geo = vunothoGeo.getInstantPosition();

    const iconSprout = vunothoIcons.get('sprout', 'text-emerald-600', 18);
    const iconPlus = vunothoIcons.get('plus', 'text-current', 16);
    const iconCalculator = vunothoIcons.get('calculator', 'text-emerald-600', 18);
    const iconBuilding = vunothoIcons.get('building', 'text-amber-600', 18);
    const iconBox = vunothoIcons.get('box', 'text-slate-600', 18);
    const iconTruck = vunothoIcons.get('truck', 'text-orange-600', 18);
    const iconMapPin = vunothoIcons.get('mapPin', 'text-slate-500', 14);
    const iconWallet = vunothoIcons.get('wallet', 'text-emerald-600', 18);

    container.innerHTML = `
      <!-- 1. IN-PORTAL HEADER CARD -->
      <div class="glass-panel p-6 md:p-8 mb-8 border border-slate-200/90 shadow-warm-md relative overflow-hidden bg-gradient-to-r from-white via-[#FAF8F5] to-[#F1F5F9]">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
          <div>
            <div class="flex items-center gap-2 flex-wrap mb-2">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100/90 text-emerald-800 text-xs font-bold border border-emerald-200">
                ${iconSprout} Smallholder Operations Desk • ${user.district || 'Nyanga'}, ${user.province || 'Manicaland'}
              </span>
              <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold font-mono">KYC: Verified</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
              Smallholder Produce Hub — <span class="text-emerald-600">Guaranteed Farmgate Value</span>
            </h1>
            <p class="text-xs md:text-sm text-slate-600 mt-1 max-w-2xl">
              Transparent take-home returns (<strong class="text-slate-900">Gross Price − Transport − 4% Fee</strong>), 2.5T rural light truck route pooling, and direct EcoCash settlements.
            </p>
          </div>

          <div class="flex items-center gap-3 flex-wrap">
            <button class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all flex items-center gap-2" onclick="document.getElementById('new-harvest-modal')?.classList.add('active')">
              ${iconPlus} Log New Harvest
            </button>
            <button class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-300 shadow-sm transition-all" onclick="window.farmerView.switchTab('market')">
              Price Intelligence
            </button>
          </div>
        </div>

        <!-- Animated Live Market Marquee Ticker -->
        <div class="mt-6 pt-4 border-t border-slate-200/80 ticker-wrap">
          <div class="ticker-track text-xs font-semibold text-slate-700 space-x-8">
            ${demands.map(d => `
              <span class="inline-flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-200">OPEN DEMAND</span>
                <strong>${d.crop}</strong>: ${Number(d.target_quantity_kg).toLocaleString()} kg @ <span class="text-emerald-700 font-bold font-mono">$${Number(d.offered_price_per_kg).toFixed(2)}/kg</span> (${d.district || 'Harare'})
              </span>
            `).join('')}
          </div>
        </div>
      </div>

      <!-- 2. QUICK METRICS OVERVIEW -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="glass-panel p-5 border-l-4 border-l-emerald-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">My Active Lots</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${myListings.length} Lots</div>
          <div class="text-xs text-emerald-700 font-medium mt-1">Volume: ${myListings.reduce((sum, i) => sum + Number(i.quantity_kg || 0), 0).toLocaleString()} kg</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-amber-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Net Earnings</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${vunothoPricing.formatUSD(transactions.reduce((sum, t) => sum + Number(t.net_payout || 0), 0))}</div>
          <div class="text-xs text-amber-700 font-medium mt-1">Disbursed to EcoCash</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-teal-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Verified Buyer Demands</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${demands.length} Orders</div>
          <div class="text-xs text-teal-700 font-medium mt-1">Matching Zimbabwean off-takers</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-orange-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Transport Savings</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${vunothoPricing.formatUSD(myListings.length * 14.5)}</div>
          <div class="text-xs text-orange-700 font-medium mt-1">Via pooled aggregation</div>
        </div>
      </div>

      <!-- 3. PORTAL SUB-NAVIGATION TABS -->
      <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-200/70 mb-8 max-w-fit text-xs font-bold text-slate-600">
        <button type="button" class="px-4 py-2.5 rounded-xl transition-all ${this.activeTab === 'market' ? 'bg-white text-emerald-800 shadow-warm-sm font-extrabold' : ''}" id="tab-btn-market" onclick="window.farmerView.switchTab('market')">
          ${iconBuilding} Live Market Exchange (${demands.length})
        </button>
        <button type="button" class="px-4 py-2.5 rounded-xl transition-all ${this.activeTab === 'listings' ? 'bg-white text-emerald-800 shadow-warm-sm font-extrabold' : ''}" id="tab-btn-listings" onclick="window.farmerView.switchTab('listings')">
          ${iconSprout} My Harvest Lots (${myListings.length})
        </button>
        <button type="button" class="px-4 py-2.5 rounded-xl transition-all ${this.activeTab === 'wallet' ? 'bg-white text-emerald-800 shadow-warm-sm font-extrabold' : ''}" id="tab-btn-wallet" onclick="window.farmerView.switchTab('wallet')">
          ${iconWallet} Wallet & Receipts (${transactions.length})
        </button>
      </div>

      <!-- 4. TAB 1: LIVE MARKET EXCHANGE -->
      <div id="tab-content-market" style="display: ${this.activeTab === 'market' ? 'block' : 'none'};">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
          <!-- Active Buyer Demands (Right off-takers) -->
          <div class="glass-panel p-6">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
              <div class="flex items-center gap-2">
                ${iconBuilding}
                <h3 class="font-extrabold text-base text-slate-900">Verified Commercial Buyer Demands</h3>
              </div>
              <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">${demands.length} Demands</span>
            </div>

            <div class="space-y-4">
              ${demands.map(item => `
                <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-warm-sm hover:border-emerald-400 transition-all">
                  <div class="flex justify-between items-start mb-3">
                    <div>
                      <h4 class="font-extrabold text-slate-900 text-base">${item.crop} Procurement Demand</h4>
                      <p class="text-xs text-slate-500 mt-0.5">Off-taker: <strong>${item.buyer_name}</strong> • Destination: <strong>${item.delivery_hub}</strong></p>
                    </div>
                    <span class="px-3 py-1 rounded-xl bg-amber-100 text-amber-800 font-extrabold text-sm font-mono">$${Number(item.offered_price_per_kg).toFixed(2)}/kg</span>
                  </div>

                  <div class="flex justify-between items-center pt-3 border-t border-dashed border-slate-200 mt-3 text-xs">
                    <span class="text-slate-600 font-bold">Target Volume: ${Number(item.target_quantity_kg).toLocaleString()} kg</span>
                    <button class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition-all" onclick="window.farmerView.matchBuyerDemand('${item.id}', '${item.crop}', ${item.offered_price_per_kg})">
                      Match & Accept Order →
                    </button>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>

          <!-- Active Farmgate Lots Feed -->
          <div class="glass-panel p-6">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
              <div class="flex items-center gap-2">
                ${iconSprout}
                <h3 class="font-extrabold text-base text-slate-900">Active Farmgate Lots (National Ledger)</h3>
              </div>
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">${listings.length} Lots</span>
            </div>

            <div class="space-y-3">
              ${listings.map(item => `
                <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-warm-sm flex justify-between items-center">
                  <div>
                    <div class="font-bold text-slate-900 text-sm">${item.crop}</div>
                    <div class="text-xs text-slate-500">${item.district || 'Nyanga'} • ${item.quality || 'Grade A'}</div>
                  </div>
                  <div class="text-right">
                    <div class="font-extrabold text-slate-900 font-mono text-sm">${Number(item.quantity_kg).toLocaleString()} kg</div>
                    <span class="text-xs font-semibold text-emerald-600">${item.sync_status || 'Synced'}</span>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>
        </div>

        <!-- In-Portal Net Return Decision Simulator -->
        <div class="glass-panel-elevated p-6 md:p-8 mb-8">
          <div class="mb-6">
            <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold mb-2">
              ${iconCalculator} Decision Intelligence
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">Interactive Net-Return Decision Simulator</h3>
            <p class="text-xs text-slate-600 mt-0.5">Evaluate take-home net profit before committing your produce to transport.</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
            <div class="md:col-span-5 space-y-5">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Commodity</label>
                <select id="farmer-calc-crop" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 font-semibold text-slate-900 text-sm" onchange="window.farmerView.updatePortalCalc()">
                  <option value="Tomatoes" selected>Tomatoes (Avg. $0.45/kg)</option>
                  <option value="Table Potatoes">Table Potatoes (Avg. $0.55/kg)</option>
                  <option value="Onions">Onions (Avg. $0.60/kg)</option>
                  <option value="Leafy Greens">Leafy Greens (Avg. $0.50/kg)</option>
                </select>
              </div>

              <div>
                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                  <span>Harvest Volume</span>
                  <span class="font-mono text-emerald-700" id="farmer-calc-weight-val">400 kg</span>
                </div>
                <input type="range" id="farmer-calc-weight-slider" min="50" max="2500" step="25" value="400" class="vunotho-slider" oninput="window.farmerView.updatePortalCalc()" />
              </div>

              <div>
                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                  <span>Distance to Market Hub</span>
                  <span class="font-mono text-amber-700" id="farmer-calc-dist-val">35 km</span>
                </div>
                <input type="range" id="farmer-calc-dist-slider" min="10" max="120" step="5" value="35" class="vunotho-slider" oninput="window.farmerView.updatePortalCalc()" />
              </div>
            </div>

            <div class="md:col-span-7 bg-slate-900 text-white p-6 rounded-2xl shadow-warm-lg">
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center pb-4 border-b border-slate-800">
                <div>
                  <div class="text-xs text-slate-400 font-semibold mb-1">Gross Value</div>
                  <div class="text-lg font-bold text-slate-100 font-mono" id="fcalc-gross-amt">$180.00</div>
                </div>
                <div>
                  <div class="text-xs text-amber-400 font-semibold mb-1">Pooled Transport</div>
                  <div class="text-lg font-bold text-amber-400 font-mono" id="fcalc-transport-amt">-$13.65</div>
                </div>
                <div>
                  <div class="text-xs text-slate-400 font-semibold mb-1">Fee (4%)</div>
                  <div class="text-lg font-bold text-slate-400 font-mono" id="fcalc-fee-amt">-$7.20</div>
                </div>
                <div class="bg-emerald-500/20 p-2 rounded-xl border border-emerald-500/30">
                  <div class="text-xs text-emerald-300 font-bold mb-1">Net Take-Home</div>
                  <div class="text-xl font-black text-emerald-400 font-mono" id="fcalc-net-amt">$159.15</div>
                </div>
              </div>
              <div class="mt-4 text-xs text-emerald-400 font-bold text-center" id="fcalc-savings-amt">
                ✓ $7.35 Saved via Pooled Rural Truck Load
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 5. TAB 2: MY HARVEST LISTINGS -->
      <div id="tab-content-listings" style="display: ${this.activeTab === 'listings' ? 'block' : 'none'};">
        <div class="glass-panel p-6 mb-8">
          <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <h3 class="font-extrabold text-base text-slate-900">My Registered Harvest Lots</h3>
            <button class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5" onclick="document.getElementById('new-harvest-modal')?.classList.add('active')">
              ${iconPlus} Log New Lot
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            ${myListings.map(item => `
              <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-warm-sm space-y-3">
                <div class="flex justify-between items-start">
                  <strong class="text-slate-900 text-base font-extrabold">${item.quantity_kg} kg • ${item.crop}</strong>
                  <span class="px-2.5 py-0.5 rounded-full ${item.sync_status === 'Saved Offline' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'} text-xs font-bold">${item.sync_status || 'Synced'}</span>
                </div>
                <div class="text-xs text-slate-500">
                  Grade: <strong>${item.quality}</strong> • District: <strong>${item.district || user.district || 'Nyanga'}</strong>
                </div>
                <div class="text-xs text-slate-400 font-mono">
                  GPS: ${item.lat}, ${item.lng}
                </div>
                <div class="pt-3 border-t border-dashed border-slate-200 flex justify-between items-center">
                  <span class="text-xs text-emerald-700 font-bold">Ready for Pickup</span>
                  <button class="text-xs text-slate-700 font-bold underline" onclick="window.farmerView.switchTab('market')">View Matching Off-takers →</button>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      </div>

      <!-- 6. TAB 3: WALLET & SETTLEMENT RECEIPTS -->
      <div id="tab-content-wallet" style="display: ${this.activeTab === 'wallet' ? 'block' : 'none'};">
        <div class="glass-panel p-6 mb-8">
          <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <h3 class="font-extrabold text-base text-slate-900">Verifiable EcoCash Settlement Ledger</h3>
            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">${transactions.length} Receipts</span>
          </div>

          <div class="space-y-3">
            ${transactions.map(t => `
              <div class="p-4 rounded-xl bg-white border border-slate-200/90 shadow-warm-sm flex justify-between items-center">
                <div>
                  <strong class="text-slate-900 text-sm font-extrabold">${t.crop} Settlement (${Number(t.quantity_kg || 0).toLocaleString()} kg)</strong>
                  <div class="text-xs text-slate-500 mt-0.5">Ref: <code class="font-mono text-slate-700">${t.receipt_reference || t.reference || t.id}</code> • Buyer: <strong>${t.buyer_name}</strong></div>
                </div>
                <div class="text-right">
                  <div class="font-black text-emerald-600 text-base font-mono">${vunothoPricing.formatUSD(t.net_payout || 0)}</div>
                  <span class="text-xs font-semibold text-slate-400">Gross: ${vunothoPricing.formatUSD(t.gross_total || 0)}</span>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      </div>

      <!-- 7. MODAL: LOG NEW HARVEST -->
      <div id="new-harvest-modal" class="vunotho-modal-backdrop">
        <div class="glass-panel-elevated max-w-md w-full p-6 md:p-8 relative">
          <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
            <h3 class="text-lg font-extrabold text-slate-900">Log New Smallholder Harvest</h3>
            <button class="text-slate-400 hover:text-slate-700 font-bold" onclick="document.getElementById('new-harvest-modal')?.classList.remove('active')">✕</button>
          </div>

          <form id="new-harvest-form" onsubmit="window.farmerView.handleCreateHarvest(event)">
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Agricultural Commodity</label>
                <select id="harvest-crop" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold" required>
                  <option value="Tomatoes" selected>Tomatoes (Round / Roma Sandak)</option>
                  <option value="Table Potatoes">Table Potatoes (15kg Mesh Pocket)</option>
                  <option value="Onions">Onions (10kg Pocket)</option>
                  <option value="Leafy Greens">Leafy Greens (Tsunga / Covo / Rape)</option>
                  <option value="Butternut Squash">Butternut Squash (10kg Pocket)</option>
                  <option value="Cabbages">Cabbages (Drumhead Bulk)</option>
                  <option value="Green Peppers">Green Peppers (20L Tin / Sacks)</option>
                  <option value="Carrots">Carrots (50kg Sack)</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Harvest Volume (Kilograms)</label>
                <input type="number" id="harvest-qty" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" placeholder="e.g. 350" min="10" step="5" required />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Quality Grading Spec</label>
                <select id="harvest-quality" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold" required>
                  <option value="Grade A (Supermarket Spec)" selected>Tier 1: Grade A (Supermarket & Fresh Wholesale Spec)</option>
                  <option value="Grade B (Agro-Processing)">Tier 2: Grade B (Agro-Processing - Crisps, Flour, Starch)</option>
                  <option value="Grade C (Animal Feed / Livestock)">Tier 3: Grade C (Livestock Feed / Pig & Cattle Rations)</option>
                  <option value="Bio-Compost Biomass">Tier 4: Bio-Compost Organic Biomass</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Farmgate Collection Coordinates (GPS)</label>
                <div class="grid grid-cols-2 gap-2">
                  <input type="text" id="harvest-lat" class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs font-mono" value="${geo.lat}" readonly />
                  <input type="text" id="harvest-lng" class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs font-mono" value="${geo.lng}" readonly />
                </div>
              </div>

              <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                <button type="button" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs" onclick="document.getElementById('new-harvest-modal')?.classList.remove('active')">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald" id="btn-save-harvest">Register Harvest Lot</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    `;

    this.updatePortalCalc();
  }

  switchTab(tabKey: 'market' | 'listings' | 'wallet') {
    this.activeTab = tabKey;
    ['market', 'listings', 'wallet'].forEach(t => {
      const btn = document.getElementById(`tab-btn-${t}`);
      const content = document.getElementById(`tab-content-${t}`);
      if (btn) {
        if (t === tabKey) {
          btn.className = 'px-4 py-2.5 rounded-xl transition-all bg-white text-emerald-800 shadow-warm-sm font-extrabold';
        } else {
          btn.className = 'px-4 py-2.5 rounded-xl transition-all text-slate-600';
        }
      }
      if (content) content.style.display = (t === tabKey) ? 'block' : 'none';
    });
  }

  updatePortalCalc() {
    const cropSelect = document.getElementById('farmer-calc-crop') as HTMLSelectElement;
    const weightSlider = document.getElementById('farmer-calc-weight-slider') as HTMLInputElement;
    const distSlider = document.getElementById('farmer-calc-dist-slider') as HTMLInputElement;

    if (!cropSelect || !weightSlider || !distSlider) return;

    const crop = cropSelect.value;
    const weightKg = Number(weightSlider.value);
    const distKm = Number(distSlider.value);

    const weightVal = document.getElementById('farmer-calc-weight-val');
    const distVal = document.getElementById('farmer-calc-dist-val');
    if (weightVal) weightVal.textContent = `${weightKg.toLocaleString()} kg`;
    if (distVal) distVal.textContent = `${distKm} km`;

    const cropPrices: Record<string, number> = {
      'Tomatoes': 0.45,
      'Table Potatoes': 0.55,
      'Onions': 0.60,
      'Leafy Greens': 0.50
    };

    const unitPrice = cropPrices[crop] || 0.45;
    const breakdown = vunothoPricing.calculateNetReturn(unitPrice, weightKg, distKm, true);

    const grossAmt = document.getElementById('fcalc-gross-amt');
    const transportAmt = document.getElementById('fcalc-transport-amt');
    const feeAmt = document.getElementById('fcalc-fee-amt');
    const netAmt = document.getElementById('fcalc-net-amt');
    const savingsAmt = document.getElementById('fcalc-savings-amt');

    if (grossAmt) grossAmt.textContent = `$${breakdown.grossTotal.toFixed(2)}`;
    if (transportAmt) transportAmt.textContent = `-$${breakdown.transportTotal.toFixed(2)}`;
    if (feeAmt) feeAmt.textContent = `-$${breakdown.platformFeeTotal.toFixed(2)}`;
    if (netAmt) netAmt.textContent = `$${breakdown.netTotal.toFixed(2)}`;
    if (savingsAmt) savingsAmt.textContent = `✓ $${breakdown.transportSavings.toFixed(2)} Saved via Pooled Rural Truck Load`;
  }

  async handleCreateHarvest(event: Event) {
    event.preventDefault();
    const crop = (document.getElementById('harvest-crop') as HTMLSelectElement).value;
    const quantity_kg = Number((document.getElementById('harvest-qty') as HTMLInputElement).value);
    const quality = (document.getElementById('harvest-quality') as HTMLSelectElement).value;
    const lat = Number((document.getElementById('harvest-lat') as HTMLInputElement).value);
    const lng = Number((document.getElementById('harvest-lng') as HTMLInputElement).value);

    const user = vunothoAuth.getUser();

    const payload = {
      crop,
      quantity_kg,
      quality,
      lat,
      lng,
      farmer_name: user ? user.name : 'Smallholder Farmer',
      user_id: user ? user.id : undefined,
      district: user ? user.district : 'Nyanga',
      province: user ? user.province : 'Manicaland'
    };

    try {
      await vunothoAPI.createListing(payload);
      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast(`Harvest lot of ${quantity_kg}kg ${crop} logged!`, 'success');
      }
      document.getElementById('new-harvest-modal')?.classList.remove('active');
      if (this.container) this.render(this.container);
    } catch (err: any) {
      alert(`Error saving harvest: ${err.message}`);
    }
  }

  async matchBuyerDemand(demandId: string, crop: string, offeredPrice: number) {
    const user = vunothoAuth.getUser();
    if ((window as any).vunothoApp) {
      (window as any).vunothoApp.showToast(`Matching your ${crop} lot to commercial demand at $${Number(offeredPrice).toFixed(2)}/kg...`, 'info');
    }

    try {
      const ref = 'ECO-' + Math.floor(100000 + Math.random() * 900000);
      const gross = Number((250 * offeredPrice).toFixed(2));
      const transport = Number((250 * 35 * 0.0015 * 0.65).toFixed(2));
      const fee = Number((gross * 0.04).toFixed(2));
      const net = Number((gross - transport - fee).toFixed(2));

      await vunothoAPI.createTransaction({
        id: `TX-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
        reference: ref,
        receipt_reference: ref,
        payment_method: 'EcoCash Mobile Wallet',
        farmer_id: user ? user.id : 'FARMER-01',
        farmer_name: user ? user.name : 'Smallholder Farmer',
        buyer_id: 'USR-BUYER-01',
        buyer_name: 'Bulawayo Fresh Wholesalers',
        crop,
        quantity_kg: 250,
        gross_total: gross,
        transport_deduction: transport,
        transport_cost: transport,
        platform_fee: fee,
        net_payout: net,
        status: 'Settled',
        created_at: new Date().toISOString()
      });

      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast(`Order matched! EcoCash settlement receipt generated.`, 'success');
      }
      this.switchTab('wallet');
      if (this.container) this.render(this.container);
    } catch (e: any) {
      alert(`Order match error: ${e.message}`);
    }
  }
}

export const farmerView = new FarmerView();
if (typeof window !== 'undefined') {
  (window as any).farmerView = farmerView;
}
