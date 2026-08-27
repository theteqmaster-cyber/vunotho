/**
 * VUNOTHO TRANSPORTER LOGISTICS VIEW (TypeScript)
 * Warm Modern Light Aesthetic with Rich Tinted Surfaces & Ambient Glassmorphism
 */

import { vunothoAPI } from '../api';
import { vunothoLogistics } from '../logistics';
import { vunothoPricing } from '../pricing';
import { vunothoIcons } from '../icons';
import { vunothoAuth } from '../auth';

export class TransporterView {
  public activeTab: 'manifests' | 'fleet' | 'ledger' = 'manifests';
  private container: HTMLElement | null = null;

  async render(container: HTMLElement) {
    this.container = container;
    const listings = (await vunothoAPI.getListings()) || [];
    const transactions = (await vunothoAPI.getTransactions()) || [];
    const manifests = vunothoLogistics.aggregateListings(listings);
    const user = vunothoAuth.getUser() || { id: 'USR-TRANSPORTER-01', name: 'Logistics Haulier', district: 'Nyanga', vehicle_type: '2.5 Tonne Rural Light Truck', role: 'transporter' as const, email_or_phone: 'haulier@vunothofleet.co.zw' };

    const iconTruck = vunothoIcons.get('truck', 'text-orange-600', 18);
    const iconMapPin = vunothoIcons.get('mapPin', 'text-slate-500', 14);
    const iconShield = vunothoIcons.get('shieldCheck', 'text-emerald-600', 18);
    const iconWallet = vunothoIcons.get('wallet', 'text-emerald-600', 18);

    container.innerHTML = `
      <!-- 1. IN-PORTAL HEADER CARD -->
      <div class="glass-panel p-6 md:p-8 mb-8 border border-slate-200/90 shadow-warm-md relative overflow-hidden bg-gradient-to-r from-white via-[#FAF8F5] to-[#F1F5F9]">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
          <div>
            <div class="flex items-center gap-2 flex-wrap mb-2">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100/90 text-orange-800 text-xs font-bold border border-orange-200">
                ${iconTruck} Rural Freight Logistics Desk • ${user.district || 'Nyanga Base'}
              </span>
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono">2.5T Fleet: Active</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
              Freight Aggregation — <span class="text-orange-600">2.5T Pooled Route Manifests</span>
            </h1>
            <p class="text-xs md:text-sm text-slate-600 mt-1 max-w-2xl">
              Eliminate empty backhauls by aggregating multi-farmer smallholder harvests into guaranteed 2.5-tonne rural collection routes.
            </p>
          </div>

          <div class="flex items-center gap-3 flex-wrap">
            <button class="px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold text-xs shadow-glow-orange transition-all flex items-center gap-2" onclick="window.transporterView.switchTab('manifests')">
              ${iconTruck} View Clustered Loads
            </button>
          </div>
        </div>
      </div>

      <!-- 2. QUICK FLEET METRICS -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="glass-panel p-5 border-l-4 border-l-orange-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Available Manifests</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${manifests.length} Routes</div>
          <div class="text-xs text-orange-700 font-medium mt-1">Total Payload: ${manifests.reduce((sum, m) => sum + m.totalWeightKg, 0).toLocaleString()} kg</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-emerald-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Capacity Efficiency</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">82.4%</div>
          <div class="text-xs text-emerald-700 font-medium mt-1">Average 2.5T load utilization</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-teal-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Freight Remittances</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${vunothoPricing.formatUSD(transactions.reduce((sum, t) => sum + Number(t.transport_deduction || 0), 0))}</div>
          <div class="text-xs text-teal-700 font-medium mt-1">Paid directly per delivery</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-amber-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Diesel Cost Recovery</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">Guaranteed</div>
          <div class="text-xs text-amber-700 font-medium mt-1">Escrowed on order locking</div>
        </div>
      </div>

      <!-- 3. PORTAL SUB-NAVIGATION TABS -->
      <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-200/70 mb-8 max-w-fit text-xs font-bold text-slate-600">
        <button type="button" class="px-4 py-2.5 rounded-xl transition-all ${this.activeTab === 'manifests' ? 'bg-white text-slate-900 shadow-warm-sm font-extrabold' : ''}" id="tab-btn-trans-manifests" onclick="window.transporterView.switchTab('manifests')">
          ${iconTruck} Aggregated Route Manifests (${manifests.length})
        </button>
        <button type="button" class="px-4 py-2.5 rounded-xl transition-all ${this.activeTab === 'ledger' ? 'bg-white text-slate-900 shadow-warm-sm font-extrabold' : ''}" id="tab-btn-trans-ledger" onclick="window.transporterView.switchTab('ledger')">
          ${iconWallet} Fuel & Transport Remittances (${transactions.length})
        </button>
      </div>

      <!-- 4. TAB 1: ROUTE MANIFESTS -->
      <div id="tab-trans-manifests" style="display: ${this.activeTab === 'manifests' ? 'block' : 'none'};">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          ${manifests.map(m => `
            <div class="glass-panel p-6 space-y-4 hover:border-orange-300 transition-all">
              <div class="flex justify-between items-start">
                <div>
                  <span class="text-xs font-bold uppercase tracking-wider text-orange-700 font-mono">${m.id}</span>
                  <h4 class="text-base font-extrabold text-slate-900 mt-0.5">${m.crop} Clustered Load (${m.originDistrict || m.district} ➔ ${m.destination})</h4>
                </div>
                <span class="px-3 py-1 rounded-xl bg-orange-100 text-orange-800 font-extrabold text-xs font-mono">
                  ${vunothoPricing.formatUSD(m.estTransporterPayout)} Est. Payout
                </span>
              </div>

              <!-- Capacity Utilization Bar -->
              <div>
                <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
                  <span>2.5T Truck Capacity: ${m.totalWeightKg.toLocaleString()} / 2,500 kg</span>
                  <span class="font-mono text-orange-700">${m.loadUtilizationPct}% Full</span>
                </div>
                <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-orange-500 to-amber-500 rounded-full" style="width: ${m.loadUtilizationPct}%;"></div>
                </div>
              </div>

              <!-- Pickup Waypoints List -->
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-2">
                <div class="font-bold text-slate-700 flex items-center gap-1.5">
                  ${iconMapPin} Collection Waypoints (${m.stops.length} smallholder stops):
                </div>
                <ul class="space-y-1 text-slate-600 pl-4 list-disc">
                  ${m.stops.map(stop => `
                    <li><strong>${stop.farmerName}</strong> (${stop.district}): ${stop.weightKg} kg ${stop.crop}</li>
                  `).join('')}
                </ul>
              </div>

              <div class="flex justify-between items-center pt-3 border-t border-dashed border-slate-200 text-xs">
                <span class="text-slate-500 font-medium">Est. Distance: <strong>${m.estimatedDistanceKm || m.estTotalDistance} km</strong></span>
                <button class="px-4 py-2 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold transition-all shadow-glow-orange" onclick="window.transporterView.acceptManifest('${m.id}')">
                  Accept & Dispatch Route →
                </button>
              </div>
            </div>
          `).join('')}
        </div>
      </div>

      <!-- 5. TAB 2: REMITTANCE LEDGER -->
      <div id="tab-trans-ledger" style="display: ${this.activeTab === 'ledger' ? 'block' : 'none'};">
        <div class="glass-panel p-6 mb-8">
          <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <h3 class="font-extrabold text-base text-slate-900">Transport Fee Remittances</h3>
            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono">Disbursed per Delivery</span>
          </div>

          <div class="space-y-3">
            ${transactions.map(t => `
              <div class="p-4 rounded-xl bg-white border border-slate-200/90 shadow-warm-sm flex justify-between items-center">
                <div>
                  <strong class="text-slate-900 text-sm font-extrabold">${t.crop} Freight Delivery (${Number(t.quantity_kg || 0).toLocaleString()} kg)</strong>
                  <div class="text-xs text-slate-500 mt-0.5">Manifest Ref: <code class="font-mono text-slate-700">${t.receipt_reference || t.reference || t.id}</code> • Waypoint: <strong>${t.farmer_name}</strong></div>
                </div>
                <div class="text-right">
                  <div class="font-black text-orange-600 text-base font-mono">${vunothoPricing.formatUSD(t.transport_deduction || 0)}</div>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">Settled</span>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      </div>
    `;
  }

  switchTab(tabKey: 'manifests' | 'fleet' | 'ledger') {
    this.activeTab = tabKey;
    ['manifests', 'ledger'].forEach(t => {
      const btn = document.getElementById(`tab-btn-trans-${t}`);
      const content = document.getElementById(`tab-trans-${t}`);
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

  async acceptManifest(manifestId: string) {
    if ((window as any).vunothoApp) {
      (window as any).vunothoApp.showToast(`Route Manifest ${manifestId} accepted! Navigation coordinates dispatched.`, 'success');
    }
  }
}

export const transporterView = new TransporterView();
if (typeof window !== 'undefined') {
  (window as any).transporterView = transporterView;
}
