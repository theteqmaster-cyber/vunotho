/**
 * VUNOTHO FARMER PORTAL VIEW (ZERO-EMOJI ENTERPRISE DESIGN)
 * In-Portal Hero Card with Marquee Ticker, Market & Demand Exchange,
 * Harvest Logging, Real-Time Net Returns, Pooled Route Logistics & Settlement Ledger
 */

class FarmerView {
  constructor() {
    this.activeTab = 'market'; // 'market' | 'listings' | 'wallet'
  }

  async render(container) {
    this.container = container;
    const listings = (await window.vunothoAPI.getListings()) || [];
    const demands = (await window.vunothoAPI.getDemands()) || [];
    const transactions = (await window.vunothoAPI.getTransactions()) || [];
    const user = window.vunothoAuth.getUser() || { name: 'Smallholder Farmer', district: 'Nyanga', province: 'Manicaland', kycStatus: 'Verified' };

    // Get current farmer's own listings
    const myListings = listings.filter(l => l.user_id === user.id || l.farmer_name === user.name || !l.user_id);
    const geo = window.vunothoGeo.getInstantPosition();

    const iconSprout = window.vunothoIcons.get('sprout');
    const iconPlus = window.vunothoIcons.get('plus');
    const iconCalculator = window.vunothoIcons.get('calculator');
    const iconBuilding = window.vunothoIcons.get('building');
    const iconBox = window.vunothoIcons.get('box');
    const iconTruck = window.vunothoIcons.get('truck');
    const iconMapPin = window.vunothoIcons.get('mapPin');
    const iconCheck = window.vunothoIcons.get('checkCircle');
    const iconWallet = window.vunothoIcons.get('wallet');

    // Build Marquee Ticker Items
    let tickerHtml = '';
    if (demands.length > 0) {
      tickerHtml = demands.map(d => `
        <div class="portal-ticker-item">
          <span class="ticker-tag hot">TOP DEMAND</span>
          <span><strong>${d.crop}</strong>: ${Number(d.target_quantity_kg || 0).toLocaleString()} kg @ <strong style="color: #fbbf24;">$${Number(d.offered_price_per_kg || 0).toFixed(2)}/kg</strong> (${d.district || 'Harare'})</span>
        </div>
      `).join('') + demands.map(d => `
        <div class="portal-ticker-item">
          <span class="ticker-tag demand">SOURCING</span>
          <span><strong>${d.crop}</strong> (${d.quality_tier || 'Grade A'}): Delivery to ${d.district || 'Harare Hub'}</span>
        </div>
      `).join('');
    } else {
      tickerHtml = `
        <div class="portal-ticker-item">
          <span class="ticker-tag live">VUNOTHO OS</span>
          <span><strong>Vunotho for the Farmer</strong>: Guaranteed Farmgate Collection & Net Payout Ledger</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag hot">TOP SOURCING</span>
          <span><strong>Tomatoes</strong>: Harare & Bulawayo Retailers Seeking Grade A Lots ($0.45–$0.60/kg)</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag demand">POOLED FREIGHT</span>
          <span><strong>Save 35% on Transport</strong> with Consolidated 2.5T Rural Light Truck Manifests</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag live">4-TIER VALUE</span>
          <span><strong>Zero Harvest Waste</strong>: Rejects Automatically Routed to Crisps, Flour, Feed & Compost</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag live">INSTANT CASHOUT</span>
          <span><strong>EcoCash & Mobile Money</strong>: Automatic Disbursement on Weighbridge Confirmation</span>
        </div>
        <!-- Duplicate loop for seamless infinite animation -->
        <div class="portal-ticker-item">
          <span class="ticker-tag live">VUNOTHO OS</span>
          <span><strong>Vunotho for the Farmer</strong>: Guaranteed Farmgate Collection & Net Payout Ledger</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag hot">TOP SOURCING</span>
          <span><strong>Table Potatoes</strong>: Belmont & Mbare Aggregators Buying Mesh Pockets ($0.55/kg)</span>
        </div>
      `;
    }

    container.innerHTML = `
      <!-- ==========================================================================
           1. IN-PORTAL HERO CARD WITH MARQUEE TICKER (Farmer Edition)
           ========================================================================== -->
      <div class="portal-hero-card">
        <div class="portal-hero-backdrop-pattern"></div>
        <div class="portal-hero-body">
          <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.35rem;">
              <span class="system-pill" style="background: rgba(16, 185, 129, 0.2); color: #34d399; border-color: rgba(16, 185, 129, 0.4); font-size: 0.75rem;">
                ${iconSprout} Smallholder Operations Desk • ${user.district || 'Nyanga'}, ${user.province || 'Manicaland'}
              </span>
              ${user.organisation ? `<span class="badge badge-gold" style="font-size: 0.75rem;">${user.organisation}</span>` : ''}
              <span class="badge badge-verified">${user.kycStatus || 'Verified Farmer'}</span>
            </div>
            <h1 class="portal-hero-title">
              Vunotho for the Farmer — <span class="portal-hero-highlight">Protected Produce Value</span>
            </h1>
            <p class="portal-hero-desc">
              Direct farmgate collection, transparent net returns (<strong style="color:#ffffff;">Gross Price − Transport − 4% Fee</strong>), and guaranteed mobile money settlements without middleman cuts.
            </p>
          </div>

          <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <button class="btn btn-primary-green" onclick="document.getElementById('new-harvest-modal').classList.add('active')">
              ${iconPlus} Log New Harvest
            </button>
            <button class="btn btn-outline" style="color: #ffffff; border-color: rgba(255,255,255,0.3);" onclick="window.farmerView.switchTab('market')">
              ${iconCalculator} Price Intelligence
            </button>
          </div>
        </div>

        <!-- Continuous Animated Live Ticker Strip -->
        <div class="portal-ticker-strip">
          <div class="portal-ticker-label">
            <span>🔥</span> <span>MARKET TICKER</span>
          </div>
          <div class="portal-ticker-track">
            ${tickerHtml}
          </div>
        </div>
      </div>

      <!-- ==========================================================================
           2. QUICK METRICS OVERVIEW
           ========================================================================== -->
      <div class="grid-4" style="margin-bottom: 1.5rem;">
        <div class="kpi-card accent-green">
          <div class="kpi-label">My Active Listings</div>
          <div class="kpi-value">${myListings.length}</div>
          <div class="kpi-sub">Volume: ${myListings.reduce((sum, i) => sum + Number(i.quantity_kg || 0), 0).toLocaleString()} kg</div>
        </div>
        <div class="kpi-card accent-gold">
          <div class="kpi-label">Total Net Earnings</div>
          <div class="kpi-value">${window.vunothoPricing.formatUSD(transactions.reduce((sum, t) => sum + Number(t.net_payout || 0), 0))}</div>
          <div class="kpi-sub trend-up">Disbursed to wallet</div>
        </div>
        <div class="kpi-card accent-teal">
          <div class="kpi-label">Verified Buyer Demands</div>
          <div class="kpi-value">${demands.length}</div>
          <div class="kpi-sub">Open matching orders</div>
        </div>
        <div class="kpi-card accent-orange">
          <div class="kpi-label">Transport Savings</div>
          <div class="kpi-value">${window.vunothoPricing.formatUSD(myListings.length * 14.5)}</div>
          <div class="kpi-sub">Via pooled aggregation</div>
        </div>
      </div>

      <!-- ==========================================================================
           3. IN-PORTAL SUB-NAVIGATION TABS
           ========================================================================== -->
      <div class="portal-subnav-bar">
        <button type="button" class="portal-tab-btn ${this.activeTab === 'market' ? 'active' : ''}" id="tab-btn-market" onclick="window.farmerView.switchTab('market')">
          ${iconBuilding} Live Market & Demand Exchange <span class="tab-badge">${demands.length} Demands</span>
        </button>
        <button type="button" class="portal-tab-btn ${this.activeTab === 'listings' ? 'active' : ''}" id="tab-btn-listings" onclick="window.farmerView.switchTab('listings')">
          ${iconSprout} My Harvest Listings <span class="tab-badge">${myListings.length} Lots</span>
        </button>
        <button type="button" class="portal-tab-btn ${this.activeTab === 'wallet' ? 'active' : ''}" id="tab-btn-wallet" onclick="window.farmerView.switchTab('wallet')">
          ${iconWallet} Wallet & Settlement Receipts <span class="tab-badge">${transactions.length}</span>
        </button>
      </div>

      <!-- ==========================================================================
           4. TAB 1: LIVE MARKET & DEMAND EXCHANGE (Similar to Landing Market)
           ========================================================================== -->
      <div id="tab-content-market" style="display: ${this.activeTab === 'market' ? 'block' : 'none'};">
        <div class="preview-split-grid" style="margin-bottom: 2rem;">
          <!-- Left: Available Farmgate Harvest Lots -->
          <div class="preview-column">
            <div class="preview-column-header">
              <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="color: var(--green-600);">${iconSprout}</span>
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--navy-900); margin: 0;">Active Farmgate Lots (National Feed)</h3>
              </div>
              <span class="system-pill" style="font-size: 0.75rem;">${listings.length} Lots</span>
            </div>
            <div class="preview-cards-list">
              ${listings.length === 0 ? `
                <div class="empty-state" style="padding: 2rem 1rem;">
                  <div class="empty-state-icon">${iconBox}</div>
                  <p>No active harvest lots in the ledger yet.</p>
                  <button class="btn btn-sm btn-primary-green" onclick="document.getElementById('new-harvest-modal').classList.add('active')">
                    ${iconPlus} Log Your First Harvest Lot
                  </button>
                </div>
              ` : listings.map(item => `
                <div class="preview-item-card">
                  <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.35rem;">
                    <div>
                      <strong style="font-size: 0.95rem; color: var(--navy-900);">${item.crop || 'Produce'}</strong>
                      <div style="font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.3rem; margin-top: 0.15rem;">
                        ${iconMapPin} ${item.district || 'Nyanga'} • ${item.farmer_name ? item.farmer_name.split(' ')[0] + ' (Verified)' : 'Farmer'}
                      </div>
                    </div>
                    <span class="badge ${item.quality && item.quality.includes('Grade A') ? 'badge-green' : 'badge-gold'}">${item.quality || 'Standard'}</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; font-size: 0.8rem; border-top: 1px dashed var(--border-color); padding-top: 0.4rem;">
                    <span style="color: var(--navy-800); font-weight: 700;">${Number(item.quantity_kg || 0).toLocaleString()} kg</span>
                    <span class="status-indicator ${item.sync_status === 'Saved Offline' ? 'offline' : 'synced'}" style="font-size: 0.7rem;">${item.sync_status || 'Synced'}</span>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>

          <!-- Right: Verified Commercial Buyer Demands -->
          <div class="preview-column">
            <div class="preview-column-header">
              <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="color: var(--navy-800);">${iconBuilding}</span>
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--navy-900); margin: 0;">Verified Commercial Buyer Demands</h3>
              </div>
              <span class="system-pill" style="font-size: 0.75rem;">${demands.length} Demands</span>
            </div>
            <div class="preview-cards-list">
              ${demands.length === 0 ? `
                <div class="empty-state" style="padding: 2rem 1rem;">
                  <div class="empty-state-icon">${iconBuilding}</div>
                  <p>No open buyer demands right now.</p>
                  <span style="font-size: 0.75rem; color: var(--text-muted);">Commercial buyers post bulk crop demands for Harare, Bulawayo and regional markets.</span>
                </div>
              ` : demands.map(item => `
                <div class="offer-card ${item.crop === 'Tomatoes' ? 'recommended' : ''}" style="margin-bottom: 0.75rem;">
                  <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.4rem;">
                    <div>
                      <strong style="color: var(--navy-900); font-size: 1rem;">${item.crop} Sourcing Demand</strong>
                      <div style="font-size: 0.75rem; color: var(--text-muted);">Destination: <strong>${item.district || 'Harare Hub'}</strong> • Spec: <strong>${item.quality_tier || 'Grade A'}</strong></div>
                    </div>
                    <span class="badge badge-gold" style="font-size: 0.85rem;">$${Number(item.offered_price_per_kg || 0).toFixed(2)}/kg</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.65rem; border-top: 1px dashed var(--border-light); padding-top: 0.5rem;">
                    <span style="font-size: 0.8rem; color: var(--navy-800); font-weight: 700;">Target Volume: ${Number(item.target_quantity_kg || 0).toLocaleString()} kg</span>
                    <button class="btn btn-sm btn-primary-green" onclick="window.farmerView.matchBuyerDemand('${item.id}', '${item.crop}', ${item.offered_price_per_kg})">
                      Accept / Match Lot →
                    </button>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>
        </div>

        <!-- In-Portal Net Return Decision Simulator -->
        <div class="landing-calculator-section" style="margin-bottom: 2rem;">
          <div class="calc-header-wrapper">
            <div>
              <div class="badge badge-gold" style="margin-bottom: 0.4rem;">Live Price Intelligence</div>
              <h2 class="section-title">In-Portal Net-Return Decision Simulator</h2>
              <p class="section-subtitle">
                Evaluate real-time take-home profits across all 10 Zimbabwe commodities before dispatching produce.
              </p>
            </div>
          </div>

          <div class="calculator-card-grid">
            <div class="calc-control-panel">
              <div class="form-group">
                <label class="form-label" for="farmer-calc-crop">Select Commodity</label>
                <select id="farmer-calc-crop" class="form-control" onchange="window.farmerView.updatePortalCalc()">
                  <option value="Tomatoes" selected>Tomatoes (Avg. $0.45/kg • $8–$16/sandak)</option>
                  <option value="Table Potatoes">Table Potatoes (Avg. $0.55/kg • $6–$10/pocket)</option>
                  <option value="Onions">Onions (Avg. $0.60/kg • $4.50–$8/pocket)</option>
                  <option value="Leafy Greens">Leafy Greens / Tsunga (Avg. $0.50/kg • $2–$4/bundle)</option>
                  <option value="Butternut Squash">Butternut Squash (Avg. $0.40/kg • $3.50–$5.50/pocket)</option>
                  <option value="Cabbages">Cabbages (Avg. $0.25/kg • $0.30–$0.70/head)</option>
                  <option value="Green Peppers">Green Peppers (Avg. $0.70/kg • $15–$22/tin)</option>
                  <option value="Carrots">Carrots (Avg. $0.45/kg • $20–$26/sack)</option>
                  <option value="Cucumbers">Cucumbers (Avg. $0.35/kg • $8–$15/bag)</option>
                  <option value="Fresh Maize">Fresh Maize / Green Mealies (Avg. $0.25/cob)</option>
                </select>
              </div>

              <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                  <label class="form-label" style="margin-bottom: 0;">Harvest Volume</label>
                  <span class="calc-val-badge" id="farmer-calc-weight-val">400 kg</span>
                </div>
                <input type="range" id="farmer-calc-weight-slider" min="50" max="2500" step="25" value="400" class="calc-slider" oninput="window.farmerView.updatePortalCalc()" />
              </div>

              <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                  <label class="form-label" style="margin-bottom: 0;">Distance to Market Hub</label>
                  <span class="calc-val-badge" id="farmer-calc-dist-val">35 km</span>
                </div>
                <input type="range" id="farmer-calc-dist-slider" min="10" max="120" step="5" value="35" class="calc-slider" oninput="window.farmerView.updatePortalCalc()" />
              </div>
            </div>

            <div class="calc-result-panel">
              <div class="calc-equation-display">
                <div class="eq-item">
                  <span class="eq-label">Gross Value</span>
                  <span class="eq-amount gross" id="fcalc-gross-amt">$180.00</span>
                </div>
                <span class="eq-operator">−</span>
                <div class="eq-item">
                  <span class="eq-label">Pooled Transport</span>
                  <span class="eq-amount cost" id="fcalc-transport-amt">$13.65</span>
                </div>
                <span class="eq-operator">−</span>
                <div class="eq-item">
                  <span class="eq-label">Platform Fee (4%)</span>
                  <span class="eq-amount fee" id="fcalc-fee-amt">$7.20</span>
                </div>
                <span class="eq-operator">=</span>
                <div class="eq-item highlight-net">
                  <span class="eq-label">Net Take-Home</span>
                  <span class="eq-amount net" id="fcalc-net-amt">$159.15</span>
                </div>
              </div>

              <div class="calc-comparison-banner">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                  <div class="pillar-icon-box orange" style="width: 32px; height: 32px; font-size: 0.85rem;">${iconTruck}</div>
                  <div>
                    <div style="font-size: 0.85rem; font-weight: 800; color: var(--navy-900);">
                      Pooled Transport Savings: <span style="color: var(--green-600);" id="fcalc-savings-amt">$7.35 saved</span>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                      Bypasses middleman extortion fees at market wholesale gates.
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ==========================================================================
           5. TAB 2: MY HARVEST LISTINGS
           ========================================================================== -->
      <div id="tab-content-listings" style="display: ${this.activeTab === 'listings' ? 'block' : 'none'};">
        <div class="card" style="margin-bottom: 2rem;">
          <div class="card-header">
            <div class="card-header-title">My Registered Harvest Lots</div>
            <button class="btn btn-sm btn-primary-green" onclick="document.getElementById('new-harvest-modal').classList.add('active')">
              ${iconPlus} Log New Lot
            </button>
          </div>

          ${myListings.length === 0 ? `
            <div class="empty-state">
              <div class="empty-state-icon">${iconBox}</div>
              <p>No active harvest listings logged yet.</p>
              <button class="btn btn-sm btn-primary-green" style="margin-top: 0.75rem;" onclick="document.getElementById('new-harvest-modal').classList.add('active')">
                ${iconPlus} Log First Harvest Lot
              </button>
            </div>
          ` : `
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem;">
              ${myListings.map(item => `
                <div class="batch-item" style="flex-direction: column; align-items: flex-start; gap: 0.5rem;">
                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                    <strong style="color: var(--navy-900); font-size: 1.05rem;">${item.quantity_kg} kg • ${item.crop}</strong>
                    <span class="badge ${item.sync_status === 'Saved Offline' ? 'badge-orange' : 'badge-green'}">${item.sync_status || 'Synced'}</span>
                  </div>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">
                    Grade: <strong>${item.quality}</strong> • District: <strong>${item.district || user.district || 'Nyanga'}</strong>
                  </div>
                  <div style="font-size: 0.75rem; color: var(--text-secondary);">
                    GPS Farmgate: <code>${item.lat}, ${item.lng}</code>
                  </div>
                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center; border-top: 1px dashed var(--border-light); padding-top: 0.5rem; margin-top: 0.25rem;">
                    <span style="font-size: 0.75rem; color: var(--green-700); font-weight: 700;">Ready for Aggregation</span>
                    <button class="btn btn-sm btn-outline" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;" onclick="window.farmerView.switchTab('market')">
                      View Matching Buyers →
                    </button>
                  </div>
                </div>
              `).join('')}
            </div>
          `}
        </div>
      </div>

      <!-- ==========================================================================
           6. TAB 3: WALLET & SETTLEMENT RECEIPTS
           ========================================================================== -->
      <div id="tab-content-wallet" style="display: ${this.activeTab === 'wallet' ? 'block' : 'none'};">
        <div class="card" style="margin-bottom: 2rem;">
          <div class="card-header">
            <div class="card-header-title">Transparent Settlement Receipts & Disbursements</div>
            <span class="badge badge-navy">${transactions.length} Receipts</span>
          </div>

          ${transactions.length === 0 ? `
            <div class="empty-state">
              <div class="empty-state-icon">${iconWallet}</div>
              <p>No payment settlements recorded yet.</p>
              <span style="font-size: 0.75rem; color: var(--text-muted);">When a buyer accepts and a transporter confirms collection, your verifiable digital receipt will appear here.</span>
            </div>
          ` : `
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
              ${transactions.map(t => `
                <div class="batch-item" style="justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                  <div>
                    <strong style="color: var(--navy-900); font-size: 0.95rem;">${t.crop} Settlement</strong>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Receipt Ref: <code>${t.receipt_reference || t.id}</code> • ${t.created_at || 'Today'}</div>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-size: 1.1rem; font-weight: 800; color: var(--green-600);">${window.vunothoPricing.formatUSD(t.net_payout || 0)}</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">Gross: ${window.vunothoPricing.formatUSD(t.gross_total || 0)} (EcoCash Disbursed)</div>
                  </div>
                </div>
              `).join('')}
            </div>
          `}
        </div>
      </div>

      <!-- ==========================================================================
           7. MODAL: LOG NEW HARVEST
           ========================================================================== -->
      <div id="new-harvest-modal" class="modal-backdrop">
        <div class="modal-box">
          <div class="modal-header">
            <h3 class="h3-title" style="margin: 0;">Log New Smallholder Harvest</h3>
            <button class="modal-close-btn" onclick="document.getElementById('new-harvest-modal').classList.remove('active')">✕</button>
          </div>
          <div class="modal-body">
            <form id="new-harvest-form" onsubmit="window.farmerView.handleCreateHarvest(event)">
              <div class="form-group">
                <label class="form-label" for="harvest-crop">Select Commodity</label>
                <select id="harvest-crop" class="form-control" required>
                  <option value="Tomatoes" ${user.main_produce === 'Tomatoes' ? 'selected' : ''}>Tomatoes (Round / Roma Sandak)</option>
                  <option value="Table Potatoes" ${user.main_produce === 'Table Potatoes' ? 'selected' : ''}>Table Potatoes (15kg Mesh Pocket)</option>
                  <option value="Onions" ${user.main_produce === 'Onions' ? 'selected' : ''}>Onions (10kg Pocket)</option>
                  <option value="Leafy Greens" ${user.main_produce === 'Leafy Greens' ? 'selected' : ''}>Leafy Greens (Tsunga / Covo / Rape)</option>
                  <option value="Butternut Squash" ${user.main_produce === 'Butternut Squash' ? 'selected' : ''}>Butternut Squash (10kg Pocket)</option>
                  <option value="Cabbages" ${user.main_produce === 'Cabbages' ? 'selected' : ''}>Cabbages (Drumhead Head / Bulk)</option>
                  <option value="Green Peppers" ${user.main_produce === 'Green Peppers' ? 'selected' : ''}>Green Peppers (20L Tin / Sacks)</option>
                  <option value="Carrots" ${user.main_produce === 'Carrots' ? 'selected' : ''}>Carrots (50kg Sack)</option>
                  <option value="Cucumbers" ${user.main_produce === 'Cucumbers' ? 'selected' : ''}>Cucumbers (60kg Bag)</option>
                  <option value="Fresh Maize" ${user.main_produce === 'Fresh Maize' ? 'selected' : ''}>Fresh Maize (Green Mealies Dozens)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="harvest-qty">Harvest Volume (Kilograms)</label>
                <input type="number" id="harvest-qty" class="form-control" placeholder="e.g. 300" min="10" step="5" required />
              </div>

              <div class="form-group">
                <label class="form-label" for="harvest-quality">Quality Grading Spec</label>
                <select id="harvest-quality" class="form-control" required>
                  <option value="Grade A (Supermarket Spec)" selected>Tier 1: Grade A (Supermarket & Fresh Wholesale Spec)</option>
                  <option value="Grade B (Small/Blemished - Processing)">Tier 2: Grade B (Agro-Processing - Crisps, Flour, Starch)</option>
                  <option value="Grade C (Animal Feed / Livestock)">Tier 3: Grade C (Livestock Feed / Pig & Cattle Rations)</option>
                  <option value="Bio-Compost Biomass">Tier 4: Bio-Compost Organic Biomass</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Farmgate Collection Coordinates (GPS)</label>
                <div style="display: flex; gap: 0.5rem;">
                  <input type="text" id="harvest-lat" class="form-control" value="${geo.lat}" readonly />
                  <input type="text" id="harvest-lng" class="form-control" value="${geo.lng}" readonly />
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Captured for pooled transporter route waypoint mapping.</div>
              </div>

              <div class="modal-footer" style="margin: 1.5rem -1.5rem -1.5rem; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('new-harvest-modal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary-green" id="btn-save-harvest">Register Harvest Lot</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;

    // Initialize portal calculator
    this.updatePortalCalc();
  }

  switchTab(tabKey) {
    this.activeTab = tabKey;
    document.querySelectorAll('.portal-tab-btn').forEach(btn => btn.classList.remove('active'));
    const targetBtn = document.getElementById(`tab-btn-${tabKey}`);
    if (targetBtn) targetBtn.classList.add('active');

    const marketTab = document.getElementById('tab-content-market');
    const listingsTab = document.getElementById('tab-content-listings');
    const walletTab = document.getElementById('tab-content-wallet');

    if (marketTab) marketTab.style.display = tabKey === 'market' ? 'block' : 'none';
    if (listingsTab) listingsTab.style.display = tabKey === 'listings' ? 'block' : 'none';
    if (walletTab) walletTab.style.display = tabKey === 'wallet' ? 'block' : 'none';
  }

  updatePortalCalc() {
    const cropSelect = document.getElementById('farmer-calc-crop');
    const weightSlider = document.getElementById('farmer-calc-weight-slider');
    const distSlider = document.getElementById('farmer-calc-dist-slider');

    if (!cropSelect || !weightSlider || !distSlider) return;

    const crop = cropSelect.value;
    const weightKg = Number(weightSlider.value);
    const distKm = Number(distSlider.value);

    const weightVal = document.getElementById('farmer-calc-weight-val');
    const distVal = document.getElementById('farmer-calc-dist-val');
    if (weightVal) weightVal.textContent = `${weightKg.toLocaleString()} kg`;
    if (distVal) distVal.textContent = `${distKm} km`;

    const cropPrices = {
      'Tomatoes': 0.45,
      'Table Potatoes': 0.55,
      'Onions': 0.60,
      'Leafy Greens': 0.50,
      'Butternut Squash': 0.40,
      'Cabbages': 0.25,
      'Green Peppers': 0.70,
      'Carrots': 0.45,
      'Cucumbers': 0.35,
      'Fresh Maize': 0.25
    };

    const unitPrice = cropPrices[crop] || 0.45;
    const breakdown = window.vunothoPricing ? window.vunothoPricing.calculateNetReturn(unitPrice, weightKg, distKm, true) : {
      grossTotal: Number((unitPrice * weightKg).toFixed(2)),
      transportTotal: Number((weightKg * distKm * 0.0015 * 0.65).toFixed(2)),
      platformFeeTotal: Number((unitPrice * weightKg * 0.04).toFixed(2)),
      netTotal: Number((unitPrice * weightKg * 0.96 - weightKg * distKm * 0.0015 * 0.65).toFixed(2)),
      transportSavings: Number((weightKg * distKm * 0.0015 * 0.35).toFixed(2))
    };

    const grossAmt = document.getElementById('fcalc-gross-amt');
    const transportAmt = document.getElementById('fcalc-transport-amt');
    const feeAmt = document.getElementById('fcalc-fee-amt');
    const netAmt = document.getElementById('fcalc-net-amt');
    const savingsAmt = document.getElementById('fcalc-savings-amt');

    if (grossAmt) grossAmt.textContent = `$${breakdown.grossTotal.toFixed(2)}`;
    if (transportAmt) transportAmt.textContent = `$${breakdown.transportTotal.toFixed(2)}`;
    if (feeAmt) feeAmt.textContent = `$${breakdown.platformFeeTotal.toFixed(2)}`;
    if (netAmt) netAmt.textContent = `$${breakdown.netTotal.toFixed(2)}`;
    if (savingsAmt) savingsAmt.textContent = `$${breakdown.transportSavings.toFixed(2)} saved`;
  }

  async handleCreateHarvest(event) {
    event.preventDefault();
    const crop = document.getElementById('harvest-crop').value;
    const quantity_kg = Number(document.getElementById('harvest-qty').value);
    const quality = document.getElementById('harvest-quality').value;
    const lat = Number(document.getElementById('harvest-lat').value);
    const lng = Number(document.getElementById('harvest-lng').value);

    const user = window.vunothoAuth.getUser();

    const payload = {
      crop,
      quantity_kg,
      quality,
      lat,
      lng,
      farmer_name: user ? user.name : 'Smallholder Farmer',
      user_id: user ? user.id : null,
      district: user ? user.district : 'Nyanga',
      province: user ? user.province : 'Manicaland'
    };

    try {
      await window.vunothoAPI.createListing(payload);
      window.vunothoApp.showToast(`Harvest of ${quantity_kg}kg ${crop} registered successfully!`, 'success');
      document.getElementById('new-harvest-modal').classList.remove('active');
      this.render(this.container);
    } catch (err) {
      window.vunothoApp.showToast(`Error saving harvest: ${err.message}`, 'error');
    }
  }

  async matchBuyerDemand(demandId, crop, offeredPrice) {
    const user = window.vunothoAuth.getUser();
    window.vunothoApp.showToast(`Matching your ${crop} harvest to buyer demand at $${Number(offeredPrice).toFixed(2)}/kg...`, 'info');
    
    try {
      const ref = 'ECO-' + Math.floor(100000 + Math.random() * 900000);
      const gross = Number((250 * offeredPrice).toFixed(2));
      const transport = Number((250 * 35 * 0.0015 * 0.65).toFixed(2));
      const fee = Number((gross * 0.04).toFixed(2));
      const net = Number((gross - transport - fee).toFixed(2));

      await window.vunothoAPI.createTransaction({
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

      window.vunothoApp.showToast(`Order matched! EcoCash settlement receipt generated.`, 'success');
      this.switchTab('wallet');
      this.render(this.container);
    } catch (e) {
      window.vunothoApp.showToast(`Order match logged to local ledger: ${e.message}`, 'info');
    }
  }
}

window.farmerView = new FarmerView();
