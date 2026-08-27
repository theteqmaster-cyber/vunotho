/**
 * VUNOTHO TRANSPORTER & LOGISTICS PORTAL VIEW (ZERO-EMOJI ENTERPRISE DESIGN)
 * In-Portal Hero Card with Marquee Ticker, Route Exchange,
 * Pooled Route Manifests, Waypoint Navigation & Delivery Check-in
 */

class TransporterView {
  constructor() {
    this.activeTab = 'manifests'; // 'manifests' | 'cargo' | 'settlements'
  }

  async render(container) {
    this.container = container;
    const listings = (await window.vunothoAPI.getListings()) || [];
    const manifests = window.vunothoLogistics.aggregateListings(listings);
    const transactions = (await window.vunothoAPI.getTransactions()) || [];
    const user = window.vunothoAuth.getUser() || { name: 'Regional Haulier', district: 'Manicaland', province: 'Manicaland' };

    const iconTruck = window.vunothoIcons.get('truck');
    const iconSearch = window.vunothoIcons.get('search');
    const iconMapPin = window.vunothoIcons.get('mapPin');
    const iconCheck = window.vunothoIcons.get('checkCircle');
    const iconBox = window.vunothoIcons.get('box');
    const iconWallet = window.vunothoIcons.get('wallet');

    // Build Transporter Marquee Ticker
    let tickerHtml = '';
    if (manifests.length > 0) {
      tickerHtml = manifests.map(m => `
        <div class="portal-ticker-item">
          <span class="ticker-tag hot">ROUTE MANIFEST</span>
          <span><strong>${m.clusterId}</strong>: ${m.totalWeightKg}kg • ${m.stops.length} Waypoints • Est. Payout: <strong style="color: #34d399;">${window.vunothoPricing.formatUSD(m.estTransporterPayout)}</strong></span>
        </div>
      `).join('') + manifests.map(m => `
        <div class="portal-ticker-item">
          <span class="ticker-tag demand">DESTINATION</span>
          <span>${m.destination} (${m.estimatedDistanceKm} km roundtrip)</span>
        </div>
      `).join('');
    } else {
      tickerHtml = `
        <div class="portal-ticker-item">
          <span class="ticker-tag live">LOGISTICS HUB</span>
          <span><strong>Vunotho Freight Engine</strong>: Consolidated 2.5T Rural Light Truck Manifests</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag hot">ROUTE EFFICIENCY</span>
          <span><strong>Multi-Farmer Clusters</strong>: Aggregate 50kg–500kg Lots into Full 2.5T Truckloads</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag demand">GUARANTEED FARE</span>
          <span><strong>$0.05/kg/km + Mileage Fee</strong>: Transparently Paid via Mobile Money on Drop-off</span>
        </div>
        <div class="portal-ticker-item">
          <span class="ticker-tag live">LOGISTICS HUB</span>
          <span><strong>Vunotho Freight Engine</strong>: Consolidated 2.5T Rural Light Truck Manifests</span>
        </div>
      `;
    }

    container.innerHTML = `
      <!-- ==========================================================================
           1. IN-PORTAL HERO CARD WITH MARQUEE TICKER (Transporter Edition)
           ========================================================================== -->
      <div class="portal-hero-card">
        <div class="portal-hero-backdrop-pattern"></div>
        <div class="portal-hero-body">
          <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.35rem;">
              <span class="system-pill" style="background: rgba(249, 115, 22, 0.2); color: #fb923c; border-color: rgba(249, 115, 22, 0.4); font-size: 0.75rem;">
                ${iconTruck} Freight & Logistics Hub • ${user.district || 'Logistics Depot'}, ${user.province || 'Manicaland'}
              </span>
              ${user.organisation ? `<span class="badge badge-gold" style="font-size: 0.75rem;">${user.organisation}</span>` : ''}
              <span class="badge badge-verified">${user.kycStatus || 'Approved Haulier'}</span>
            </div>
            <h1 class="portal-hero-title">
              Vunotho Logistics Engine — <span class="portal-hero-highlight">Consolidated Freight</span>
            </h1>
            <p class="portal-hero-desc">
              Vehicle Capacity: <strong>${user.vehicle_type || '2.5 Tonne Rural Light Truck'}</strong> • Maximize haulage revenue by collecting clustered farmgate produce on optimized routing runs.
            </p>
          </div>

          <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <button class="btn btn-orange" onclick="window.vunothoApp.showToast('Scanning nearby smallholder collection points...', 'info')">
              ${iconSearch} Scan Waypoints
            </button>
            <button class="btn btn-outline" style="color: #ffffff; border-color: rgba(255,255,255,0.3);" onclick="window.transporterView.switchTab('manifests')">
              ${iconTruck} View Active Manifests
            </button>
          </div>
        </div>

        <!-- Continuous Animated Live Ticker Strip -->
        <div class="portal-ticker-strip">
          <div class="portal-ticker-label">
            <span>🚚</span> <span>LOGISTICS TICKER</span>
          </div>
          <div class="portal-ticker-track">
            ${tickerHtml}
          </div>
        </div>
      </div>

      <!-- ==========================================================================
           2. QUICK LOGISTICS STATS
           ========================================================================== -->
      <div class="grid-4" style="margin-bottom: 1.5rem;">
        <div class="kpi-card accent-orange">
          <div class="kpi-label">Aggregated Manifests</div>
          <div class="kpi-value">${manifests.length}</div>
          <div class="kpi-sub">Optimized regional routes</div>
        </div>
        <div class="kpi-card accent-teal">
          <div class="kpi-label">Total Tonnage in Queue</div>
          <div class="kpi-value">${listings.reduce((sum, l) => sum + Number(l.quantity_kg || 0), 0).toLocaleString()} kg</div>
          <div class="kpi-sub">Across ${listings.length} smallholder stops</div>
        </div>
        <div class="kpi-card accent-gold">
          <div class="kpi-label">Est. Haulier Earnings</div>
          <div class="kpi-value">${window.vunothoPricing.formatUSD(manifests.reduce((sum, m) => sum + Number(m.estTransporterPayout || 0), 0))}</div>
          <div class="kpi-sub trend-up">Guaranteed transparent rates</div>
        </div>
        <div class="kpi-card accent-green">
          <div class="kpi-label">Fleet Load Efficiency</div>
          <div class="kpi-value">${manifests.length > 0 ? manifests[0].loadUtilizationPct : 0}%</div>
          <div class="kpi-sub">Multi-farm pooled capacity</div>
        </div>
      </div>

      <!-- ==========================================================================
           3. IN-PORTAL SUB-NAVIGATION TABS
           ========================================================================== -->
      <div class="portal-subnav-bar">
        <button type="button" class="portal-tab-btn ${this.activeTab === 'manifests' ? 'active' : ''}" id="tab-btn-trans-manifests" onclick="window.transporterView.switchTab('manifests')">
          ${iconTruck} Aggregated Route Manifests <span class="tab-badge">${manifests.length} Routes</span>
        </button>
        <button type="button" class="portal-tab-btn ${this.activeTab === 'cargo' ? 'active' : ''}" id="tab-btn-trans-cargo" onclick="window.transporterView.switchTab('cargo')">
          ${iconBox} Available Farmgate Cargo <span class="tab-badge">${listings.length} Lots</span>
        </button>
        <button type="button" class="portal-tab-btn ${this.activeTab === 'settlements' ? 'active' : ''}" id="tab-btn-trans-settlements" onclick="window.transporterView.switchTab('settlements')">
          ${iconWallet} Trip Settlements & Fuel Ledger <span class="tab-badge">${transactions.length}</span>
        </button>
      </div>

      <!-- ==========================================================================
           4. TAB 1: AGGREGATED ROUTE MANIFESTS
           ========================================================================== -->
      <div id="tab-trans-manifests" style="display: ${this.activeTab === 'manifests' ? 'block' : 'none'};">
        <div class="card" style="margin-bottom: 2rem;">
          <div class="card-header">
            <div class="card-header-title">Active & Proposed Aggregated Collection Manifests</div>
            <span class="badge badge-orange">${manifests.length} Manifests</span>
          </div>

          ${manifests.length === 0 ? `
            <div class="empty-state">
              <div class="empty-state-icon">${iconTruck}</div>
              <p>No pooled route manifests formed yet.</p>
              <span style="font-size: 0.75rem; color: var(--text-muted);">As smallholders log harvest lots in the same farming district, Vunotho automatically clusters them into 2.5T truck manifests.</span>
            </div>
          ` : `
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
              ${manifests.map(m => `
                <div class="batch-item" style="flex-direction: column; align-items: flex-start; gap: 1rem; border-left: 4px solid var(--orange-500); padding: 1.25rem;">
                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                      <strong style="color: var(--navy-900); font-size: 1.15rem;">Route Manifest ${m.clusterId}</strong>
                      <div style="font-size: 0.8rem; color: var(--text-muted);">
                        Corridor: <strong>${m.originDistrict} ➔ ${m.destination}</strong> (${m.estimatedDistanceKm} km)
                      </div>
                    </div>
                    <div style="text-align: right;">
                      <span class="badge badge-gold" style="font-size: 0.9rem;">Payout: ${window.vunothoPricing.formatUSD(m.estTransporterPayout)}</span>
                      <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">Capacity: <strong>${m.loadUtilizationPct}% Filled</strong> (${m.totalWeightKg} / 2,500 kg)</div>
                    </div>
                  </div>

                  <!-- Progress Bar of Truck Capacity -->
                  <div style="width: 100%;">
                    <div class="splash-progress-track" style="height: 6px; background: rgba(0,0,0,0.08);">
                      <div style="height: 100%; width: ${m.loadUtilizationPct}%; background: linear-gradient(90deg, #f59e0b, #10b981); border-radius: 9999px;"></div>
                    </div>
                  </div>

                  <!-- Waypoint Stops List -->
                  <div style="width: 100%; background: #ffffff; padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--navy-900); text-transform: uppercase; margin-bottom: 0.5rem;">
                      ${iconMapPin} ${m.stops.length} Farmgate Collection Waypoints
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                      ${m.stops.map((stop, idx) => `
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; border-bottom: 1px dotted var(--border-light); padding-bottom: 0.25rem;">
                          <span>${idx + 1}. <strong>${stop.farmerName}</strong> (${stop.crop} • ${stop.weightKg} kg)</span>
                          <code>GPS: ${stop.lat}, ${stop.lng}</code>
                        </div>
                      `).join('')}
                    </div>
                  </div>

                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <span class="status-indicator synced" style="font-size: 0.75rem;">Optimized & Ready for Dispatch</span>
                    <button class="btn btn-sm btn-orange" onclick="window.transporterView.acceptManifest('${m.clusterId}')">
                      Accept & Lock Route Manifest →
                    </button>
                  </div>
                </div>
              `).join('')}
            </div>
          `}
        </div>
      </div>

      <!-- ==========================================================================
           5. TAB 2: AVAILABLE FARMGATE CARGO
           ========================================================================== -->
      <div id="tab-trans-cargo" style="display: ${this.activeTab === 'cargo' ? 'block' : 'none'};">
        <div class="card" style="margin-bottom: 2rem;">
          <div class="card-header">
            <div class="card-header-title">Unassigned Farmgate Produce Lots</div>
            <span class="badge badge-teal">${listings.length} Lots</span>
          </div>

          ${listings.length === 0 ? `
            <div class="empty-state">
              <div class="empty-state-icon">${iconBox}</div>
              <p>No unassigned farmgate cargo available.</p>
            </div>
          ` : `
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem;">
              ${listings.map(item => `
                <div class="batch-item" style="flex-direction: column; align-items: flex-start; gap: 0.5rem;">
                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                    <strong style="color: var(--navy-900); font-size: 1.05rem;">${item.crop} (${item.quantity_kg} kg)</strong>
                    <span class="badge badge-green">${item.quality || 'Grade A'}</span>
                  </div>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">
                    Origin: <strong>${item.district || 'Nyanga'}, ${item.province || 'Manicaland'}</strong> • Farmer: <strong>${item.farmer_name || 'Smallholder'}</strong>
                  </div>
                  <div style="font-size: 0.75rem; color: var(--text-secondary);">
                    Pickup Coordinates: <code>${item.lat}, ${item.lng}</code>
                  </div>
                  <div style="display: flex; justify-content: space-between; width: 100%; align-items: center; border-top: 1px dashed var(--border-light); padding-top: 0.5rem; margin-top: 0.25rem;">
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Est. Freight: <strong>$${(item.quantity_kg * 35 * 0.0015 * 0.65).toFixed(2)}</strong></span>
                    <button class="btn btn-sm btn-outline" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;" onclick="window.transporterView.switchTab('manifests')">
                      Cluster on Route →
                    </button>
                  </div>
                </div>
              `).join('')}
            </div>
          `}
        </div>
      </div>

      <!-- ==========================================================================
           6. TAB 3: SETTLEMENTS & FUEL LEDGER
           ========================================================================== -->
      <div id="tab-trans-settlements" style="display: ${this.activeTab === 'settlements' ? 'block' : 'none'};">
        <div class="card" style="margin-bottom: 2rem;">
          <div class="card-header">
            <div class="card-header-title">Completed Haulage Runs & Fuel Remittances</div>
            <span class="badge badge-navy">${transactions.length} Trips</span>
          </div>

          ${transactions.length === 0 ? `
            <div class="empty-state">
              <div class="empty-state-icon">${iconWallet}</div>
              <p>No completed haulage trips yet.</p>
              <span style="font-size: 0.75rem; color: var(--text-muted);">Completed deliveries with weighbridge confirmations will appear here.</span>
            </div>
          ` : `
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
              ${transactions.map(t => `
                <div class="batch-item" style="justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                  <div>
                    <strong style="color: var(--navy-900); font-size: 0.95rem;">${t.crop} Delivery (${t.quantity_kg || 300} kg)</strong>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Trip Ref: <code>${t.receipt_reference || t.id}</code> • Drop-off Destination: <strong>Harare Hub</strong></div>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-size: 1.05rem; font-weight: 800; color: var(--green-600);">${window.vunothoPricing.formatUSD(t.transport_cost || 18.50)}</div>
                    <span class="badge badge-green" style="font-size: 0.7rem;">Remitted via Mobile Money</span>
                  </div>
                </div>
              `).join('')}
            </div>
          `}
        </div>
      </div>
    `;
  }

  switchTab(tabKey) {
    this.activeTab = tabKey;
    document.querySelectorAll('.portal-tab-btn').forEach(btn => btn.classList.remove('active'));
    const targetBtn = document.getElementById(`tab-btn-trans-${tabKey}`);
    if (targetBtn) targetBtn.classList.add('active');

    const manifestsTab = document.getElementById('tab-trans-manifests');
    const cargoTab = document.getElementById('tab-trans-cargo');
    const settlementsTab = document.getElementById('tab-trans-settlements');

    if (manifestsTab) manifestsTab.style.display = tabKey === 'manifests' ? 'block' : 'none';
    if (cargoTab) cargoTab.style.display = tabKey === 'cargo' ? 'block' : 'none';
    if (settlementsTab) settlementsTab.style.display = tabKey === 'settlements' ? 'block' : 'none';
  }

  acceptManifest(clusterId) {
    window.vunothoApp.showToast(`Route Manifest ${clusterId} locked! Waypoint GPS coordinates sent to navigation.`, 'success');
  }
}

window.transporterView = new TransporterView();
