/**
 * VUNOTHO TRANSPORTER & LOGISTICS PORTAL VIEW (ZERO-EMOJI ENTERPRISE DESIGN)
 * Pooled Route Manifests, Waypoint Navigation, Farmgate Inspection & Delivery Check-in
 */

class TransporterView {
  async render(container) {
    const listings = await window.vunothoAPI.getListings();
    const manifests = window.vunothoLogistics.aggregateListings(listings);
    const user = window.vunothoAuth.getUser() || { name: 'Regional Haulier', district: 'Manicaland' };

    const iconTruck = window.vunothoIcons.get('truck');
    const iconSearch = window.vunothoIcons.get('search');
    const iconMapPin = window.vunothoIcons.get('mapPin');
    const iconCheck = window.vunothoIcons.get('checkCircle');
    const iconBox = window.vunothoIcons.get('box');

    container.innerHTML = `
      <!-- Role Banner -->
      <div class="role-banner">
        <div class="role-banner-info">
          <div class="role-avatar transporter">${iconTruck}</div>
          <div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
              <h2 class="h2-title" style="margin-bottom: 0;">Transporter Hub — ${user.name}</h2>
              <span class="badge badge-verified">${user.kycStatus || 'Approved Vunotho Haulier'}</span>
            </div>
            <p class="text-sub">View aggregated multi-farmer pickup routes, verify load weights, and confirm deliveries.</p>
          </div>
        </div>
        <div>
          <button class="btn btn-orange" onclick="window.vunothoApp.showToast('Scanning nearby farmgate collection requests...', 'info')">
            ${iconSearch} Scan Nearby Requests
          </button>
        </div>
      </div>

      <!-- Quick Logistics Stats -->
      <div class="grid-4" style="margin-bottom: 1.5rem;">
        <div class="kpi-card accent-orange">
          <div class="kpi-label">Aggregated Manifests</div>
          <div class="kpi-value">${manifests.length}</div>
          <div class="kpi-sub">Optimized regional routes</div>
        </div>
        <div class="kpi-card accent-teal">
          <div class="kpi-label">Total Tonnage in Queue</div>
          <div class="kpi-value">${listings.reduce((sum, l) => sum + Number(l.quantity_kg || 0), 0)} kg</div>
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

      <div class="card">
        <div class="card-header">
          <div class="card-header-title">Active & Proposed Aggregated Collection Manifests</div>
          <span class="badge badge-orange">${manifests.length} Active Routes</span>
        </div>

        ${manifests.length === 0 ? `
          <div class="empty-state">
            <div class="empty-state-icon">${iconTruck}</div>
            <p>No active harvest pickups pending in your regional radius.</p>
            <p class="text-sub">When farmers list produce in the <strong>Farmer Portal</strong>, aggregated routes will auto-generate here.</p>
          </div>
        ` : `
          <div>
            ${manifests.map(manifest => `
              <div style="background: #ffffff; border: 2px solid var(--border-light); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                  <div>
                    <h3 class="h3-title" style="margin: 0; color: var(--navy-900);">
                      Route Manifest: ${manifest.district} to Central Terminal
                    </h3>
                    <div style="font-size: 0.825rem; color: var(--text-muted); margin-top: 0.2rem;">
                      Commodity: <strong>${manifest.crop}</strong> • Waypoints: <strong>${manifest.stopsCount} Farmgate Stops</strong> • Total Weight: <strong>${manifest.totalWeightKg} kg</strong>
                    </div>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Haulier Payout</div>
                    <div style="font-size: 1.5rem; font-weight: 800; font-family: var(--font-mono); color: var(--gold-600);">
                      ${window.vunothoPricing.formatUSD(manifest.estTransporterPayout)}
                    </div>
                  </div>
                </div>

                <!-- Load Capacity Meter -->
                <div style="margin-bottom: 1.25rem;">
                  <div style="display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 700; margin-bottom: 0.35rem;">
                    <span>Truck Capacity Utilization (2.5T Vehicle)</span>
                    <span style="color: var(--orange-600);">${manifest.totalWeightKg} / 2,500 kg (${manifest.loadUtilizationPct}%)</span>
                  </div>
                  <div style="height: 8px; background: var(--border-light); border-radius: 9999px; overflow: hidden;">
                    <div style="width: ${manifest.loadUtilizationPct}%; height: 100%; background: linear-gradient(90deg, var(--green-500) 0%, var(--orange-500) 100%); border-radius: 9999px;"></div>
                  </div>
                </div>

                <!-- Waypoint Steps -->
                <div class="waypoint-timeline">
                  ${manifest.items.map((item, idx) => `
                    <div class="waypoint-step">
                      <div class="waypoint-marker">${idx + 1}</div>
                      <div class="waypoint-content">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                          <strong style="color: var(--navy-900); font-size: 0.95rem;">${item.farmer_name || `Farmer Lot #${idx + 1}`}</strong>
                          <span class="badge badge-green">${item.quantity_kg} kg</span>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                          Location GPS: <code>${item.lat}, ${item.lng}</code> • Quality: <strong>${item.quality}</strong>
                        </div>
                        <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem;">
                          <button class="btn btn-sm btn-outline" onclick="window.vunothoApp.showToast('Navigating to waypoint via GPS...', 'info')">
                            ${iconMapPin} Navigate GPS
                          </button>
                          <button class="btn btn-sm btn-primary-green" onclick="window.vunothoApp.showToast('Farmgate load inspection verified & confirmed!', 'info')">
                            ${iconCheck} Verify & Load
                          </button>
                        </div>
                      </div>
                    </div>
                  `).join('')}

                  <div class="waypoint-step completed">
                    <div class="waypoint-marker">★</div>
                    <div class="waypoint-content" style="background: var(--navy-50);">
                      <strong style="color: var(--navy-900);">Final Destination: Mbare Central Market / Vunotho Processing Hub</strong>
                      <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">Complete offloading & trigger digital settlement release.</p>
                      <button class="btn btn-sm btn-gold" style="margin-top: 0.5rem;" onclick="window.vunothoApp.showToast('Manifest Completed: Digital payments released to farmers!', 'info')">
                        Complete Route & Release Settlement
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            `).join('')}
          </div>
        `}
      </div>
    `;
  }
}

window.transporterView = new TransporterView();
