/**
 * VUNOTHO HERO LANDING PAGE VIEW (TypeScript)
 * Warm Modern Light Aesthetic with Rich Tinted Surfaces & Ambient Glassmorphism
 */

import { vunothoAPI } from '../api';
import { vunothoPricing } from '../pricing';
import { vunothoIcons } from '../icons';
import { vunothoAuth } from '../auth';

export class LandingView {
  private selectedCrop = 'Tomatoes';

  async render(container: HTMLElement) {
    const listings = (await vunothoAPI.getListings()) || [];
    const demands = (await vunothoAPI.getDemands()) || [];
    const stats = (await vunothoAPI.getImpactStats()) || {
      total_listed_kg: 320,
      total_sold_kg: 300,
      conversion_rate_pct: 93.8,
      farmer_net_earnings_usd: 148.16,
      total_diverted_kg: 120,
      youth_jobs_supported: 1
    };

    const iconSprout = vunothoIcons.get('sprout', 'text-emerald-600', 20);
    const iconBuilding = vunothoIcons.get('building', 'text-slate-700', 20);
    const iconTruck = vunothoIcons.get('truck', 'text-amber-600', 20);
    const iconShield = vunothoIcons.get('shieldCheck', 'text-emerald-600', 20);
    const iconCalculator = vunothoIcons.get('calculator', 'text-emerald-600', 20);
    const iconRecycle = vunothoIcons.get('recycle', 'text-teal-600', 20);
    const iconArrow = vunothoIcons.get('arrowRight', 'text-current', 16);

    container.innerHTML = `
      <!-- 1. HERO SECTION -->
      <section class="relative overflow-hidden rounded-3xl mb-12 p-8 md:p-14 bg-gradient-to-br from-[#0F2942] via-[#0A192F] to-[#060D17] text-white shadow-warm-xl border border-slate-700/50">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl">
          <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold tracking-wide uppercase mb-6">
            ${iconShield} Enactus International Blueprint • Farmer-to-Market OS
          </div>

          <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight leading-tight mb-6">
            Turning Smallholder Produce into <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-amber-300 to-teal-300">Protected Economic Value</span>
          </h1>

          <p class="text-base md:text-lg text-slate-300 mb-8 leading-relaxed">
            Eliminating broker exploitation in Zimbabwe with transparent farmgate Net Returns, pooled 2.5T load logistics, and guaranteed mobile money settlements on collection.
          </p>

          <div class="flex flex-wrap items-center gap-3.5">
            <button class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-glow-emerald transition-all transform hover:-translate-y-0.5 flex items-center gap-2" onclick="window.landingView.openAuthModal('farmer', 'register')">
              ${iconSprout} Join as Smallholder Farmer
            </button>
            <button class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-100 font-bold text-sm border border-slate-600 transition-all flex items-center gap-2" onclick="window.landingView.openAuthModal('buyer', 'signin')">
              ${iconBuilding} Commercial Buyer Sourcing
            </button>
            <button class="px-5 py-3 rounded-xl bg-amber-600/20 hover:bg-amber-600/30 text-amber-300 border border-amber-500/30 font-bold text-sm transition-all flex items-center gap-2" onclick="window.landingView.openAuthModal('transporter', 'signin')">
              ${iconTruck} Haulier Fleet
            </button>
          </div>
        </div>

        <!-- Animated Live Market Marquee Ticker -->
        <div class="mt-10 pt-6 border-t border-slate-700/60 ticker-wrap">
          <div class="ticker-track text-xs font-semibold text-slate-300 space-x-8">
            ${demands.map(d => `
              <span class="inline-flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">BUYER DEMAND</span>
                <strong>${d.crop}</strong>: ${Number(d.target_quantity_kg).toLocaleString()} kg @ <span class="text-emerald-400">$${Number(d.offered_price_per_kg).toFixed(2)}/kg</span> (${d.district || 'Harare'})
              </span>
            `).join('')}
            ${listings.map(l => `
              <span class="inline-flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">FARMER SUPPLY</span>
                <strong>${l.crop}</strong>: ${Number(l.quantity_kg).toLocaleString()} kg available in <strong>${l.district || 'Nyanga'}</strong>
              </span>
            `).join('')}
          </div>
        </div>
      </section>

      <!-- 2. QUICK ECOSYSTEM METRICS (Warm Tinted Cards) -->
      <section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
        <div class="glass-panel p-5 border-l-4 border-l-emerald-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Smallholder Supply</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${Number(stats.total_listed_kg || 0).toLocaleString()} kg</div>
          <div class="text-xs text-emerald-700 font-medium mt-1">Across active farming cohorts</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-amber-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Commercial Sales</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${vunothoPricing.formatUSD(stats.farmer_net_earnings_usd || 0)}</div>
          <div class="text-xs text-amber-700 font-medium mt-1">Disbursed via EcoCash</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-teal-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Waste Diverted</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">${Number(stats.total_diverted_kg || 0).toLocaleString()} kg</div>
          <div class="text-xs text-teal-700 font-medium mt-1">Via 4-Tier Circular Engine</div>
        </div>
        <div class="glass-panel p-5 border-l-4 border-l-orange-500">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Transport Pooling</div>
          <div class="text-2xl font-extrabold text-slate-900 font-mono">35% Savings</div>
          <div class="text-xs text-orange-700 font-medium mt-1">Via clustered routes</div>
        </div>
      </section>

      <!-- 3. INTERACTIVE PRICE INTELLIGENCE SIMULATOR -->
      <section class="glass-panel-elevated p-6 md:p-10 mb-12">
        <div class="max-w-2xl mb-8">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold mb-2">
            ${iconCalculator} Live Price Engine
          </div>
          <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
            Transparent Net-Return Decision Simulator
          </h2>
          <p class="text-sm text-slate-600 mt-1">
            See your exact net profit before harvesting: <strong class="text-slate-900">Gross Price − Pooled Freight − 4% Fee = Net Take-Home</strong>.
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
          <div class="lg:col-span-5 space-y-6">
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Agricultural Commodity</label>
              <select id="landing-crop-select" class="w-full px-4 py-3 rounded-xl bg-white border border-slate-300 font-semibold text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" onchange="window.landingView.updateCalculator()">
                <option value="Tomatoes" selected>Tomatoes (Avg. $0.45/kg • $8–$16/sandak)</option>
                <option value="Table Potatoes">Table Potatoes (Avg. $0.55/kg • $6–$10/pocket)</option>
                <option value="Onions">Onions (Avg. $0.60/kg • $4.50–$8/pocket)</option>
                <option value="Leafy Greens">Leafy Greens / Tsunga (Avg. $0.50/kg • $2–$4/bundle)</option>
                <option value="Butternut Squash">Butternut Squash (Avg. $0.40/kg • $3.50–$5.50/pocket)</option>
                <option value="Cabbages">Cabbages (Avg. $0.25/kg • $0.30–$0.70/head)</option>
                <option value="Green Peppers">Green Peppers (Avg. $0.70/kg • $15–$22/tin)</option>
                <option value="Carrots">Carrots (Avg. $0.45/kg • $20–$26/sack)</option>
              </select>
            </div>

            <div>
              <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                <span>Harvest Volume</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-mono" id="val-sim-weight">400 kg</span>
              </div>
              <input type="range" id="sim-weight" min="50" max="2500" step="25" value="400" class="vunotho-slider" oninput="window.landingView.updateCalculator()" />
            </div>

            <div>
              <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                <span>Distance to Wholesale Market Hub</span>
                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-mono" id="val-sim-dist">35 km</span>
              </div>
              <input type="range" id="sim-dist" min="10" max="120" step="5" value="35" class="vunotho-slider" oninput="window.landingView.updateCalculator()" />
            </div>
          </div>

          <div class="lg:col-span-7 bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 md:p-8 rounded-2xl border border-slate-700 shadow-warm-lg">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center pb-6 border-b border-slate-700">
              <div>
                <div class="text-xs text-slate-400 uppercase font-semibold mb-1">Gross Value</div>
                <div class="text-lg md:text-xl font-extrabold text-slate-100 font-mono" id="sim-gross">$180.00</div>
              </div>
              <div>
                <div class="text-xs text-amber-400 uppercase font-semibold mb-1">Pooled Freight</div>
                <div class="text-lg md:text-xl font-extrabold text-amber-400 font-mono" id="sim-transport">-$13.65</div>
              </div>
              <div>
                <div class="text-xs text-slate-400 uppercase font-semibold mb-1">Fee (4%)</div>
                <div class="text-lg md:text-xl font-extrabold text-slate-400 font-mono" id="sim-fee">-$7.20</div>
              </div>
              <div class="bg-emerald-500/20 p-2.5 rounded-xl border border-emerald-500/40">
                <div class="text-xs text-emerald-300 uppercase font-bold mb-1">Net Take-Home</div>
                <div class="text-xl md:text-2xl font-black text-emerald-400 font-mono" id="sim-net">$159.15</div>
              </div>
            </div>

            <div class="mt-6 flex items-center justify-between text-xs text-slate-300">
              <span class="inline-flex items-center gap-1 text-emerald-400 font-bold" id="sim-savings">
                ✓ $7.35 Freight Saved via 2.5T Pooling
              </span>
              <span class="text-slate-400">Guaranteed Mobile Money Transfer</span>
            </div>
          </div>
        </div>
      </section>

      <!-- 4. LIVE EXCHANGE PREVIEW (Split Feed) -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <!-- Smallholder Farmgate Lots -->
        <div class="glass-panel p-6">
          <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <div class="flex items-center gap-2">
              ${iconSprout}
              <h3 class="font-extrabold text-base text-slate-900">Active Smallholder Supply Lots</h3>
            </div>
            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">${listings.length} Lots</span>
          </div>

          <div class="space-y-3">
            ${listings.map(l => `
              <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-warm-sm flex justify-between items-center hover:border-emerald-300 transition-all">
                <div>
                  <div class="font-bold text-slate-900 text-sm">${l.crop}</div>
                  <div class="text-xs text-slate-500 mt-0.5">${l.district || 'Nyanga'} • ${l.quality || 'Grade A'}</div>
                </div>
                <div class="text-right">
                  <div class="font-extrabold text-slate-900 font-mono text-sm">${Number(l.quantity_kg).toLocaleString()} kg</div>
                  <span class="text-xs text-emerald-600 font-semibold">Ready for pickup</span>
                </div>
              </div>
            `).join('')}
          </div>
        </div>

        <!-- Verified Commercial Demands -->
        <div class="glass-panel p-6">
          <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <div class="flex items-center gap-2">
              ${iconBuilding}
              <h3 class="font-extrabold text-base text-slate-900">Commercial Off-taker Demands</h3>
            </div>
            <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">${demands.length} Demands</span>
          </div>

          <div class="space-y-3">
            ${demands.map(d => `
              <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-warm-sm flex justify-between items-center hover:border-amber-300 transition-all">
                <div>
                  <div class="font-bold text-slate-900 text-sm">${d.buyer_name}</div>
                  <div class="text-xs text-slate-500 mt-0.5">Seeking <strong>${d.crop}</strong> • ${d.delivery_hub}</div>
                </div>
                <div class="text-right">
                  <div class="font-extrabold text-amber-600 font-mono text-sm">$${Number(d.offered_price_per_kg).toFixed(2)}/kg</div>
                  <div class="text-xs text-slate-500 font-mono">${Number(d.target_quantity_kg).toLocaleString()} kg</div>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      </section>

      <!-- 5. DATABASE AUTHENTICATION MODAL -->
      <div id="auth-modal" class="vunotho-modal-backdrop">
        <div class="glass-panel-elevated max-w-md w-full p-6 md:p-8 relative">
          <button class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold" onclick="document.getElementById('auth-modal').classList.remove('active')">✕</button>

          <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto mb-3 shadow-glow-emerald">V</div>
            <h3 class="text-xl font-extrabold text-slate-900" id="auth-modal-title">Sign In to Vunotho</h3>
            <p class="text-xs text-slate-500 mt-1">Direct database authentication for verified Zimbabwean agricultural actors.</p>
          </div>

          <!-- Role Toggle Tabs -->
          <div class="grid grid-cols-3 gap-2 p-1 rounded-xl bg-slate-100 mb-6 text-xs font-bold text-slate-600">
            <button type="button" class="py-2 rounded-lg transition-all active bg-white text-emerald-700 shadow-sm" id="btn-role-farmer" onclick="window.landingView.selectRole('farmer')">Farmer</button>
            <button type="button" class="py-2 rounded-lg transition-all" id="btn-role-buyer" onclick="window.landingView.selectRole('buyer')">Buyer</button>
            <button type="button" class="py-2 rounded-lg transition-all" id="btn-role-transporter" onclick="window.landingView.selectRole('transporter')">Haulier</button>
          </div>

          <form id="auth-form" onsubmit="window.landingView.handleAuthSubmit(event)">
            <div class="space-y-4">
              <div id="group-reg-name" style="display: none;">
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name / Trading Name</label>
                <input type="text" id="auth-name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" placeholder="e.g. Sipho Moyo" />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number or Email</label>
                <input type="text" id="auth-email" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" placeholder="e.g. 0773878836" required />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                <input type="password" id="auth-password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" placeholder="••••••••" required />
              </div>

              <div id="group-reg-district" style="display: none;">
                <label class="block text-xs font-bold text-slate-700 mb-1">Farming District / Operations Hub</label>
                <select id="auth-district" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                  <option value="Nyanga" selected>Nyanga (Manicaland)</option>
                  <option value="Gwanda">Gwanda (Matabeleland South)</option>
                  <option value="Mutasa">Mutasa (Manicaland)</option>
                  <option value="Mutare">Mutare (Manicaland)</option>
                  <option value="Goromonzi">Goromonzi (Mashonaland East)</option>
                  <option value="Harare">Harare CBD Hub</option>
                  <option value="Bulawayo">Bulawayo Belmont Hub</option>
                </select>
              </div>

              <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-glow-emerald transition-all mt-2" id="auth-submit-btn">
                Sign In to Portal
              </button>
            </div>
          </form>

          <div class="mt-5 text-center text-xs text-slate-500">
            <span id="auth-toggle-caption">Don't have an account yet?</span>
            <button type="button" class="text-emerald-700 font-bold ml-1 underline" id="auth-toggle-btn" onclick="window.landingView.toggleAuthMode()">
              Register New Account
            </button>
          </div>
        </div>
      </div>
    `;

    this.updateCalculator();
  }

  private currentRole: 'farmer' | 'buyer' | 'transporter' = 'farmer';
  private isRegister = false;

  selectRole(role: 'farmer' | 'buyer' | 'transporter') {
    this.currentRole = role;
    ['farmer', 'buyer', 'transporter'].forEach(r => {
      const btn = document.getElementById(`btn-role-${r}`);
      if (btn) {
        if (r === role) {
          btn.className = 'py-2 rounded-lg transition-all bg-white text-emerald-700 shadow-sm font-bold';
        } else {
          btn.className = 'py-2 rounded-lg transition-all text-slate-600';
        }
      }
    });
  }

  openAuthModal(role: 'farmer' | 'buyer' | 'transporter' = 'farmer', mode: 'signin' | 'register' = 'signin') {
    this.selectRole(role);
    this.isRegister = (mode === 'register');
    this.renderAuthFormState();
    const modal = document.getElementById('auth-modal');
    if (modal) modal.classList.add('active');
  }

  toggleAuthMode() {
    this.isRegister = !this.isRegister;
    this.renderAuthFormState();
  }

  private renderAuthFormState() {
    const nameGroup = document.getElementById('group-reg-name');
    const districtGroup = document.getElementById('group-reg-district');
    const title = document.getElementById('auth-modal-title');
    const submitBtn = document.getElementById('auth-submit-btn');
    const toggleCaption = document.getElementById('auth-toggle-caption');
    const toggleBtn = document.getElementById('auth-toggle-btn');

    if (this.isRegister) {
      if (nameGroup) nameGroup.style.display = 'block';
      if (districtGroup) districtGroup.style.display = 'block';
      if (title) title.textContent = `Register as ${this.currentRole.toUpperCase()}`;
      if (submitBtn) submitBtn.textContent = 'Create Database Account';
      if (toggleCaption) toggleCaption.textContent = 'Already have an account?';
      if (toggleBtn) toggleBtn.textContent = 'Sign In';
    } else {
      if (nameGroup) nameGroup.style.display = 'none';
      if (districtGroup) districtGroup.style.display = 'none';
      if (title) title.textContent = 'Sign In to Vunotho';
      if (submitBtn) submitBtn.textContent = 'Sign In to Portal';
      if (toggleCaption) toggleCaption.textContent = "Don't have an account yet?";
      if (toggleBtn) toggleBtn.textContent = 'Register New Account';
    }
  }

  async handleAuthSubmit(event: Event) {
    event.preventDefault();
    const emailOrPhone = (document.getElementById('auth-email') as HTMLInputElement).value;
    const password = (document.getElementById('auth-password') as HTMLInputElement).value;
    const name = (document.getElementById('auth-name') as HTMLInputElement)?.value;
    const district = (document.getElementById('auth-district') as HTMLSelectElement)?.value;

    try {
      if (this.isRegister) {
        await vunothoAuth.register({
          name: name || 'Smallholder Farmer',
          email_or_phone: emailOrPhone,
          password,
          role: this.currentRole,
          district: district || 'Nyanga'
        });
      } else {
        await vunothoAuth.login(emailOrPhone, password, this.currentRole);
      }
      document.getElementById('auth-modal')?.classList.remove('active');
    } catch (err: any) {
      alert(`Authentication error: ${err.message}`);
    }
  }

  updateCalculator() {
    const cropSelect = document.getElementById('landing-crop-select') as HTMLSelectElement;
    const weightSlider = document.getElementById('sim-weight') as HTMLInputElement;
    const distSlider = document.getElementById('sim-dist') as HTMLInputElement;

    if (!cropSelect || !weightSlider || !distSlider) return;

    const crop = cropSelect.value;
    const weightKg = Number(weightSlider.value);
    const distKm = Number(distSlider.value);

    const weightVal = document.getElementById('val-sim-weight');
    const distVal = document.getElementById('val-sim-dist');
    if (weightVal) weightVal.textContent = `${weightKg.toLocaleString()} kg`;
    if (distVal) distVal.textContent = `${distKm} km`;

    const cropPrices: Record<string, number> = {
      'Tomatoes': 0.45,
      'Table Potatoes': 0.55,
      'Onions': 0.60,
      'Leafy Greens': 0.50,
      'Butternut Squash': 0.40,
      'Cabbages': 0.25,
      'Green Peppers': 0.70,
      'Carrots': 0.45
    };

    const unitPrice = cropPrices[crop] || 0.45;
    const breakdown = vunothoPricing.calculateNetReturn(unitPrice, weightKg, distKm, true);

    const grossEl = document.getElementById('sim-gross');
    const transportEl = document.getElementById('sim-transport');
    const feeEl = document.getElementById('sim-fee');
    const netEl = document.getElementById('sim-net');
    const savingsEl = document.getElementById('sim-savings');

    if (grossEl) grossEl.textContent = `$${breakdown.grossTotal.toFixed(2)}`;
    if (transportEl) transportEl.textContent = `-$${breakdown.transportTotal.toFixed(2)}`;
    if (feeEl) feeEl.textContent = `-$${breakdown.platformFeeTotal.toFixed(2)}`;
    if (netEl) netEl.textContent = `$${breakdown.netTotal.toFixed(2)}`;
    if (savingsEl) savingsEl.textContent = `✓ $${breakdown.transportSavings.toFixed(2)} Freight Saved via 2.5T Pooling`;
  }
}

export const landingView = new LandingView();
if (typeof window !== 'undefined') {
  (window as any).landingView = landingView;
}
