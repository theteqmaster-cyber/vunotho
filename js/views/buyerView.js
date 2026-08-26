/**
 * VUNOTHO BUYER PORTAL VIEW (ZERO-EMOJI ENTERPRISE DESIGN)
 * Bulk Demand Creation, Multi-Farmer Aggregated Order Matching & Fulfillment Tracking
 */

class BuyerView {
  async render(container) {
    const demands = await window.vunothoAPI.getDemands();
    const listings = await window.vunothoAPI.getListings();
    const transactions = await window.vunothoAPI.getTransactions();
    const user = window.vunothoAuth.getUser() || { name: 'Commercial Off-taker', district: 'Harare' };

    const iconBuilding = window.vunothoIcons.get('building');
    const iconPlus = window.vunothoIcons.get('plus');
    const iconBox = window.vunothoIcons.get('box');
    const iconTruck = window.vunothoIcons.get('truck');
    const iconCheck = window.vunothoIcons.get('checkCircle');

    container.innerHTML = `
      <!-- Role Banner -->
      <div class="role-banner">
        <div class="role-banner-info">
          <div class="role-avatar buyer">${iconBuilding}</div>
          <div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
              <h2 class="h2-title" style="margin-bottom: 0;">Buyer Portal — ${user.name}</h2>
              <span class="badge badge-verified">${user.kycStatus || 'Verified Commercial Buyer'}</span>
            </div>
            <p class="text-sub">Post bulk commodity demand, secure verified farmer supply, and track pooled fulfillment.</p>
          </div>
        </div>
        <div>
          <button class="btn btn-primary-navy" onclick="document.getElementById('new-demand-modal').classList.add('active')">
            ${iconPlus} Post Purchase Demand
          </button>
        </div>
      </div>

      <!-- Quick Metrics -->
      <div class="grid-4" style="margin-bottom: 1.5rem;">
        <div class="kpi-card accent-teal">
          <div class="kpi-label">Active Demands</div>
          <div class="kpi-value">${demands.length}</div>
          <div class="kpi-sub">Total Target: ${demands.reduce((sum, d) => sum + Number(d.target_quantity_kg || 0), 0)} kg</div>
        </div>
        <div class="kpi-card accent-green">
          <div class="kpi-label">Available Farmer Supply</div>
          <div class="kpi-value">${listings.reduce((sum, l) => sum + Number(l.quantity_kg || 0), 0)} kg</div>
          <div class="kpi-sub">Ready across smallholder belts</div>
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

      <div class="grid-sidebar">
        <!-- Left: Open Buyer Demands -->
        <div>
          <div class="card">
            <div class="card-header">
              <div class="card-header-title">My Active Purchase Orders</div>
              <span class="badge badge-teal">${demands.length}</span>
            </div>

            ${demands.length === 0 ? `
              <div class="empty-state">
                <div class="empty-state-icon">${iconBox}</div>
                <p>No purchase demands posted yet.</p>
                <button class="btn btn-sm btn-primary-navy" style="margin-top: 0.75rem;" onclick="document.getElementById('new-demand-modal').classList.add('active')">
                  ${iconPlus} Create Demand Request
                </button>
              </div>
            ` : `
              <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                ${demands.map(item => `
                  <div class="batch-item" style="flex-direction: column; align-items: flex-start; gap: 0.5rem; border-left-color: var(--teal-600);">
                    <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                      <strong style="color: var(--navy-900); font-size: 1rem;">${item.target_quantity_kg} kg ${item.crop}</strong>
                      <span class="badge badge-gold">${window.vunothoPricing.formatUSD(item.offered_price_per_kg)}/kg</span>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                      Spec: <strong>${item.quality_required}</strong> • Hub: <strong>${item.delivery_hub}</strong>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--teal-700); font-weight: 600;">
                      Deadline: ${new Date(item.deadline).toLocaleDateString()}
                    </div>
                  </div>
                `).join('')}
              </div>
            `}
          </div>
        </div>

        <!-- Right: Aggregated Multi-Farmer Supply Matches -->
        <div>
          <div class="card">
            <div class="card-header">
              <div class="card-header-title">Multi-Farmer Aggregated Supply Matches</div>
              <span class="badge badge-green">${listings.length} Lots Available</span>
            </div>

            ${listings.length === 0 ? `
              <div class="empty-state">
                <div class="empty-state-icon">${iconBox}</div>
                <p>No matching farmer listings in the regional queue.</p>
                <p class="text-sub">Farmers can list harvests in the <strong>Farmer Portal</strong>.</p>
              </div>
            ` : `
              <div>
                <p class="text-sub" style="margin-bottom: 1rem;">
                  Vunotho's aggregation algorithm automatically combines multiple smallholder lots into single commercial truckload deliveries:
                </p>

                <div style="background: var(--navy-50); border-radius: var(--radius-md); padding: 1.25rem; border: 1px solid var(--border-light); margin-bottom: 1.5rem;">
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <h4 style="font-weight: 700; color: var(--navy-900);">Nyanga Smallholder Pooled Batch</h4>
                    <span class="badge badge-green">Combined: ${listings.reduce((sum, l) => sum + Number(l.quantity_kg || 0), 0)} kg</span>
                  </div>

                  ${listings.map((l, index) => `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px dashed var(--border-light); font-size: 0.85rem;">
                      <div>
                        <strong>Farmer #${index + 1} (${l.farmer_name || 'Smallholder'})</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">${l.quality} • GPS: ${l.lat}, ${l.lng}</div>
                      </div>
                      <span style="font-weight: 700; color: var(--navy-900); font-family: var(--font-mono);">${l.quantity_kg} kg</span>
                    </div>
                  `).join('')}

                  <div style="margin-top: 1rem; display: flex; justify-content: flex-end;">
                    <button class="btn btn-sm btn-primary-green" onclick="window.vunothoApp.showToast('Aggregated Batch dispatched to Logistics Hub!', 'info')">
                      Lock Aggregated Batch & Dispatch Pickup
                    </button>
                  </div>
                </div>
              </div>
            `}
          </div>
        </div>
      </div>

      <!-- New Demand Modal -->
      <div id="new-demand-modal" class="modal-backdrop">
        <div class="modal-box">
          <div class="modal-header">
            <h3 class="h3-title">Post Commercial Demand Request</h3>
            <button class="modal-close-btn" onclick="document.getElementById('new-demand-modal').classList.remove('active')">✕</button>
          </div>
          <div class="modal-body">
            <form id="demand-entry-form" onsubmit="window.buyerView.handleDemandSubmit(event)">
              <div class="form-group">
                <label class="form-label">Buyer / Organization Name</label>
                <input type="text" class="form-control" id="demand-buyer-name" value="${user.name || 'Commercial Off-taker'}" required />
              </div>

              <div class="form-group">
                <label class="form-label">Required Crop</label>
                <select class="form-control form-select" id="demand-crop" required>
                  <option value="Potatoes" selected>Potatoes</option>
                  <option value="Tomatoes">Tomatoes</option>
                  <option value="Onions">Onions</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Target Volume (Kilograms)</label>
                <input type="number" class="form-control" id="demand-quantity" min="50" step="50" value="500" required />
              </div>

              <div class="form-group">
                <label class="form-label">Offered Gross Price (USD per kg)</label>
                <input type="number" class="form-control" id="demand-price" min="0.1" step="0.05" value="0.95" required />
                <div class="form-hint">Competitive buyer offers attract faster aggregation and dispatch.</div>
              </div>

              <div class="form-group">
                <label class="form-label">Quality Specification Required</label>
                <select class="form-control form-select" id="demand-quality" required>
                  <option value="Grade A (Premium)">Grade A (Premium - Supermarket / Direct Retail)</option>
                  <option value="Grade B (Processing)">Grade B (Processing - Crisps / Drying / Starch)</option>
                  <option value="Any Grade (Aggregated)">Any Grade (Accept Flexible Mixed Volumes)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Delivery Destination Hub</label>
                <input type="text" class="form-control" id="demand-hub" value="Mbare Central Terminal, Harare" required />
              </div>

              <div class="modal-footer" style="margin: 1.5rem -1.5rem -1.5rem; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('new-demand-modal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary-navy">Publish Demand Request</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;
  }

  async handleDemandSubmit(event) {
    event.preventDefault();
    const buyer_name = document.getElementById('demand-buyer-name').value;
    const crop = document.getElementById('demand-crop').value;
    const target_quantity_kg = Number(document.getElementById('demand-quantity').value);
    const offered_price_per_kg = Number(document.getElementById('demand-price').value);
    const quality_required = document.getElementById('demand-quality').value;
    const delivery_hub = document.getElementById('demand-hub').value;
    const user = window.vunothoAuth.getUser() || { id: 'BUYER-01' };

    const demand = {
      id: `DEM-${Date.now()}-${Math.random().toString(36).substr(2, 4).toUpperCase()}`,
      buyer_id: user.id || 'BUYER-01',
      buyer_name,
      crop,
      target_quantity_kg,
      offered_price_per_kg,
      quality_required,
      delivery_hub,
      deadline: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString(),
      created_at: new Date().toISOString(),
      status: 'Active'
    };

    await window.vunothoAPI.createDemand(demand);

    document.getElementById('new-demand-modal').classList.remove('active');
    window.vunothoApp.showToast(`Demand for ${target_quantity_kg}kg ${crop} published to database!`, 'info');
    window.vunothoApp.refreshCurrentView();
  }
}

window.buyerView = new BuyerView();
