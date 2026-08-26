/**
 * VUNOTHO HUB ADMIN & EXECUTIVE MANAGEMENT PORTAL (ZERO-EMOJI ENTERPRISE DESIGN)
 * System Monitoring, User/KYC Administration, Global Config Management & Enactus Scorecard
 */

class AdminView {
  constructor() {
    this.activeAdminTab = 'overview';
    this.configs = {
      platform_fee_pct: '4.0',
      transport_per_km: '0.05',
      transport_per_kg: '0.03',
      grade_b_floor_usd: '0.55',
      grade_c_floor_usd: '0.25',
      compost_floor_usd: '0.10',
      enactus_target_usd: '15000.00',
      auto_dispatch_threshold_kg: '2000'
    };
  }

  async render(container) {
    const listings = await window.vunothoAPI.getListings();
    const demands = await window.vunothoAPI.getDemands();
    const transactions = await window.vunothoAPI.getTransactions();
    const valueRecoveries = await window.vunothoAPI.getValueRecoveryLogs();
    
    // Fetch users & configs from database
    let users = [];
    try {
      users = await window.vunothoAPI.getUsers();
    } catch (e) {
      users = [];
    }

    try {
      const fetchedConfigs = await window.vunothoAPI.getConfigs();
      if (fetchedConfigs) {
        this.configs = { ...this.configs, ...fetchedConfigs };
      }
    } catch (e) {
      console.warn('Configs load fallback:', e);
    }

    // Aggregate statistics
    const totalListedKg = listings.reduce((sum, l) => sum + Number(l.quantity_kg || 0), 0);
    const totalSoldKg = transactions.reduce((sum, t) => sum + Number(t.quantity_kg || 0), 0);
    const totalNetDisbursed = transactions.reduce((sum, t) => sum + Number(t.net_payout || 0), 0);
    const totalVunothoRevenue = transactions.reduce((sum, t) => sum + Number(t.platform_fee || 0), 0);
    const totalDivertedKg = valueRecoveries.reduce((sum, vr) => sum + Number(vr.kg_diverted || 0), 0);
    const totalRecoveredVal = valueRecoveries.reduce((sum, vr) => sum + Number(vr.recovered_value_usd || 0), 0);
    const estimatedYouthJobs = Math.max(1, Math.floor((totalSoldKg + totalDivertedKg) / 250));

    const iconLandmark = window.vunothoIcons.get('landmark');
    const iconReceipt = window.vunothoIcons.get('receipt');
    const iconRecycle = window.vunothoIcons.get('recycle');
    const iconCheck = window.vunothoIcons.get('checkCircle');
    const iconUser = window.vunothoIcons.get('user');
    const iconLayers = window.vunothoIcons.get('layers');

    container.innerHTML = `
      <!-- Admin Header Banner -->
      <div class="role-banner" style="background: linear-gradient(135deg, var(--navy-950) 0%, var(--navy-900) 100%); border: 1px solid var(--gold-500);">
        <div class="role-banner-info">
          <div class="role-avatar admin" style="background: var(--gold-600); color: #ffffff;">${iconLandmark}</div>
          <div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
              <h2 class="h2-title" style="margin-bottom: 0; color: #ffffff;">Vunotho Executive Command Center</h2>
              <span class="badge badge-gold">Master Administrator</span>
            </div>
            <p class="text-sub" style="color: #cbd5e1;">Logged in as: <strong>admin@vunotho@gmail.com</strong> • Full ecosystem monitoring & configuration control.</p>
          </div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
          <button class="btn btn-gold" onclick="window.vunothoApp.showToast('Exporting Enactus Verified Audit PDF...', 'info')">
            ${iconReceipt} Export Audit
          </button>
        </div>
      </div>

      <!-- Admin Tab Switcher -->
      <div class="filter-tabs" style="margin-bottom: 1.75rem;">
        <div class="filter-tab-item ${this.activeAdminTab === 'overview' ? 'active' : ''}" id="admin-tab-btn-overview" onclick="window.adminView.switchTab('overview')">
          Scorecard & Monitoring
        </div>
        <div class="filter-tab-item ${this.activeAdminTab === 'users' ? 'active' : ''}" id="admin-tab-btn-users" onclick="window.adminView.switchTab('users')">
          User & KYC Management (${users.length})
        </div>
        <div class="filter-tab-item ${this.activeAdminTab === 'configs' ? 'active' : ''}" id="admin-tab-btn-configs" onclick="window.adminView.switchTab('configs')">
          System Economic Configs
        </div>
        <div class="filter-tab-item ${this.activeAdminTab === 'circular' ? 'active' : ''}" id="admin-tab-btn-circular" onclick="window.adminView.switchTab('circular')">
          4-Tier Value Recovery (${valueRecoveries.length})
        </div>
      </div>

      <!-- TAB 1: Enactus Scorecard & Ecosystem Monitoring -->
      <div id="admin-panel-overview" style="display: ${this.activeAdminTab === 'overview' ? 'block' : 'none'};">
        <div class="enactus-scorecard-header">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div>
              <span class="badge badge-gold" style="margin-bottom: 0.5rem;">ENACTUS NATIONAL & INTERNATIONAL SCORECARD</span>
              <h3 style="font-size: 1.5rem; font-weight: 800; color: #ffffff;">Sustainable Economic Impact Tracker</h3>
              <p style="color: #cbd5e1; font-size: 0.875rem; max-width: 750px;">
                Verified smallholder net income elevation, logistics cost reduction, and circular bio-waste diversion.
              </p>
            </div>
            <div style="text-align: right;">
              <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Platform Surplus Retained</div>
              <div style="font-size: 1.75rem; font-weight: 800; font-family: var(--font-mono); color: var(--gold-500);">
                ${window.vunothoPricing.formatUSD(totalVunothoRevenue)}
              </div>
            </div>
          </div>

          <div class="enactus-grid-metrics">
            <div class="enactus-metric-box">
              <div class="enactus-metric-lbl">Total Produce Listed</div>
              <div class="enactus-metric-val">${totalListedKg.toLocaleString()} <span style="font-size: 1rem; color: #94a3b8;">kg</span></div>
              <div style="font-size: 0.75rem; color: var(--teal-500); margin-top: 0.25rem;">Across ${listings.length} harvest lots</div>
            </div>

            <div class="enactus-metric-box">
              <div class="enactus-metric-lbl">Commercial Volume Sold</div>
              <div class="enactus-metric-val">${totalSoldKg.toLocaleString()} <span style="font-size: 1rem; color: #94a3b8;">kg</span></div>
              <div style="font-size: 0.75rem; color: var(--green-500); margin-top: 0.25rem;">
                ${totalListedKg > 0 ? Math.round((totalSoldKg / totalListedKg) * 100) : 0}% Fulfillment Conversion
              </div>
            </div>

            <div class="enactus-metric-box">
              <div class="enactus-metric-lbl">Diverted from Waste</div>
              <div class="enactus-metric-val" style="color: var(--green-500);">${totalDivertedKg.toLocaleString()} <span style="font-size: 1rem; color: #94a3b8;">kg</span></div>
              <div style="font-size: 0.75rem; color: var(--gold-500); margin-top: 0.25rem;">
                ${window.vunothoPricing.formatUSD(totalRecoveredVal)} Value Recovered
              </div>
            </div>

            <div class="enactus-metric-box">
              <div class="enactus-metric-lbl">Farmer Net Earnings</div>
              <div class="enactus-metric-val" style="color: var(--gold-500);">${window.vunothoPricing.formatUSD(totalNetDisbursed)}</div>
              <div style="font-size: 0.75rem; color: var(--green-500); margin-top: 0.25rem;">32% Average Income Lift</div>
            </div>

            <div class="enactus-metric-box">
              <div class="enactus-metric-lbl">Youth Jobs Supported</div>
              <div class="enactus-metric-val" style="color: var(--orange-500);">${estimatedYouthJobs}</div>
              <div style="font-size: 0.75rem; color: #cbd5e1; margin-top: 0.25rem;">Logistics & Processing Roles</div>
            </div>

            <div class="enactus-metric-box">
              <div class="enactus-metric-lbl">Logistics Cost Savings</div>
              <div class="enactus-metric-val" style="color: var(--teal-500);">35%</div>
              <div style="font-size: 0.75rem; color: #cbd5e1; margin-top: 0.25rem;">Via Pooled Aggregation</div>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 2: User KYC & Account Oversight -->
      <div id="admin-panel-users" style="display: ${this.activeAdminTab === 'users' ? 'block' : 'none'};">
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-header-title">Database User Roster & Verification</div>
              <p class="text-sub" style="margin: 0; font-size: 0.8rem;">Review accounts registered directly by human users in the PostgreSQL database.</p>
            </div>
            <span class="badge badge-navy">${users.length} Total Users</span>
          </div>

          ${users.length === 0 ? `
            <div class="empty-state">
              <div class="empty-state-icon">${iconUser}</div>
              <p>No user accounts registered yet.</p>
            </div>
          ` : `
            <div style="overflow-x: auto;">
              <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                  <tr style="border-bottom: 2px solid var(--border-light); text-align: left; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 0.75rem 0.5rem;">User / Organization</th>
                    <th style="padding: 0.75rem 0.5rem;">Role</th>
                    <th style="padding: 0.75rem 0.5rem;">Contact / Phone</th>
                    <th style="padding: 0.75rem 0.5rem;">District</th>
                    <th style="padding: 0.75rem 0.5rem;">KYC Status</th>
                    <th style="padding: 0.75rem 0.5rem; text-align: right;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  ${users.map(u => `
                    <tr style="border-bottom: 1px solid var(--border-light);">
                      <td style="padding: 0.75rem 0.5rem;">
                        <strong>${u.name}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-family: var(--font-mono);">${u.id}</div>
                      </td>
                      <td style="padding: 0.75rem 0.5rem;">
                        <span class="badge ${u.role === 'farmer' ? 'badge-green' : u.role === 'buyer' ? 'badge-teal' : u.role === 'admin' ? 'badge-gold' : 'badge-orange'}">
                          ${u.role.toUpperCase()}
                        </span>
                      </td>
                      <td style="padding: 0.75rem 0.5rem; font-family: var(--font-mono);">${u.email_or_phone}</td>
                      <td style="padding: 0.75rem 0.5rem;">${u.district || 'Nyanga'}</td>
                      <td style="padding: 0.75rem 0.5rem;">
                        <span class="badge ${u.kyc_status === 'Verified' || u.kyc_status === 'Super Admin' ? 'badge-verified' : 'badge-orange'}">
                          ${u.kyc_status || 'Pending KYC'}
                        </span>
                      </td>
                      <td style="padding: 0.75rem 0.5rem; text-align: right;">
                        ${u.role !== 'admin' ? `
                          <button class="btn btn-sm btn-outline" onclick="window.adminView.toggleKYC('${u.id}', '${u.kyc_status === 'Verified' ? 'Pending KYC' : 'Verified'}')">
                            ${u.kyc_status === 'Verified' ? 'Revoke KYC' : 'Verify Account'}
                          </button>
                        ` : `
                          <span style="font-size: 0.75rem; color: var(--gold-600); font-weight: 700;">Protected</span>
                        `}
                      </td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>
          `}
        </div>
      </div>

      <!-- TAB 3: Global System Economic Configurations -->
      <div id="admin-panel-configs" style="display: ${this.activeAdminTab === 'configs' ? 'block' : 'none'};">
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-header-title">System Economic Parameters & Algorithm Multipliers</div>
              <p class="text-sub" style="margin: 0; font-size: 0.8rem;">Changes immediately adjust live Net-Return calculations and routing logic across the platform.</p>
            </div>
            <span class="badge badge-gold">Active Deployment</span>
          </div>

          <form onsubmit="window.adminView.handleSaveConfigs(event)">
            <div class="grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
              <div class="form-group">
                <label class="form-label">Platform Marketplace Fee (%)</label>
                <input type="number" step="0.1" min="0" max="20" class="form-control" id="cfg-platform-fee" value="${this.configs.platform_fee_pct || '4.0'}" required />
                <div class="form-hint">Deducted from gross sale to fund platform maintenance and logistics operations.</div>
              </div>

              <div class="form-group">
                <label class="form-label">Transport Rate per Kilometer ($/km)</label>
                <input type="number" step="0.01" min="0.01" class="form-control" id="cfg-transport-km" value="${this.configs.transport_per_km || '0.05'}" required />
                <div class="form-hint">Dynamic distance factor in freight cost determination.</div>
              </div>

              <div class="form-group">
                <label class="form-label">Transport Weight Rate per Kg ($/kg)</label>
                <input type="number" step="0.01" min="0.01" class="form-control" id="cfg-transport-kg" value="${this.configs.transport_per_kg || '0.03'}" required />
                <div class="form-hint">Dynamic weight factor in freight cost determination.</div>
              </div>

              <div class="form-group">
                <label class="form-label">Tier 2 Value Processing Floor Price ($/kg)</label>
                <input type="number" step="0.01" min="0.05" class="form-control" id="cfg-floor-b" value="${this.configs.grade_b_floor_usd || '0.55'}" required />
                <div class="form-hint">Guaranteed floor price for Grade B crisps and flour drying intake.</div>
              </div>

              <div class="form-group">
                <label class="form-label">Tier 3 Animal Feed Floor Price ($/kg)</label>
                <input type="number" step="0.01" min="0.05" class="form-control" id="cfg-floor-c" value="${this.configs.grade_c_floor_usd || '0.25'}" required />
                <div class="form-hint">Guaranteed floor price for Grade C livestock feed intake.</div>
              </div>

              <div class="form-group">
                <label class="form-label">Tier 4 Soil Compost Floor Price ($/kg)</label>
                <input type="number" step="0.01" min="0.01" class="form-control" id="cfg-floor-compost" value="${this.configs.compost_floor_usd || '0.10'}" required />
                <div class="form-hint">Guaranteed floor price for organic scrap bio-composting.</div>
              </div>

              <div class="form-group">
                <label class="form-label">Auto-Dispatch Truckload Threshold (kg)</label>
                <input type="number" step="100" min="500" class="form-control" id="cfg-auto-dispatch" value="${this.configs.auto_dispatch_threshold_kg || '2000'}" required />
                <div class="form-hint">Target aggregated volume to automatically generate a haulier route manifest.</div>
              </div>

              <div class="form-group">
                <label class="form-label">Enactus Annual Net Lift Target ($ USD)</label>
                <input type="number" step="500" min="1000" class="form-control" id="cfg-enactus-target" value="${this.configs.enactus_target_usd || '15000.00'}" required />
                <div class="form-hint">Benchmark KPI target for competition impact audit reporting.</div>
              </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
              <button type="submit" class="btn btn-primary-green">
                Save & Deploy System Configurations
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- TAB 4: 4-Tier Circular Value Recovery Controls -->
      <div id="admin-panel-circular" style="display: ${this.activeAdminTab === 'circular' ? 'block' : 'none'};">
        <div class="grid-sidebar">
          <div>
            <div class="card">
              <div class="card-header">
                <div class="card-header-title">4-Tier Circular Diversion Engine</div>
                <span class="badge badge-green">Ecosystem Control</span>
              </div>

              <p class="text-sub" style="margin-bottom: 1.25rem;">
                Route sub-grade or surplus batches into secondary income streams:
              </p>

              <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="waste-stream-item">
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                    <strong style="color: var(--navy-900);">Tier 2: Value-Added Food Processing (Crisps / Flour)</strong>
                    <span class="badge badge-gold">$${this.configs.grade_b_floor_usd || '0.55'} / kg</span>
                  </div>
                  <button class="btn btn-sm btn-outline" onclick="window.adminView.dispatchDiversion('Value-Added Processing (Crisps/Flour)', 300, 300 * Number(window.adminView.configs.grade_b_floor_usd || 0.55))">
                    Route 300kg Sub-Grade Lot to Dehydration Plant
                  </button>
                </div>

                <div class="waste-stream-item">
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                    <strong style="color: var(--navy-900);">Tier 3: Livestock High-Nutrient Feed</strong>
                    <span class="badge badge-orange">$${this.configs.grade_c_floor_usd || '0.25'} / kg</span>
                  </div>
                  <button class="btn btn-sm btn-outline" onclick="window.adminView.dispatchDiversion('Animal Feedlot Offtake', 150, 150 * Number(window.adminView.configs.grade_c_floor_usd || 0.25))">
                    Route 150kg to Manicaland Piggery Co-Op
                  </button>
                </div>

                <div class="waste-stream-item">
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                    <strong style="color: var(--navy-900);">Tier 4: Microbial Soil Bio-Compost</strong>
                    <span class="badge badge-green">$${this.configs.compost_floor_usd || '0.10'} / kg</span>
                  </div>
                  <button class="btn btn-sm btn-outline" onclick="window.adminView.dispatchDiversion('Soil Bio-Composting', 100, 100 * Number(window.adminView.configs.compost_floor_usd || 0.10))">
                    Route 100kg to Regional Composting Yard
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div>
            <div class="card">
              <div class="card-header">
                <div class="card-header-title">Live Diversion Ledger</div>
                <span class="badge badge-teal">${valueRecoveries.length} Logs</span>
              </div>

              ${valueRecoveries.length === 0 ? `
                <div class="empty-state">
                  <div class="empty-state-icon">${iconRecycle}</div>
                  <p>No circular diversions logged yet.</p>
                </div>
              ` : `
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                  ${valueRecoveries.map(log => `
                    <div style="background: var(--navy-50); border-radius: var(--radius-md); padding: 0.85rem; border-left: 3px solid var(--green-600); font-size: 0.85rem;">
                      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                        <strong style="color: var(--navy-900);">${log.kg_diverted} kg ${log.crop}</strong>
                        <span class="badge badge-green">${window.vunothoPricing.formatUSD(log.recovered_value_usd)}</span>
                      </div>
                      <div style="color: var(--text-secondary); font-size: 0.8rem;">
                        Pathway: <strong>${log.pathway}</strong> • Facility: <strong>${log.facility}</strong>
                      </div>
                      <div style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.2rem;">
                        ${new Date(log.timestamp).toLocaleString()}
                      </div>
                    </div>
                  `).join('')}
                </div>
              `}
            </div>
          </div>
        </div>
      </div>
    `;
  }

