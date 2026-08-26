/**
 * VUNOTHO FARMER PORTAL VIEW (ZERO-EMOJI ENTERPRISE DESIGN)
 * Harvest Logging, Real-Time Net Return Price Intelligence, Transport Pickup Scheduling & Ledger
 */

class FarmerView {
  async render(container) {
    const listings = await window.vunothoAPI.getListings();
    const demands = await window.vunothoAPI.getDemands();
    const transactions = await window.vunothoAPI.getTransactions();
    const user = window.vunothoAuth.getUser() || { name: 'Smallholder Farmer', district: 'Nyanga', kycStatus: 'Verified' };

    // Instant default position (non-blocking)
    const geo = window.vunothoGeo.getInstantPosition();

    const iconSprout = window.vunothoIcons.get('sprout');
    const iconPlus = window.vunothoIcons.get('plus');
    const iconCalculator = window.vunothoIcons.get('calculator');
    const iconBuilding = window.vunothoIcons.get('building');
    const iconBox = window.vunothoIcons.get('box');
    const iconMapPin = window.vunothoIcons.get('mapPin');
    const iconCheck = window.vunothoIcons.get('checkCircle');

    container.innerHTML = `
      <!-- Role Banner -->
      <div class="role-banner">
        <div class="role-banner-info">
          <div class="role-avatar farmer">${iconSprout}</div>
          <div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
              <h2 class="h2-title" style="margin-bottom: 0;">Farmer Portal — ${user.name}</h2>
              <span class="badge badge-verified" id="farmer-kyc-badge">${user.kycStatus || 'Verified Smallholder'}</span>
            </div>
            <p class="text-sub">District: <strong>${user.district || 'Nyanga'}</strong> • Log harvest, evaluate transparent net returns, and schedule pooled logistics.</p>
          </div>
        </div>
        <div>
          <button class="btn btn-primary-green" onclick="document.getElementById('new-harvest-modal').classList.add('active')">
            ${iconPlus} Log New Harvest
          </button>
        </div>
      </div>

      <!-- Quick Metrics -->
      <div class="grid-4" style="margin-bottom: 1.5rem;">
        <div class="kpi-card accent-green">
          <div class="kpi-label">Active Listings</div>
          <div class="kpi-value">${listings.length}</div>
          <div class="kpi-sub">Total: ${listings.reduce((sum, i) => sum + Number(i.quantity_kg || 0), 0)} kg</div>
        </div>
        <div class="kpi-card accent-gold">
          <div class="kpi-label">Total Net Earnings</div>
          <div class="kpi-value">${window.vunothoPricing.formatUSD(transactions.reduce((sum, t) => sum + Number(t.net_payout || 0), 0))}</div>
          <div class="kpi-sub trend-up">Disbursed to wallet</div>
        </div>
        <div class="kpi-card accent-teal">
          <div class="kpi-label">Verified Buyer Demands</div>
          <div class="kpi-value">${demands.length}</div>
          <div class="kpi-sub">Open matching requests</div>
        </div>
        <div class="kpi-card accent-orange">
          <div class="kpi-label">Transport Savings</div>
          <div class="kpi-value">${window.vunothoPricing.formatUSD(listings.length * 14.5)}</div>
          <div class="kpi-sub">Via pooled aggregation</div>
        </div>
      </div>

      <div class="grid-sidebar">
        <!-- Left Column: Active Harvest Listings -->
        <div>
          <div class="card">
            <div class="card-header">
              <div class="card-header-title">My Harvest Listings</div>
              <span class="badge badge-navy">${listings.length}</span>
            </div>

            ${listings.length === 0 ? `
              <div class="empty-state">
                <div class="empty-state-icon">${iconBox}</div>
                <p>No active harvest listings yet.</p>
                <button class="btn btn-sm btn-primary-green" style="margin-top: 0.75rem;" onclick="document.getElementById('new-harvest-modal').classList.add('active')">
                  ${iconPlus} Create First Listing
                </button>
              </div>
            ` : `
              <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                ${listings.map(item => `
                  <div class="batch-item" style="flex-direction: column; align-items: flex-start; gap: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                      <strong style="color: var(--navy-900); font-size: 1rem;">${item.quantity_kg} kg • ${item.crop}</strong>
                      <span class="badge ${item.sync_status === 'Saved Offline' ? 'badge-orange' : 'badge-green'}">${item.sync_status || 'Synced'}</span>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                      Grade: <strong>${item.quality}</strong> • District: <strong>${item.district || user.district || 'Nyanga'}</strong>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">
                      GPS: <code>${item.lat}, ${item.lng}</code>
                    </div>
                  </div>
                `).join('')}
              </div>
            `}
          </div>
        </div>

        <!-- Right Column: Price Intelligence & Live Buyer Offers -->
        <div>
          <div class="card">
            <div class="card-header">
              <div>
                <div class="card-header-title">Real-Time Price Intelligence & Buyer Offers</div>
                <p class="text-sub" style="font-size: 0.8rem; margin: 0;">Showing transparent Net Returns: Gross Offer Price minus Transport & Platform Fees.</p>
              </div>
              <span class="badge badge-teal">${demands.length} Available</span>
            </div>

            ${demands.length === 0 ? `
              <div class="empty-state">
                <div class="empty-state-icon">${iconCalculator}</div>
                <p>No buyer demand requests open right now.</p>
                <p class="text-sub">Buyers can post demands in the <strong>Buyer Portal</strong>.</p>
              </div>
            ` : `
              <div>
                ${demands.map(demand => {
                  const breakdown = window.vunothoPricing.calculateNetReturn(
                    Number(demand.offered_price_per_kg || 0.85),
                    Number(listings[0]?.quantity_kg || 200),
                    28,
                    true
                  );

                  return `
                    <div class="offer-card recommended">
                      <div class="offer-header">
                        <div class="offer-buyer-name">
                          ${iconBuilding}
                          <span>${demand.buyer_name || 'Commercial Off-taker'}</span>
                          <span class="badge badge-verified">Verified Buyer</span>
                        </div>
                        <span class="badge badge-gold">Top Match</span>
                      </div>

                      <div style="margin-bottom: 0.75rem; font-size: 0.875rem; color: var(--navy-800);">
                        Seeking <strong>${demand.target_quantity_kg} kg ${demand.crop}</strong> (${demand.quality_required}) • Delivery to: <strong>${demand.delivery_hub || 'Harare'}</strong>
                      </div>

                      <!-- Net Return Formula Spotlight -->
                      <div class="net-return-spotlight">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                          <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--gold-500);">Net-Return Breakdown</span>
                          <span style="font-size: 0.75rem; color: #cbd5e1;">(Calculated for ${breakdown.quantityKg} kg)</span>
                        </div>

                        <div class="net-return-equation">
                          <div class="equation-box">
                            <span class="equation-label">Gross Offer</span>
                            <span class="equation-num gross">${window.vunothoPricing.formatUSD(breakdown.grossTotal)}</span>
                            <span style="font-size: 0.7rem; color: #94a3b8;">${window.vunothoPricing.formatUSD(breakdown.grossPricePerKg)}/kg</span>
                          </div>
                          <div class="equation-operator">-</div>
                          <div class="equation-box">
                            <span class="equation-label">Transport</span>
                            <span class="equation-num cost">${window.vunothoPricing.formatUSD(breakdown.transportTotal)}</span>
                            <span style="font-size: 0.7rem; color: #94a3b8;">Pooled Load</span>
                          </div>
                          <div class="equation-operator">-</div>
                          <div class="equation-box">
                            <span class="equation-label">Vunotho Fee</span>
                            <span class="equation-num fee">${window.vunothoPricing.formatUSD(breakdown.platformFeeTotal)}</span>
                            <span style="font-size: 0.7rem; color: #94a3b8;">4%</span>
                          </div>
                          <div class="equation-operator">=</div>
                          <div class="equation-box">
                            <span class="equation-label" style="color: var(--gold-500);">Est. Net Return</span>
                            <span class="equation-num net">${window.vunothoPricing.formatUSD(breakdown.netTotal)}</span>
                            <span style="font-size: 0.75rem; color: var(--gold-500); font-weight: 700;">${window.vunothoPricing.formatUSD(breakdown.netReturnPerKg)}/kg</span>
                          </div>
                        </div>
                      </div>

                      <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                        <span style="font-size: 0.8rem; color: var(--green-600); font-weight: 700;">
                          Save ${window.vunothoPricing.formatUSD(breakdown.transportSavings)} vs solo transport
                        </span>
                        <button class="btn btn-sm btn-primary-green" onclick="window.farmerView.acceptOffer('${demand.id}', ${JSON.stringify(breakdown).replace(/"/g, '&quot;')})">
                          Accept Offer & Request Pickup
                        </button>
                      </div>
                    </div>
                  `;
                }).join('')}
              </div>
            `}
          </div>
        </div>
      </div>

      <!-- New Harvest Modal -->
      <div id="new-harvest-modal" class="modal-backdrop">
        <div class="modal-box">
          <div class="modal-header">
            <h3 class="h3-title">Log New Harvest Lot</h3>
            <button class="modal-close-btn" onclick="document.getElementById('new-harvest-modal').classList.remove('active')">✕</button>
          </div>
          <div class="modal-body">
            <form id="harvest-entry-form" onsubmit="window.farmerView.handleHarvestSubmit(event)">
              <div class="form-group">
                <label class="form-label">Crop Type</label>
                <select class="form-control form-select" id="harvest-crop" required>
                  <option value="Potatoes" selected>Potatoes (Stage 1 Core Crop)</option>
                  <option value="Tomatoes">Tomatoes</option>
                  <option value="Onions">Onions</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Available Volume (Kilograms)</label>
                <input type="number" class="form-control" id="harvest-quantity" min="10" step="5" value="250" required placeholder="e.g. 250" />
              </div>

              <div class="form-group">
                <label class="form-label">Produce Appearance & Quality Grade</label>
                <select class="form-control form-select" id="harvest-quality" required>
                  <option value="Grade A (Premium)">Grade A (Premium - Retail Supermarket Spec)</option>
                  <option value="Grade B (Cosmetic Imperfections)">Grade B (Cosmetic / Size Irregularity - Value Processing)</option>
                  <option value="Grade C (Sub-grade / Blemished)">Grade C (Sub-grade - Animal Feed)</option>
                  <option value="Organic Biomass (Spoiled)">Organic Biomass (Soil Compost)</option>
                </select>
                <div class="form-hint">Non-binary grading ensures even cosmetically irregular crops earn value through processing.</div>
              </div>

              <div class="form-group">
                <label class="form-label">Collection Location & Farmgate GPS</label>
                <div style="display: flex; gap: 0.5rem;">
                  <input type="text" class="form-control" id="harvest-gps" value="${geo.lat}, ${geo.lng}" required readonly />
                  <button type="button" class="btn btn-outline" onclick="window.farmerView.refreshGPS()">${iconMapPin} GPS</button>
                </div>
                <div class="form-hint">District: <strong>${user.district || 'Nyanga'} Smallholder Belt</strong> (${geo.accuracy})</div>
              </div>

              <div class="modal-footer" style="margin: 1.5rem -1.5rem -1.5rem; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('new-harvest-modal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary-green">Save Harvest Listing</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;
  }

  async refreshGPS() {
    const geo = await window.vunothoGeo.getCurrentPosition();
    const input = document.getElementById('harvest-gps');
    if (input) input.value = `${geo.lat}, ${geo.lng}`;
    window.vunothoApp.showToast('GPS coordinates acquired from device sensor.', 'info');
  }

  async handleHarvestSubmit(event) {
    event.preventDefault();
    const crop = document.getElementById('harvest-crop').value;
    const quantity_kg = Number(document.getElementById('harvest-quantity').value);
    const quality = document.getElementById('harvest-quality').value;
    const gpsVal = document.getElementById('harvest-gps').value.split(',');
    const user = window.vunothoAuth.getUser() || { id: 'FARMER-01', name: 'Smallholder Farmer', district: 'Nyanga' };

    const listing = {
      id: `LIST-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
      farmer_id: user.id || 'FARMER-01',
      farmer_name: user.name || 'Smallholder Farmer',
      crop,
      quantity_kg,
      quality,
      lat: Number(gpsVal[0].trim()),
      lng: Number(gpsVal[1].trim()),
      district: user.district || 'Nyanga',
      sync_status: navigator.onLine ? 'Synced' : 'Saved Offline',
      created_at: new Date().toISOString(),
      status: 'Open'
    };

