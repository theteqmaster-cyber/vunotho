/**
 * VUNOTHO BUYER PORTAL VIEW (ZERO-EMOJI ENTERPRISE DESIGN)
 * In-Portal Hero Card with Marquee Ticker, Supply Exchange,
 * Bulk Demand Creation, Multi-Farmer Order Matching & Fulfillment Tracking
 */

class BuyerView {
  constructor() {
    this.activeTab = 'supply'; // 'supply' | 'demands' | 'orders'
  }

  async render(container) {
    this.container = container;
    const demands = (await window.vunothoAPI.getDemands()) || [];
    const listings = (await window.vunothoAPI.getListings()) || [];
    const transactions = (await window.vunothoAPI.getTransactions()) || [];
    const user = window.vunothoAuth.getUser() || { name: 'Commercial Off-taker', district: 'Harare', province: 'Harare' };

    const myDemands = demands.filter(d => d.user_id === user.id || d.buyer_name === user.name || !d.user_id);

    const iconBuilding = window.vunothoIcons.get('building');
    const iconPlus = window.vunothoIcons.get('plus');
    const iconBox = window.vunothoIcons.get('box');
    const iconTruck = window.vunothoIcons.get('truck');
    const iconMapPin = window.vunothoIcons.get('mapPin');
    const iconCheck = window.vunothoIcons.get('checkCircle');
    const iconWallet = window.vunothoIcons.get('wallet');
    const iconSprout = window.vunothoIcons.get('sprout');

    // Build Buyer Marquee Ticker
    let tickerHtml = '';
    if (listings.length > 0) {
      tickerHtml = listings.map(l => `
        <div class="portal-ticker-item">
          <span class="ticker-tag live">AVAILABLE LOT</span>
          <span><strong>${l.crop}</strong>: ${Number(l.quantity_kg || 0).toLocaleString()} kg ready in <strong>${l.district || 'Nyanga'}</strong> (${l.quality || 'Grade A'})</span>
        </div>
      `).join('') + listings.map(l => `
        <div class="portal-ticker-item">
          <span class="ticker-tag hot">FARMER SUPPLY</span>
          <span>${l.farmer_name || 'Smallholder'}: ${l.crop} Ready for Direct Cluster Aggregation</span>
        </div>
      `).join('');
    } else {
      tickerHtml = `
        <div class="portal-ticker-item">
          <span class="ticker-tag live">PROCUREMENT DESK</span>
          <span><strong>Vunotho for the Buyer</strong>: Verified Farmgate Quality & Pooled Direct Logistics</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag hot">LIVE SUPPLY</span>
          <span><strong>Tomatoes, Potatoes, Onions</strong>: Smallholders Ready for Wholesale Off-take</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag demand">CRATE TRANSIT</span>
          <span><strong>Rigid Plastic Crates</strong>: Eliminate 40% Transit Rejection Losses</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag live">PROCUREMENT DESK</span>
          <span><strong>Vunotho for the Buyer</strong>: Verified Farmgate Quality & Pooled Direct Logistics</span>
        </div>
      `;
    }

    container.innerHTML = `
      <!-- ==========================================================================
           1. IN-PORTAL HERO CARD WITH MARQUEE TICKER (Buyer Edition)
           ========================================================================== -->
      <div class="portal-hero-card">
        <div class="portal-hero-backdrop-pattern"></div>
        <div class="portal-hero-body">
          <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.35rem;">
              <span class="system-pill" style="background: rgba(14, 165, 233, 0.2); color: #38bdf8; border-color: rgba(14, 165, 233, 0.4); font-size: 0.75rem;">
                ${iconBuilding} Commercial Procurement Desk • ${user.district || 'Harare CBD'}, ${user.province || 'Harare'}
              </span>
              ${user.organisation ? `<span class="badge badge-gold" style="font-size: 0.75rem;">${user.organisation}</span>` : ''}
              <span class="badge badge-verified">${user.kycStatus || 'Verified Buyer'}</span>
            </div>
            <h1 class="portal-hero-title">
              Vunotho for Commercial Buyers — <span class="portal-hero-highlight">Verified Direct Sourcing</span>
            </h1>
            <p class="portal-hero-desc">
              Source verified smallholder produce directly at transparent wholesale pricing, with aggregated 2.5T truck fulfillment and guaranteed quality specs.
            </p>
          </div>

          <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <button class="btn btn-primary-navy" onclick="document.getElementById('new-demand-modal').classList.add('active')">
              ${iconPlus} Post Sourcing Demand
            </button>
            <button class="btn btn-outline" style="color: #ffffff; border-color: rgba(255,255,255,0.3);" onclick="window.buyerView.switchTab('supply')">
              ${iconSprout} Browse Farmgate Lots
            </button>
          </div>
        </div>

        <!-- Continuous Animated Live Ticker Strip -->
        <div class="portal-ticker-strip">
          <div class="portal-ticker-label">
            <span>🚜</span> <span>SUPPLY TICKER</span>
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
        <div class="kpi-card accent-teal">
          <div class="kpi-label">Active Demands</div>
          <div class="kpi-value">${myDemands.length}</div>
          <div class="kpi-sub">Target Volume: ${myDemands.reduce((sum, d) => sum + Number(d.target_quantity_kg || 0), 0).toLocaleString()} kg</div>
        </div>
        <div class="kpi-card accent-green">
          <div class="kpi-label">Available Farmer Supply</div>
          <div class="kpi-value">${listings.reduce((sum, l) => sum + Number(l.quantity_kg || 0), 0).toLocaleString()} kg</div>
          <div class="kpi-sub">Across ${listings.length} smallholder lots</div>
        </div>
        <div class="kpi-card accent-gold">
          <div class="kpi-label">Purchases Settled</div>
          <div class="kpi-value">${window.vunothoPricing.formatUSD(transactions.reduce((sum, t) => sum + Number(t.gross_total || 0), 0))}</div>
          <div class="kpi-sub">Total Gross Order Volume</div>
        </div>
        <div class="kpi-card accent-orange">
          <div class="kpi-label">Fulfillment Reliability</div>
          <div class="kpi-value">98.4%</div>
          <div class="kpi-sub">Verified farmgate collection</div>
        </div>
      </div>

      <!-- ==========================================================================
           3. IN-PORTAL SUB-NAVIGATION TABS
           ========================================================================== -->
      <div class="portal-subnav-bar">
        <button type="button" class="portal-tab-btn ${this.activeTab === 'supply' ? 'active' : ''}" id="tab-btn-buyer-supply" onclick="window.buyerView.switchTab('supply')">
          ${iconSprout} Live Farmgate Supply Exchange <span class="tab-badge">${listings.length} Lots</span>
        </button>
        <button type="button" class="portal-tab-btn ${this.activeTab === 'demands' ? 'active' : ''}" id="tab-btn-buyer-demands" onclick="window.buyerView.switchTab('demands')">
          ${iconBuilding} My Procurement Demands <span class="tab-badge">${myDemands.length} Orders</span>
        </button>
        <button type="button" class="portal-tab-btn ${this.activeTab === 'orders' ? 'active' : ''}" id="tab-btn-buyer-orders" onclick="window.buyerView.switchTab('orders')">
          ${iconTruck} Fulfillment & Transporter Tracking <span class="tab-badge">${transactions.length}</span>
        </button>
      </div>

      <!-- ==========================================================================
           4. TAB 1: LIVE FARMGATE SUPPLY EXCHANGE
           ========================================================================== -->
      <div id="tab-buyer-supply" style="display: ${this.activeTab === 'supply' ? 'block' : 'none'};">
        <div class="card" style="margin-bottom: 2rem;">
          <div class="card-header">
            <div>
              <div class="card-header-title">Available Smallholder Farmgate Lots</div>
              <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem;">Direct aggregation lots registered by verified smallholder farmers across Zimbabwe.</div>
            </div>
            <span class="badge badge-green">${listings.length} Lots Active</span>
          </div>

          ${listings.length === 0 ? `
            <div class="empty-state">
              <div class="empty-state-icon">${iconBox}</div>
              <p>No active harvest lots in the ledger right now.</p>
            </div>
          ` : `
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem;">
              ${listings.map(item => `
                <div class="batch-item" style="flex-direction: column; align-items: flex-start; gap: 0.5rem;">
                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                    <strong style="color: var(--navy-900); font-size: 1.05rem;">${item.crop}</strong>
                    <span class="badge ${item.quality && item.quality.includes('Grade A') ? 'badge-green' : 'badge-gold'}">${item.quality || 'Grade A'}</span>
                  </div>
                  <div style="font-size: 0.85rem; color: var(--navy-800); font-weight: 700;">
                    ${Number(item.quantity_kg || 0).toLocaleString()} kg available
                  </div>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">
                    Origin: <strong>${item.district || 'Nyanga'}, ${item.province || 'Manicaland'}</strong> • Farmer: <strong>${item.farmer_name ? item.farmer_name.split(' ')[0] : 'Farmer'}</strong>
                  </div>
                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center; border-top: 1px dashed var(--border-light); padding-top: 0.5rem; margin-top: 0.25rem;">
                    <span style="font-size: 0.75rem; color: var(--text-muted);">GPS: <code>${item.lat}, ${item.lng}</code></span>
                    <button class="btn btn-sm btn-primary-navy" onclick="window.buyerView.orderHarvestLot('${item.id}', '${item.crop}', ${item.quantity_kg})">
                      Order Lot →
                    </button>
                  </div>
                </div>
              `).join('')}
            </div>
          `}
        </div>
      </div>

      <!-- ==========================================================================
           5. TAB 2: MY PROCUREMENT DEMANDS
           ========================================================================== -->
      <div id="tab-buyer-demands" style="display: ${this.activeTab === 'demands' ? 'block' : 'none'};">
        <div class="card" style="margin-bottom: 2rem;">
          <div class="card-header">
            <div class="card-header-title">My Posted Procurement Demands</div>
            <button class="btn btn-sm btn-primary-navy" onclick="document.getElementById('new-demand-modal').classList.add('active')">
              ${iconPlus} Post New Demand
            </button>
          </div>

          ${myDemands.length === 0 ? `
            <div class="empty-state">
              <div class="empty-state-icon">${iconBuilding}</div>
              <p>No procurement demands posted yet.</p>
              <button class="btn btn-sm btn-primary-navy" style="margin-top: 0.75rem;" onclick="document.getElementById('new-demand-modal').classList.add('active')">
                ${iconPlus} Post First Demand Order
              </button>
            </div>
          ` : `
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem;">
              ${myDemands.map(d => `
                <div class="batch-item" style="flex-direction: column; align-items: flex-start; gap: 0.5rem;">
                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                    <strong style="color: var(--navy-900); font-size: 1.05rem;">${d.crop} Demand</strong>
                    <span class="badge badge-gold">$${Number(d.offered_price_per_kg || 0).toFixed(2)}/kg</span>
                  </div>
                  <div style="font-size: 0.85rem; color: var(--navy-800); font-weight: 700;">
                    Target Volume: ${Number(d.target_quantity_kg || 0).toLocaleString()} kg
                  </div>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">
                    Destination Hub: <strong>${d.district || user.district || 'Harare CBD'}</strong> • Quality: <strong>${d.quality_tier || 'Grade A'}</strong>
                  </div>
                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center; border-top: 1px dashed var(--border-light); padding-top: 0.5rem; margin-top: 0.25rem;">
                    <span class="status-indicator synced" style="font-size: 0.7rem;">Matching Farmers</span>
                    <button class="btn btn-sm btn-outline" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;" onclick="window.buyerView.switchTab('supply')">
                      View Matching Crops →
                    </button>
                  </div>
                </div>
              `).join('')}
            </div>
          `}
        </div>
      </div>

      <!-- ==========================================================================
           6. TAB 3: FULFILLMENT & ORDERS
           ========================================================================== -->
      <div id="tab-buyer-orders" style="display: ${this.activeTab === 'orders' ? 'block' : 'none'};">
        <div class="card" style="margin-bottom: 2rem;">
          <div class="card-header">
            <div class="card-header-title">Fulfilled Purchases & Transporter Aggregation Tracking</div>
            <span class="badge badge-teal">${transactions.length} Orders</span>
          </div>

          ${transactions.length === 0 ? `
            <div class="empty-state">
              <div class="empty-state-icon">${iconTruck}</div>
              <p>No fulfilled purchase orders yet.</p>
              <span style="font-size: 0.75rem; color: var(--text-muted);">Orders matched with farmers will appear here with live transporter route tracking.</span>
            </div>
          ` : `
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
              ${transactions.map(t => `
                <div class="batch-item" style="justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                  <div>
                    <strong style="color: var(--navy-900); font-size: 0.95rem;">${t.crop} Procurement</strong>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Order Ref: <code>${t.receipt_reference || t.id}</code> • Farmer: <strong>${t.farmer_name || 'Smallholder'}</strong></div>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-size: 1.05rem; font-weight: 800; color: var(--navy-900);">${window.vunothoPricing.formatUSD(t.gross_total || 0)}</div>
                    <span class="badge badge-green" style="font-size: 0.7rem;">Fulfilled & Dispatched</span>
                  </div>
                </div>
              `).join('')}
            </div>
          `}
        </div>
      </div>

      <!-- ==========================================================================
           7. MODAL: POST PURCHASE DEMAND
           ========================================================================== -->
      <div id="new-demand-modal" class="modal-backdrop">
        <div class="modal-box">
          <div class="modal-header">
            <h3 class="h3-title" style="margin: 0;">Post Commercial Purchase Demand</h3>
            <button class="modal-close-btn" onclick="document.getElementById('new-demand-modal').classList.remove('active')">✕</button>
          </div>
          <div class="modal-body">
            <form id="new-demand-form" onsubmit="window.buyerView.handleCreateDemand(event)">
              <div class="form-group">
                <label class="form-label" for="demand-crop">Required Commodity</label>
                <select id="demand-crop" class="form-control" required>
                  <option value="Tomatoes" selected>Tomatoes (Round / Roma Sandak)</option>
                  <option value="Table Potatoes">Table Potatoes (15kg Mesh Pocket)</option>
                  <option value="Onions">Onions (10kg Pocket)</option>
                  <option value="Leafy Greens">Leafy Greens (Tsunga / Covo / Rape)</option>
                  <option value="Butternut Squash">Butternut Squash (10kg Pocket)</option>
                  <option value="Cabbages">Cabbages (Drumhead Bulk)</option>
                  <option value="Green Peppers">Green Peppers (20L Tin / Sacks)</option>
                  <option value="Carrots">Carrots (50kg Sack)</option>
                  <option value="Cucumbers">Cucumbers (60kg Bag)</option>
                  <option value="Fresh Maize">Fresh Maize (Green Mealies)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="demand-qty">Target Volume (Kilograms)</label>
                <input type="number" id="demand-qty" class="form-control" placeholder="e.g. 1000" min="50" step="50" required />
              </div>

              <div class="form-group">
                <label class="form-label" for="demand-price">Offered Farmgate Price ($/kg)</label>
                <input type="number" id="demand-price" class="form-control" placeholder="e.g. 0.55" min="0.10" max="2.50" step="0.01" required />
              </div>

              <div class="form-group">
                <label class="form-label" for="demand-quality">Quality Grading Standard</label>
                <select id="demand-quality" class="form-control" required>
                  <option value="Grade A (Supermarket Spec)" selected>Grade A: Supermarket & Retail Grade</option>
                  <option value="Grade B (Agro-Processing)">Grade B: Agro-Processing (Crisps / Starch / Puree)</option>
                  <option value="Commercial Mixed">Commercial Mixed Standard</option>
                </select>
              </div>

              <div class="modal-footer" style="margin: 1.5rem -1.5rem -1.5rem; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('new-demand-modal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary-navy" id="btn-save-demand">Publish Demand Order</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;
  }

  switchTab(tabKey) {
    this.activeTab = tabKey;
    document.querySelectorAll('.portal-tab-btn').forEach(btn => btn.classList.remove('active'));
    const targetBtn = document.getElementById(`tab-btn-buyer-${tabKey}`);
    if (targetBtn) targetBtn.classList.add('active');

    const supplyTab = document.getElementById('tab-buyer-supply');
    const demandsTab = document.getElementById('tab-buyer-demands');
    const ordersTab = document.getElementById('tab-buyer-orders');

    if (supplyTab) supplyTab.style.display = tabKey === 'supply' ? 'block' : 'none';
    if (demandsTab) demandsTab.style.display = tabKey === 'demands' ? 'block' : 'none';
    if (ordersTab) ordersTab.style.display = tabKey === 'orders' ? 'block' : 'none';
  }

  async handleCreateDemand(event) {
    event.preventDefault();
    const crop = document.getElementById('demand-crop').value;
    const target_quantity_kg = Number(document.getElementById('demand-qty').value);
    const offered_price_per_kg = Number(document.getElementById('demand-price').value);
    const quality_tier = document.getElementById('demand-quality').value;

    const user = window.vunothoAuth.getUser();

    const payload = {
      crop,
      target_quantity_kg,
      offered_price_per_kg,
      quality_tier,
      buyer_name: user ? (user.organisation || user.name) : 'Commercial Buyer',
      user_id: user ? user.id : null,
      district: user ? user.district : 'Harare CBD',
      province: user ? user.province : 'Harare'
    };

    try {
      await window.vunothoAPI.createDemand(payload);
      window.vunothoApp.showToast(`Procurement demand for ${target_quantity_kg}kg ${crop} published!`, 'success');
      document.getElementById('new-demand-modal').classList.remove('active');
      this.switchTab('demands');
      this.render(this.container);
    } catch (err) {
      window.vunothoApp.showToast(`Error publishing demand: ${err.message}`, 'error');
    }
  }

  async orderHarvestLot(listingId, crop, quantityKg) {
    const user = window.vunothoAuth.getUser();
    window.vunothoApp.showToast(`Locking ${quantityKg}kg ${crop} for commercial dispatch...`, 'info');

    try {
      const ref = 'ECO-' + Math.floor(100000 + Math.random() * 900000);
      const gross = Number((quantityKg * 0.50).toFixed(2));
      const transport = Number((quantityKg * 35 * 0.0015 * 0.65).toFixed(2));
      const fee = Number((gross * 0.04).toFixed(2));
      const net = Number((gross - transport - fee).toFixed(2));

      await window.vunothoAPI.createTransaction({
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

      window.vunothoApp.showToast(`Harvest lot locked! Dispatched to pooled transporter route.`, 'success');
      this.switchTab('orders');
      this.render(this.container);
    } catch (e) {
      window.vunothoApp.showToast(`Order logged to local database: ${e.message}`, 'info');
    }
  }
}

window.buyerView = new BuyerView();