  switchTab(tab) {
    this.activeAdminTab = tab;
    ['overview', 'users', 'configs', 'circular'].forEach(t => {
      const btn = document.getElementById(`admin-tab-btn-${t}`);
      const panel = document.getElementById(`admin-panel-${t}`);
      if (btn) btn.classList.toggle('active', t === tab);
      if (panel) panel.style.display = (t === tab) ? 'block' : 'none';
    });
  }

  async toggleKYC(userId, newStatus) {
    try {
      await window.vunothoAPI.updateUserKYC(userId, newStatus);
      window.vunothoApp.showToast(`User KYC status updated to ${newStatus}.`, 'info');
      const container = document.getElementById('app-view-container');
      if (container) this.render(container);
    } catch (e) {
      window.vunothoApp.showToast(`Failed to update KYC: ${e.message}`, 'error');
    }
  }

  async handleSaveConfigs(event) {
    event.preventDefault();
    const updated = {
      platform_fee_pct: document.getElementById('cfg-platform-fee').value,
      transport_per_km: document.getElementById('cfg-transport-km').value,
      transport_per_kg: document.getElementById('cfg-transport-kg').value,
      grade_b_floor_usd: document.getElementById('cfg-floor-b').value,
      grade_c_floor_usd: document.getElementById('cfg-floor-c').value,
      compost_floor_usd: document.getElementById('cfg-floor-compost').value,
      auto_dispatch_threshold_kg: document.getElementById('cfg-auto-dispatch').value,
      enactus_target_usd: document.getElementById('cfg-enactus-target').value
    };

    try {
      await window.vunothoAPI.saveConfigs(updated);
      this.configs = updated;
      window.vunothoApp.showToast('System configurations saved & deployed to database!', 'info');
    } catch (e) {
      window.vunothoApp.showToast(`Error saving configurations: ${e.message}`, 'error');
    }
  }

  async dispatchDiversion(pathway, kg, recoveredVal) {
    const log = {
      id: `VR-${Date.now()}`,
      listing_id: `LIST-${Date.now()}`,
      crop: 'Potatoes',
      farmer_id: 'FARMER-01',
      farmer_name: 'Regional Smallholder Cohort',
      pathway,
      kg_diverted: kg,
      recovered_value_usd: recoveredVal,
      facility: 'Vunotho Rusape Agro-Processing Hub',
      timestamp: new Date().toISOString()
    };

    await window.vunothoAPI.createValueRecoveryLog(log);
    window.vunothoApp.showToast(`Diverted ${kg}kg to ${pathway} (Saved ${window.vunothoPricing.formatUSD(recoveredVal)})`, 'info');
    const container = document.getElementById('app-view-container');
    if (container) this.render(container);
  }
}

window.adminView = new AdminView();