    await window.vunothoAPI.createListing(listing);

    document.getElementById('new-harvest-modal').classList.remove('active');
    window.vunothoApp.showToast(`Harvest listing for ${quantity_kg}kg ${crop} saved to database!`, 'info');
    window.vunothoApp.refreshCurrentView();
  }

  async acceptOffer(demandId, breakdown) {
    if (!window.vunothoSync.requireOnline('accept buyer offers')) {
      return;
    }

    const demand = await window.vunothoDB.get('demands', demandId);
    const user = window.vunothoAuth.getUser() || { id: 'FARMER-01', name: 'Smallholder Farmer' };

    await window.vunothoSettlement.initiatePayment({
      farmer_id: user.id || 'FARMER-01',
      farmer_name: user.name || 'Smallholder Farmer',
      buyer_id: demand ? demand.buyer_id : 'BUYER-01',
      buyer_name: demand ? demand.buyer_name : 'Harare Fresh Distribution',
      crop: demand ? demand.crop : 'Potatoes',
      quantity_kg: breakdown.quantityKg,
      grossTotal: breakdown.grossTotal,
      transportTotal: breakdown.transportTotal,
      platformFeeTotal: breakdown.platformFeeTotal,
      netTotal: breakdown.netTotal,
      paymentMethod: 'ecocash'
    });
  }
}

window.farmerView = new FarmerView();
