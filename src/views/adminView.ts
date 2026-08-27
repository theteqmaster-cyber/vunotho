/**
 * VUNOTHO EXECUTIVE ADMIN COMMAND CENTER VIEW (TypeScript)
 * Warm Modern Light Aesthetic with Rich Tinted Surfaces & Ambient Glassmorphism
 */

import { vunothoAPI } from '../api';
import { vunothoPricing } from '../pricing';
import { vunothoIcons } from '../icons';
import { UserProfile, SystemConfig, ValueRecoveryLog } from '../types';

export class AdminView {
  private container: HTMLElement | null = null;

  async render(container: HTMLElement) {
    this.container = container;
    const stats = (await vunothoAPI.getImpactStats()) || {
      total_listed_kg: 320,
      total_sold_kg: 300,
      conversion_rate_pct: 93.8,
      total_diverted_kg: 120,
      recovered_value_usd: 66,
      farmer_net_earnings_usd: 148.16,
      average_income_lift_pct: 32.5,
      youth_jobs_supported: 1,
      platform_surplus_usd: 6.60
    };

    const users: UserProfile[] = (await vunothoAPI.getUsers()) || [];
    const configs: SystemConfig | null = await vunothoAPI.getConfigs();
    const vrLogs: ValueRecoveryLog[] = (await vunothoAPI.getValueRecoveryLogs()) || [];

    const iconShield = vunothoIcons.get('shieldCheck', 'text-emerald-600', 18);
    const iconRecycle = vunothoIcons.get('recycle', 'text-teal-600', 18);
    const iconUsers = vunothoIcons.get('user', 'text-slate-600', 18);

    container.innerHTML = `
      <!-- 1. EXECUTIVE HEADER CARD -->
      <div class="glass-panel p-6 md:p-8 mb-8 border border-slate-200/90 shadow-warm-md relative overflow-hidden bg-gradient-to-r from-white via-[#FAF8F5] to-[#F1F5F9]">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
          <div>
            <div class="flex items-center gap-2 flex-wrap mb-2">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-bold font-mono">
                ${iconShield} Executive Command Center • Super Admin
              </span>
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono">Enactus Zimbabwe Live</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
              National Governance & Impact Dashboard
            </h1>
            <p class="text-xs md:text-sm text-slate-600 mt-1 max-w-2xl">
              Real-time oversight over smallholder financial lift, 4-tier circular post-harvest recovery, KYC approvals, and economic platform parameters.
            </p>
          </div>
        </div>
      </div>

      <!-- 2. ENACTUS IMPACT SCORECARD (4 Key KPIs) -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="glass-panel p-5 border-l-4 border-l-emerald-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Smallholder Net Income</div>
          <div class="text-2xl font-black text-slate-900 font-mono">${vunothoPricing.formatUSD(stats.farmer_net_earnings_usd || 0)}</div>
          <div class="text-xs text-emerald-700 font-bold mt-1">▲ +${stats.average_income_lift_pct || 32.5}% vs Broker Benchmark</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-teal-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Waste Diverted (SDG 12.3)</div>
          <div class="text-2xl font-black text-slate-900 font-mono">${Number(stats.total_diverted_kg || 0).toLocaleString()} kg</div>
          <div class="text-xs text-teal-700 font-bold mt-1">Value Recovered: ${vunothoPricing.formatUSD(stats.recovered_value_usd || 0)}</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-orange-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Freight Pool Efficiency</div>
          <div class="text-2xl font-black text-slate-900 font-mono">${stats.logistics_savings_pct || 35}%</div>
          <div class="text-xs text-orange-700 font-bold mt-1">Logistics cost reduction</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-amber-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Youth Jobs & Surplus</div>
          <div class="text-2xl font-black text-slate-900 font-mono">${stats.youth_jobs_supported || 1} Agents</div>
          <div class="text-xs text-amber-700 font-bold mt-1">Platform Surplus: ${vunothoPricing.formatUSD(stats.platform_surplus_usd || 6.60)}</div>
        </div>
      </div>

      <!-- 3. USER KYC MANAGEMENT TABLE -->
      <div class="glass-panel p-6 mb-8">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
          <div>
            <h3 class="font-extrabold text-base text-slate-900">User Identity & KYC Approvals</h3>
            <p class="text-xs text-slate-500 mt-0.5">Manage smallholders, commercial off-takers, and hauliers registered in the central database.</p>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-800 text-xs font-bold">${users.length} Users</span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-100/80 text-slate-700 font-bold uppercase tracking-wider">
              <tr>
                <th class="p-3.5 rounded-l-xl">Name / Organisation</th>
                <th class="p-3.5">Contact</th>
                <th class="p-3.5">Role</th>
                <th class="p-3.5">District</th>
                <th class="p-3.5">KYC Status</th>
                <th class="p-3.5 rounded-r-xl text-right">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              ${users.map(u => `
                <tr class="hover:bg-slate-50 transition-all">
                  <td class="p-3.5 font-bold text-slate-900">${u.name} ${u.organisation ? `<span class="text-slate-400 font-normal">(${u.organisation})</span>` : ''}</td>
                  <td class="p-3.5 font-mono text-slate-600">${u.email_or_phone}</td>
                  <td class="p-3.5"><span class="px-2 py-0.5 rounded uppercase font-bold text-[10px] ${u.role === 'farmer' ? 'bg-emerald-100 text-emerald-800' : (u.role === 'buyer' ? 'bg-amber-100 text-amber-800' : 'bg-orange-100 text-orange-800')}">${u.role}</span></td>
                  <td class="p-3.5 text-slate-600">${u.district || 'Nyanga'}</td>
                  <td class="p-3.5">
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold ${u.kyc_status === 'Approved' || u.kycStatus === 'Super Admin' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}">
                      ${u.kyc_status || u.kycStatus || 'Pending KYC'}
                    </span>
                  </td>
                  <td class="p-3.5 text-right">
                    <button class="px-2.5 py-1 rounded bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs" onclick="window.adminView.toggleKYC('${u.id}', '${u.kyc_status === 'Approved' ? 'Pending KYC' : 'Approved'}')">
                      ${u.kyc_status === 'Approved' ? 'Revoke' : 'Approve KYC'}
                    </button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>

      <!-- 4. 4-TIER CIRCULAR VALUE RECOVERY & PARAMETERS (2-Col Grid) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Circular Value Recovery Ledger -->
        <div class="lg:col-span-7 glass-panel p-6">
          <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <div class="flex items-center gap-2">
              ${iconRecycle}
              <h3 class="font-extrabold text-base text-slate-900">4-Tier Circular Value Recovery Ledger</h3>
            </div>
            <span class="px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800 text-xs font-bold font-mono">${vrLogs.length} Streams</span>
          </div>

          <div class="space-y-3">
            ${vrLogs.map(l => `
              <div class="p-4 rounded-xl bg-white border border-slate-200/90 shadow-warm-sm flex justify-between items-center">
                <div>
                  <strong class="text-slate-900 text-sm font-extrabold">${l.crop} (${Number(l.kg_diverted || 0).toLocaleString()} kg)</strong>
                  <div class="text-xs text-slate-500 mt-0.5">Pathway: <strong class="text-teal-700">${l.pathway}</strong> • Facility: <strong>${l.facility}</strong></div>
                </div>
                <div class="text-right">
                  <div class="font-black text-teal-600 text-base font-mono">${vunothoPricing.formatUSD(l.recovered_value_usd || 0)}</div>
                  <span class="text-xs font-semibold text-slate-400 font-mono">${new Date(l.timestamp).toLocaleDateString()}</span>
                </div>
              </div>
            `).join('')}
          </div>
        </div>

        <!-- Global Economic Parameters Form -->
        <div class="lg:col-span-5 glass-panel p-6">
          <h3 class="font-extrabold text-base text-slate-900 mb-2">Platform Economic Sliders</h3>
          <p class="text-xs text-slate-500 mb-6">Real-time parameters governing the national marketplace matching formula.</p>

          <form id="configs-form" onsubmit="window.adminView.saveConfigs(event)">
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Marketplace Coordination Fee (%)</label>
                <input type="number" id="cfg-fee" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" value="${configs?.platform_fee_pct || '4.0'}" step="0.1" required />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Base Logistics Rate ($ / kg / km)</label>
                <input type="number" id="cfg-trans-km" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" value="${configs?.transport_per_km || '0.0015'}" step="0.0001" required />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Enactus National Target ($ USD)</label>
                <input type="number" id="cfg-target" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" value="${configs?.enactus_target_usd || '50000'}" step="1000" required />
              </div>

              <button type="submit" class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-warm-md transition-all mt-4">
                Save Platform Economic Parameters
              </button>
            </div>
          </form>
        </div>
      </div>
    `;
  }

  async toggleKYC(userId: string, status: string) {
    try {
      await vunothoAPI.updateUserKYC(userId, status);
      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast(`User KYC updated to ${status}!`, 'success');
      }
      if (this.container) this.render(this.container);
    } catch (e: any) {
      alert(`Error updating KYC: ${e.message}`);
    }
  }

  async saveConfigs(event: Event) {
    event.preventDefault();
    const platform_fee_pct = (document.getElementById('cfg-fee') as HTMLInputElement).value;
    const transport_per_km = (document.getElementById('cfg-trans-km') as HTMLInputElement).value;
    const enactus_target_usd = (document.getElementById('cfg-target') as HTMLInputElement).value;

    try {
      await vunothoAPI.saveConfigs({
        platform_fee_pct,
        transport_per_km,
        enactus_target_usd
      });
      if ((window as any).vunothoApp) {
        (window as any).vunothoApp.showToast('Platform parameters updated successfully!', 'success');
      }
      if (this.container) this.render(this.container);
    } catch (e: any) {
      alert(`Error saving parameters: ${e.message}`);
    }
  }
}

export const adminView = new AdminView();
if (typeof window !== 'undefined') {
  (window as any).adminView = adminView;
}
