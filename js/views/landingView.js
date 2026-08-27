/**
 * VUNOTHO HERO LANDING PAGE VIEW (ZERO-EMOJI ENTERPRISE DESIGN)
 * Real Zimbabwe Market Analysis Pricing, Live Marketplace Board, Net-Return Calculator,
 * Continuous 4-Tier Marquee (3s Interval), Value Pipeline, and Database Authentication.
 */

class LandingView {
  constructor() {
    this.tierKeys = ['fresh', 'processing', 'feed', 'compost'];
    this.currentTierIndex = 0;
    this.selectedTier = 'fresh';
    this.marqueeTimer = null;

    // Authentic Zimbabwe Market Benchmark Data (Source: Market Analysis 27 Aug 2026)
    this.cropPrices = {
      'Tomatoes': { pricePerKg: 0.45, range: '$0.30–$0.60/kg', unit: 'kg', rejectRate: '20%–35%', packaging: '30kg Wooden Sandak' },
      'Table Potatoes': { pricePerKg: 0.55, range: '$0.40–$0.65/kg', unit: 'kg', rejectRate: '5%–10%', packaging: '15kg Mesh Pocket' },
      'Onions': { pricePerKg: 0.60, range: '$0.45–$0.80/kg', unit: 'kg', rejectRate: '8%–15%', packaging: '10kg Pocket' },
      'Leafy Greens': { pricePerKg: 0.50, range: '$0.35–$0.65/kg', unit: 'kg', rejectRate: '25%–40%', packaging: '6kg Tied Bundle' },
      'Butternut Squash': { pricePerKg: 0.40, range: '$0.30–$0.55/kg', unit: 'kg', rejectRate: '5%–10%', packaging: '10kg Pocket' },
      'Cabbages': { pricePerKg: 0.25, range: '$0.15–$0.35/kg', unit: 'kg', rejectRate: '10%–18%', packaging: 'Per Head / Bulk' },
      'Green Peppers': { pricePerKg: 0.70, range: '$0.50–$0.90/kg', unit: 'kg', rejectRate: '15%–25%', packaging: '20L Bucket / Sack' },
      'Carrots': { pricePerKg: 0.45, range: '$0.35–$0.55/kg', unit: 'kg', rejectRate: '12%–20%', packaging: '50kg Sack' },
      'Cucumbers': { pricePerKg: 0.35, range: '$0.25–$0.50/kg', unit: 'kg', rejectRate: '15%–25%', packaging: '60kg Bag' },
      'Fresh Maize': { pricePerKg: 0.25, range: '$0.18–$0.30/cob', unit: 'cob', rejectRate: '10%–20%', packaging: 'Tied Dozens' }
    };
  }

  async render(container) {
    const iconSprout = window.vunothoIcons.get('sprout');
    const iconBuilding = window.vunothoIcons.get('building');
    const iconTruck = window.vunothoIcons.get('truck');
    const iconCalculator = window.vunothoIcons.get('calculator');
    const iconRecycle = window.vunothoIcons.get('recycle');
    const iconWallet = window.vunothoIcons.get('wallet');
    const iconChart = window.vunothoIcons.get('chart');
    const iconLock = window.vunothoIcons.get('lock');

    // 1. Render Complete Layout Synchronously (0ms Paint) in Requested Sequence
    container.innerHTML = `
      <!-- ==========================================================================
           1. HERO SECTION (With Background Image, Embedded Loader & Core CTA)
           ========================================================================== -->
      <section class="landing-hero">
        <div class="landing-hero-backdrop">
          <!-- Embedded High-End Image Loader with Progress Bar (Active while photo decodes) -->
          <div class="hero-image-loader" id="hero-img-loader">
            <div class="hero-loader-progress-track">
              <div class="hero-loader-progress-bar"></div>
            </div>
            <span class="hero-loader-text">Loading agricultural visual assets...</span>
          </div>

          <img src="hero%20image.jpg" alt="Vunotho Zimbabwean Agriculture" class="landing-hero-img" id="hero-bg-img" onload="window.landingView.onHeroImageLoaded()" />
          <div class="landing-hero-overlay"></div>
        </div>

        <div class="landing-hero-content">
          <div class="hero-pill">
            ${iconSprout}
            <span>Enactus International Blueprint • Farmer-to-Market OS</span>
          </div>
          <h1 class="hero-title">
            Turning Smallholder Produce into <span class="hero-highlight">Measurable Economic Value</span>
          </h1>
          <p class="hero-desc">
            A farmer should not have to solve pricing opacity, logistics costs, and post-harvest waste alone. Vunotho coordinates the entire farmer-to-market value chain with transparent net returns, pooled transport, and 4-tier circular value recovery.
          </p>

          <div class="hero-actions">
            <button class="btn btn-primary-green" onclick="window.landingView.openAuthModal('farmer', 'register')">
              ${iconSprout} Register / Sign In as Farmer
            </button>
            <button class="btn btn-primary-navy" onclick="window.landingView.openAuthModal('buyer', 'signin')">
              ${iconBuilding} Commercial Buyer Portal
            </button>
            <button class="btn btn-orange" onclick="window.landingView.openAuthModal('transporter', 'signin')">
              ${iconTruck} Transporter Hub
            </button>
          </div>
        </div>
      </section>

      <!-- ==========================================================================
           2. LIVE CURATED MARKETPLACE BOARD (Precedes Calculator)
           ========================================================================== -->
      <section class="landing-preview-section">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
          <div>
            <div class="badge badge-teal" style="margin-bottom: 0.4rem;">Live Market Activity • Mbare, Malaleni, Sakubva</div>
            <h2 class="section-title" style="margin: 0;">Curated Marketplace Board</h2>
            <p class="section-subtitle" style="margin: 0.25rem 0 0;">
              Previewing current produce listings and buyer demand orders recorded on the Vunotho ledger.
            </p>
          </div>
          <button class="btn btn-sm btn-outline" onclick="window.landingView.openAuthModal('farmer', 'signin')">
            ${iconLock} Sign In for Full Trading Desk
          </button>
        </div>

        <div class="preview-split-grid">
          <!-- Left: Available Farmgate Harvests -->
          <div class="preview-column">
            <div class="preview-column-header">
              <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="color: var(--green-600);">${iconSprout}</span>
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--navy-900); margin: 0;">Available Farmgate Lots</h3>
              </div>
              <span class="system-pill" id="preview-listings-count" style="font-size: 0.75rem;">Loading...</span>
            </div>
            <div id="preview-listings-container" class="preview-cards-list">
              <div class="preview-loading-box">
                <div class="splash-progress-bar" style="width: 60%; animation: pulse-fill 1.2s ease-in-out infinite alternate;"></div>
                <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Connecting to agricultural ledger...</span>
              </div>
            </div>
          </div>

          <!-- Right: Active Buyer Demand Orders -->
          <div class="preview-column">
            <div class="preview-column-header">
              <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="color: var(--navy-800);">${iconBuilding}</span>
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--navy-900); margin: 0;">Commercial Demand Requests</h3>
              </div>
              <span class="system-pill" id="preview-demands-count" style="font-size: 0.75rem;">Loading...</span>
            </div>
            <div id="preview-demands-container" class="preview-cards-list">
              <div class="preview-loading-box">
                <div class="splash-progress-bar" style="width: 60%; animation: pulse-fill 1.2s ease-in-out infinite alternate;"></div>
                <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Connecting to demand orders...</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Lock Teaser Banner -->
        <div class="preview-lock-banner">
          <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="color: var(--gold-500);">${iconLock}</div>
            <div>
              <strong style="color: #ffffff; font-size: 0.9rem;">Authenticated Trading & Aggregation Desk</strong>
              <div style="color: #cbd5e1; font-size: 0.75rem;">Sign in with your verified profile to accept offers, lock orders, dispatch pooled transporters, or view verifiable receipts.</div>
            </div>
          </div>
          <button class="btn btn-sm btn-gold" onclick="window.landingView.openAuthModal('farmer', 'signin')">
            Access Portal →
          </button>
        </div>
      </section>

      <!-- ==========================================================================
           3. INTERACTIVE NET-RETURN DECISION SIMULATOR (Real Market Benchmarks)
           ========================================================================== -->
      <section class="landing-calculator-section">
        <div class="calc-header-wrapper">
          <div>
            <div class="badge badge-gold" style="margin-bottom: 0.5rem;">Section 6 Blueprint • Price Intelligence</div>
            <h2 class="section-title">Interactive Net-Return Decision Engine</h2>
            <p class="section-subtitle">
              We never tell a farmer just <em>"Price = $X"</em>. Try the interactive model below using authentic Zimbabwean market wholesale prices (Harare, Bulawayo, Mutare) to see how pooled logistics protects profit.
            </p>
          </div>
        </div>

        <div class="calculator-card-grid">
          <!-- Control Sliders -->
          <div class="calc-control-panel">
            <div class="form-group">
              <label class="form-label" for="calc-crop-select">Select Commodity & Benchmark Price</label>
              <select id="calc-crop-select" class="form-control" onchange="window.landingView.updateCalculator()">
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
                <span class="calc-val-badge" id="calc-weight-val">400 kg</span>
              </div>
              <input type="range" id="calc-weight-slider" min="50" max="2500" step="25" value="400" class="calc-slider" oninput="window.landingView.updateCalculator()" />
              <div class="calc-range-bounds"><span>50 kg (Micro-lot)</span><span>2,500 kg (Full Cluster Manifest)</span></div>
            </div>

            <div class="form-group">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                <label class="form-label" style="margin-bottom: 0;">Distance to Buyer / Market Hub</label>
                <span class="calc-val-badge" id="calc-dist-val">35 km</span>
              </div>
              <input type="range" id="calc-dist-slider" min="10" max="120" step="5" value="35" class="calc-slider" oninput="window.landingView.updateCalculator()" />
              <div class="calc-range-bounds"><span>10 km (Peri-Urban)</span><span>120 km (Regional Wholesale Terminal)</span></div>
            </div>
          </div>

          <!-- Formula & Outcome Result Cards -->
          <div class="calc-result-panel">
            <div class="calc-equation-display">
              <div class="eq-item">
                <span class="eq-label">Gross Value</span>
                <span class="eq-amount gross" id="calc-gross-amt">$180.00</span>
              </div>
              <span class="eq-operator">−</span>
              <div class="eq-item">
                <span class="eq-label">Pooled Transport</span>
                <span class="eq-amount cost" id="calc-transport-amt">$13.65</span>
              </div>
              <span class="eq-operator">−</span>
              <div class="eq-item">
                <span class="eq-label">Platform Fee (4%)</span>
                <span class="eq-amount fee" id="calc-fee-amt">$7.20</span>
              </div>
              <span class="eq-operator">=</span>
              <div class="eq-item highlight-net">
                <span class="eq-label">Net Take-Home</span>
                <span class="eq-amount net" id="calc-net-amt">$159.15</span>
              </div>
            </div>

            <div class="calc-comparison-banner">
              <div style="display: flex; align-items: center; gap: 0.6rem;">
                <div class="pillar-icon-box orange" style="width: 32px; height: 32px; font-size: 0.85rem;">${iconTruck}</div>
                <div>
                  <div style="font-size: 0.85rem; font-weight: 800; color: var(--navy-900);">
                    Pooled Aggregation Savings: <span style="color: var(--green-600);" id="calc-savings-amt">$7.35 saved</span>
                  </div>
                  <div style="font-size: 0.75rem; color: var(--text-muted);">
                    Solo hire transport would cost <strong id="calc-solo-amt">$21.00</strong>. Vunotho clusters save 35% on freight and bypass middleman fees.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ==========================================================================
           4. 4-TIER CIRCULAR VALUE-RECOVERY ENGINE (Continuous 3s Auto-Marquee)
           ========================================================================== -->
      <section class="landing-tier-section">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
          <div>
            <div class="badge badge-green" style="margin-bottom: 0.4rem;">Section 8 Blueprint • Eliminating 20%–40% Harvest Losses</div>
            <h2 class="section-title" style="margin: 0;">4-Tier Circular Value Recovery Engine</h2>
            <p class="section-subtitle" style="margin: 0.25rem 0 0;">
              Traditional supply chains discard 20%–35% of tomatoes and leafy greens as rejects. Vunotho rotates every lot down an automatic economic recovery waterfall.
            </p>
          </div>
          <div class="marquee-control-bar">
            <span class="marquee-status-pill running">
              <span>⚡</span> <span>Continuous Auto-Rotation (3s)</span>
            </span>
          </div>
        </div>

        <div class="tier-interactive-grid">
          <!-- Left: 4 Tier Selectors with Continuous 3-Second Countdown Progress -->
          <div class="tier-buttons-col">
            <div class="tier-selector-card active" id="tier-btn-fresh" onclick="window.landingView.selectTier('fresh', true)">
              <div class="tier-badge-pill tier-1">Tier 1 • 100% Value</div>
              <h4 style="margin: 0.35rem 0 0.15rem; font-size: 0.95rem; color: var(--navy-900);">Fresh-Market Primary</h4>
              <div style="font-size: 0.75rem; color: var(--text-muted);">Grade-A supermarket & wholesale spec ($0.30–$0.65/kg).</div>
              <div class="tier-timer-track"><div class="tier-timer-progress animating" id="timer-progress-fresh"></div></div>
            </div>

            <div class="tier-selector-card" id="tier-btn-processing" onclick="window.landingView.selectTier('processing', true)">
              <div class="tier-badge-pill tier-2">Tier 2 • 75% Value</div>
              <h4 style="margin: 0.35rem 0 0.15rem; font-size: 0.95rem; color: var(--navy-900);">Value-Added Agro-Processing</h4>
              <div style="font-size: 0.75rem; color: var(--text-muted);">Converts blemished & small lots into crisps, flour & starch.</div>
              <div class="tier-timer-track"><div class="tier-timer-progress" id="timer-progress-processing"></div></div>
            </div>

            <div class="tier-selector-card" id="tier-btn-feed" onclick="window.landingView.selectTier('feed', true)">
              <div class="tier-badge-pill tier-3">Tier 3 • 35% Value</div>
              <h4 style="margin: 0.35rem 0 0.15rem; font-size: 0.95rem; color: var(--navy-900);">Livestock & Animal Feed</h4>
              <div style="font-size: 0.75rem; color: var(--text-muted);">Sub-grade lots & clean peels routed to local pig & cattle feed.</div>
              <div class="tier-timer-track"><div class="tier-timer-progress" id="timer-progress-feed"></div></div>
            </div>

            <div class="tier-selector-card" id="tier-btn-compost" onclick="window.landingView.selectTier('compost', true)">
              <div class="tier-badge-pill tier-4">Tier 4 • 15% Value</div>
              <h4 style="margin: 0.35rem 0 0.15rem; font-size: 0.95rem; color: var(--navy-900);">Organic Bio-Compost</h4>
              <div style="font-size: 0.75rem; color: var(--text-muted);">Residual organic biomass regenerated into soil bio-fertilizer.</div>
              <div class="tier-timer-track"><div class="tier-timer-progress" id="timer-progress-compost"></div></div>
            </div>
          </div>

          <!-- Right: Dynamic Tier Detail Display Card -->
          <div class="tier-detail-card" id="tier-detail-display">
            <!-- Populated dynamically by selectTier() -->
          </div>
        </div>
      </section>

      <!-- ==========================================================================
           5. THE 7-STAGE TRANSFORMATION VALUE PIPELINE STRIP
           ========================================================================== -->
      <section class="transformation-strip">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--navy-900);">The Vunotho Unified Value Pipeline</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted);">One platform coordinating every stage from farmgate to final payout.</p>
          </div>
          <span class="badge badge-gold">Blueprint Section 21</span>
        </div>

        <div class="trans-flow-steps">
          <div class="trans-step-box" style="border-left: 3px solid var(--green-600);">
            <div class="trans-step-icon" style="color: var(--green-600);">${iconSprout}</div>
            <div class="trans-step-title">1. Produce</div>
            <div style="font-size: 0.65rem; color: var(--text-muted);">Harvest Listing</div>
          </div>

          <div class="trans-step-box" style="border-left: 3px solid var(--gold-500);">
            <div class="trans-step-icon" style="color: var(--gold-600);">${iconCalculator}</div>
            <div class="trans-step-title">2. Price</div>
            <div style="font-size: 0.65rem; color: var(--text-muted);">Net-Return IQ</div>
          </div>

          <div class="trans-step-box" style="border-left: 3px solid var(--teal-600);">
            <div class="trans-step-icon" style="color: var(--teal-600);">${iconBuilding}</div>
            <div class="trans-step-title">3. Buyer</div>
            <div style="font-size: 0.65rem; color: var(--text-muted);">Match Demand</div>
          </div>

          <div class="trans-step-box" style="border-left: 3px solid var(--orange-600);">
            <div class="trans-step-icon" style="color: var(--orange-600);">${iconTruck}</div>
            <div class="trans-step-title">4. Transport</div>
            <div style="font-size: 0.65rem; color: var(--text-muted);">Pooled Loads</div>
          </div>

          <div class="trans-step-box" style="border-left: 3px solid var(--green-600);">
            <div class="trans-step-icon" style="color: var(--green-600);">${iconRecycle}</div>
            <div class="trans-step-title">5. Processing</div>
            <div style="font-size: 0.65rem; color: var(--text-muted);">Value Recovery</div>
          </div>

          <div class="trans-step-box" style="border-left: 3px solid var(--gold-500);">
            <div class="trans-step-icon" style="color: var(--gold-600);">${iconWallet}</div>
            <div class="trans-step-title">6. Payment</div>
            <div style="font-size: 0.65rem; color: var(--text-muted);">Digital Settlement</div>
          </div>

          <div class="trans-step-box" style="border-left: 3px solid var(--teal-600);">
            <div class="trans-step-icon" style="color: var(--teal-600);">${iconChart}</div>
            <div class="trans-step-title">7. Impact</div>
            <div style="font-size: 0.65rem; color: var(--text-muted);">Enactus KPIs</div>
          </div>
        </div>
      </section>

      <!-- ==========================================================================
           6. 3 CORE INNOVATION PILLARS
           ========================================================================== -->
      <section class="pillars-grid">
        <!-- Pillar 1 -->
        <div class="pillar-card p-price">
          <div class="pillar-icon-box gold">${iconCalculator}</div>
          <h3 class="h3-title" style="margin-bottom: 0.5rem;">Transparent Net Returns</h3>
          <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
            We never tell a farmer just "Price = $X". We compute and display:
            <strong style="color: var(--navy-900); display: block; margin-top: 0.4rem;">Gross Price - Transport Cost - Fee = Net Return</strong>
            bypassing the 5%–10% middleman extortion fees at urban market gates.
          </p>
        </div>

        <!-- Pillar 2 -->
        <div class="pillar-card p-logistics">
          <div class="pillar-icon-box orange">${iconTruck}</div>
          <h3 class="h3-title" style="margin-bottom: 0.5rem;">Pooled Route Logistics</h3>
          <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
            Small individual lots (50kg–300kg) are uneconomical to transport solo. Vunotho clusters nearby smallholders into consolidated 2.5T truck manifests, reducing freight costs by <strong>35%</strong>.
          </p>
        </div>

        <!-- Pillar 3 -->
        <div class="pillar-card p-circular">
          <div class="pillar-icon-box green">${iconRecycle}</div>
          <h3 class="h3-title" style="margin-bottom: 0.5rem;">Circular Value Recovery</h3>
          <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
            Moving beyond binary 'good vs bad' produce grading. Cosmetically imperfect or surplus crops are routed to value-added processing (crisps, flour), animal feed, or bio-compost.
          </p>
        </div>
      </section>

      <!-- ==========================================================================
           7. DATABASE AUTHENTICATION MODAL (With Zimbabwe's 10 Provinces)
           ========================================================================== -->
      <div id="auth-modal" class="modal-backdrop">
        <div class="modal-box" style="max-width: 480px;">
          <div class="modal-header">
            <div style="display: flex; align-items: center; gap: 0.6rem;">
              <div class="brand-logo" style="width: 32px; height: 32px; font-size: 1rem;">V</div>
              <h3 class="h3-title" style="margin: 0;" id="auth-modal-title">Account Access</h3>
            </div>
            <button class="modal-close-btn" onclick="document.getElementById('auth-modal').classList.remove('active')">✕</button>
          </div>
          <div class="modal-body">
            <!-- Inline Error Banner -->
            <div id="auth-modal-error" class="auth-error-banner" style="display: none;"></div>

            <!-- Mode Switcher: Sign In vs Create Account -->
            <div class="filter-tabs" style="margin-bottom: 1.25rem;">
              <div class="filter-tab-item active" id="auth-mode-signin" onclick="window.landingView.setAuthMode('signin')">Sign In</div>
              <div class="filter-tab-item" id="auth-mode-register" onclick="window.landingView.setAuthMode('register')">Create Account</div>
            </div>

            <!-- Role Selector -->
            <div class="form-group" style="margin-bottom: 1rem;">
              <label class="form-label">Select Account Type</label>
              <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                <button type="button" class="btn btn-sm btn-outline role-select-btn active" id="btn-role-farmer" onclick="window.landingView.selectRole('farmer')">Farmer</button>
                <button type="button" class="btn btn-sm btn-outline role-select-btn" id="btn-role-buyer" onclick="window.landingView.selectRole('buyer')">Buyer</button>
                <button type="button" class="btn btn-sm btn-outline role-select-btn" id="btn-role-transporter" onclick="window.landingView.selectRole('transporter')">Transporter</button>
              </div>
            </div>

            <!-- Main Auth Form -->
            <form id="auth-user-form" onsubmit="window.landingView.handleAuthSubmit(event)">
              <!-- Name Field -->
              <div class="form-group" id="reg-name-group" style="display: none;">
                <label class="form-label" id="lbl-auth-name">Full Name</label>
                <input type="text" class="form-control" id="auth-name" placeholder="e.g. Sipho Moyo or Arnold Dinga" />
              </div>

              <!-- Organisation / Farm Name Field -->
              <div class="form-group" id="reg-org-group" style="display: none;">
                <label class="form-label" id="lbl-auth-org">Farm / Organisation Name (Optional)</label>
                <input type="text" class="form-control" id="auth-org" placeholder="e.g. Green Tomatoes or Fresh Inn" />
              </div>

              <!-- Contact Phone/Email -->
              <div class="form-group">
                <label class="form-label" id="lbl-auth-email-phone">Phone Number or Email Address</label>
                <input type="text" class="form-control" id="auth-email-phone" placeholder="e.g. 0773878836 or email@domain.com" required />
              </div>

              <!-- Zimbabwe's 10 Provinces Dropdown -->
              <div class="form-group" id="reg-province-group" style="display: none;">
                <label class="form-label" id="lbl-auth-province">Operating Province</label>
                <select class="form-control" id="auth-province">
                  <option value="Manicaland" selected>Manicaland (Mutare, Nyanga, Chipinge)</option>
                  <option value="Matabeleland South">Matabeleland South (Gwanda, Beitbridge, Maphisa)</option>
                  <option value="Matabeleland North">Matabeleland North (Lupane, Hwange, Nkayi)</option>
                  <option value="Masvingo">Masvingo (Masvingo, Chiredzi, Zaka)</option>
                  <option value="Midlands">Midlands (Gweru, Kwekwe, Mberengwa, Mataga)</option>
                  <option value="Mashonaland East">Mashonaland East (Marondera, Goromonzi, Murehwa)</option>
                  <option value="Mashonaland Central">Mashonaland Central (Bindura, Mazowe)</option>
                  <option value="Mashonaland West">Mashonaland West (Chinhoyi, Kadoma, Karoi)</option>
                  <option value="Harare">Harare (Metropolitan / National Hub)</option>
                  <option value="Bulawayo">Bulawayo (Metropolitan / Regional Hub)</option>
                </select>
              </div>

              <!-- District / Local Area -->
              <div class="form-group" id="reg-district-group" style="display: none;">
                <label class="form-label" id="lbl-auth-district">District / Farming Area</label>
                <input type="text" class="form-control" id="auth-district" placeholder="e.g. Gwanda, Masvingo, Beitbridge, Maphisa, Nyanga, Mataga, Mberengwa" value="Nyanga" />
              </div>

              <!-- Role Specific: Main Produce (Farmer) -->
              <div class="form-group" id="reg-produce-group" style="display: none;">
                <label class="form-label">Primary Crop / Main Produce</label>
                <select class="form-control" id="auth-main-produce">
                  <option value="Tomatoes" selected>Tomatoes (Round / Roma)</option>
                  <option value="Table Potatoes">Table Potatoes</option>
                  <option value="Cabbages">Cabbages</option>
                  <option value="Onions">Onions</option>
                  <option value="Leafy Greens">Leafy Greens (Tsunga / Covo / Rape)</option>
                  <option value="Butternut Squash">Butternut Squash</option>
                  <option value="Green Peppers">Green Peppers</option>
                  <option value="Carrots">Carrots</option>
                  <option value="Cucumbers">Cucumbers</option>
                  <option value="Fresh Maize">Fresh Maize (Green Mealies)</option>
                  <option value="Other Produce">Other Produce</option>
                </select>
              </div>

              <!-- Role Specific: Sourcing Focus (Buyer) -->
              <div class="form-group" id="reg-sourcing-group" style="display: none;">
                <label class="form-label">Primary Sourcing Requirement</label>
                <select class="form-control" id="auth-sourcing-focus">
                  <option value="Supermarket Retail" selected>Supermarket & Retail Produce</option>
                  <option value="Value-Added Processing">Value-Added Processing (Crisps / Starch / Flour)</option>
                  <option value="Wholesale Aggregation">Wholesale Market Aggregation</option>
                  <option value="Institutional Catering">Institutional Catering & Hospitality</option>
                </select>
              </div>

              <!-- Role Specific: Vehicle Type (Transporter) -->
              <div class="form-group" id="reg-vehicle-group" style="display: none;">
                <label class="form-label">Vehicle Type & Freight Capacity</label>
                <select class="form-control" id="auth-vehicle-type">
                  <option value="2.5T Rural Truck" selected>2.5 Tonne Rural Light Truck (Vunotho Standard Cluster)</option>
                  <option value="1.0T Pickup">1.0 Tonne Bakkie / Light Pickup</option>
                  <option value="5.0T Canter">5.0 Tonne Rigid Canter</option>
                  <option value="10.0T+ Heavy Freight">10.0+ Tonne Heavy Freight Truck</option>
                </select>
              </div>

              <!-- Password -->
              <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" id="auth-pwd" placeholder="Enter secure password (e.g. wish2026)" required />
              </div>

              <div class="modal-footer" style="margin: 1.5rem -1.5rem -1.5rem; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('auth-modal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary-navy" id="auth-submit-action-btn">Sign In</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;

    // 2. Initialize Calculator, 4-Tier Display, Image Loader, and start continuous Marquee Auto-Cycle
    this.updateCalculator();
    this.selectTier('fresh', false);
    this.startMarqueeTimer();
    this.initHeroImage();

    // 3. Asynchronously load database preview records in the background
    this.loadMarketplacePreviews();
  }

  // ==========================================================================
  // HERO IMAGE SMOOTH TRANSITION
  // ==========================================================================
  onHeroImageLoaded() {
    const img = document.getElementById('hero-bg-img');
    const loader = document.getElementById('hero-img-loader');
    if (img) img.classList.add('loaded');
    if (loader) {
      loader.classList.add('fade-out');
      setTimeout(() => {
        if (loader) loader.style.display = 'none';
      }, 600);
    }
  }

  initHeroImage() {
    const img = document.getElementById('hero-bg-img');
    if (img && img.complete) {
      this.onHeroImageLoaded();
    }
  }

  // ==========================================================================
  // 4-TIER CONTINUOUS MARQUEE CYCLE & PROGRESSION (3 SECONDS)
  // ==========================================================================
  startMarqueeTimer() {
    this.stopMarqueeTimer();
    this.marqueeTimer = setInterval(() => {
      this.currentTierIndex = (this.currentTierIndex + 1) % this.tierKeys.length;
      this.selectTier(this.tierKeys[this.currentTierIndex], false);
    }, 3000);
  }

  stopMarqueeTimer() {
    if (this.marqueeTimer) {
      clearInterval(this.marqueeTimer);
      this.marqueeTimer = null;
    }
  }

  triggerProgressAnimation(tierKey) {
    document.querySelectorAll('.tier-timer-progress').forEach(el => {
      el.classList.remove('animating');
      el.style.width = '0%';
    });

    const activeBar = document.getElementById(`timer-progress-${tierKey}`);
    if (activeBar) {
      void activeBar.offsetWidth; // Reflow to restart animation
      activeBar.classList.add('animating');
    }
  }

  selectTier(tierKey, isManual = false) {
    if (isManual) {
      const idx = this.tierKeys.indexOf(tierKey);
      if (idx !== -1) this.currentTierIndex = idx;
      // Restart the 3s interval so it continues seamlessly from the clicked tier
      this.startMarqueeTimer();
    }

    this.selectedTier = tierKey;

    document.querySelectorAll('.tier-selector-card').forEach(c => c.classList.remove('active'));
    const targetBtn = document.getElementById(`tier-btn-${tierKey}`);
    if (targetBtn) targetBtn.classList.add('active');

    this.triggerProgressAnimation(tierKey);

    const detailContainer = document.getElementById('tier-detail-display');
    if (!detailContainer) return;

    const tierData = {
      fresh: {
        title: 'Tier 1: Fresh-Market Primary',
        badge: 'Grade A Commercial Spec • 100% Value Recovery',
        badgeClass: 'badge-green',
        desc: 'Direct farmgate pickup for pristine, undamaged produce meeting commercial supermarket and wholesale criteria ($0.30–$0.65/kg). Rigid plastic crate transport eliminates 40%–50% of transit bruising.',
        destinations: 'Mbare Musika (Harare), Malaleni / 5th Ave (Bulawayo), Sakubva (Mutare), Supermarket chains.',
        economicModel: 'Full wholesale price paid directly to farmer via EcoCash / Mobile Money settlement.',
        outputProducts: ['Supermarket Crate Stock', 'Wholesale Bulk Pallets', 'Table-Ready Grade A Produce', 'Hospitality Lots']
      },
      processing: {
        title: 'Tier 2: Value-Added Agro-Processing',
        badge: 'Surplus & Small/Blemished • 75% Value Recovery',
        badgeClass: 'badge-gold',
        desc: 'Undersized, oversized, or cosmetically imperfect produce (which accounts for 20%–35% of tomato & potato harvests) routed to Vunotho value-addition facilities rather than dumped as waste.',
        destinations: 'Vunotho food processing hubs, local crisp makers, starch mills, and solar dehydration units.',
        economicModel: 'Converts perishable produce into shelf-stable packaged foods with 6–12 month shelf life.',
        outputProducts: ['Vunotho Artisan Potato Crisps', 'Refined Potato Starch', 'Gluten-Free Potato Flour', 'Sun-Dried Tomato Paste']
      },
      feed: {
        title: 'Tier 3: Livestock & Animal Feed',
        badge: 'Sub-grade & Processing Peels • 35% Value Recovery',
        badgeClass: 'badge-orange',
        desc: 'Sub-grade harvest lots, non-human-consumption blemishes, and clean peeling residues channeled directly to local smallholder livestock producers to slash feed costs.',
        destinations: 'Community pig farms, dairy cattle cooperatives, poultry feed compounders.',
        economicModel: 'Recovers 35% economic value while substantially lowering feed expenses for local livestock keepers.',
        outputProducts: ['Nutritious Pig Swill & Mash', 'Fermented Cattle Biomass Feed', 'High-Calorie Animal Rations']
      },
      compost: {
        title: 'Tier 4: Organic Bio-Compost & Soil Nutrients',
        badge: 'Degraded Biomass • 15% Value Retention',
        badgeClass: 'badge-teal',
        desc: 'Degraded, spoiled, or non-feed organic residue processed through aerobic composting, returning vital nitrogen, phosphorus, and organic matter to smallholder soils.',
        destinations: 'Smallholder farm plots, community seedling nurseries, organic vegetable gardens.',
        economicModel: 'Circular regenerative agriculture eliminating synthetic fertilizer costs for the next planting cycle.',
        outputProducts: ['Nitrogen-Rich Bio-Fertilizer', 'Organic Soil Conditioner', 'Nursery Seedling Compost Mix']
      }
    };

    const cur = tierData[tierKey] || tierData.fresh;

    detailContainer.innerHTML = `
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--navy-900); margin: 0;">${cur.title}</h3>
        <span class="badge ${cur.badgeClass}">${cur.badge}</span>
      </div>
      <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 1.25rem;">
        ${cur.desc}
      </p>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
        <div style="background: #ffffff; padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
          <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Target Channels</div>
          <div style="font-size: 0.85rem; font-weight: 700; color: var(--navy-900); margin-top: 0.25rem;">${cur.destinations}</div>
        </div>
        <div style="background: #ffffff; padding: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
          <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Circular Mechanism</div>
          <div style="font-size: 0.85rem; font-weight: 700; color: var(--navy-900); margin-top: 0.25rem;">${cur.economicModel}</div>
        </div>
      </div>

      <div>
        <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 0.4rem;">Derived Outputs & Products</div>
        <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
          ${cur.outputProducts.map(p => `<span class="system-pill" style="font-size: 0.75rem; background: #ffffff; border: 1px solid var(--border-color); color: var(--navy-800);">${p}</span>`).join('')}
        </div>
      </div>
    `;
  }

  // ==========================================================================
  // DECISION ENGINE & NET-RETURN CALCULATOR
  // ==========================================================================
  updateCalculator() {
    const cropSelect = document.getElementById('calc-crop-select');
    const weightSlider = document.getElementById('calc-weight-slider');
    const distSlider = document.getElementById('calc-dist-slider');

    if (!cropSelect || !weightSlider || !distSlider) return;

    const crop = cropSelect.value;
    const weightKg = Number(weightSlider.value);
    const distKm = Number(distSlider.value);

    // Update slider labels
    const weightVal = document.getElementById('calc-weight-val');
    const distVal = document.getElementById('calc-dist-val');
    if (weightVal) weightVal.textContent = `${weightKg.toLocaleString()} kg`;
    if (distVal) distVal.textContent = `${distKm} km`;

    const unitPrice = (this.cropPrices[crop] && this.cropPrices[crop].pricePerKg) || 0.45;
    const breakdown = window.vunothoPricing ? window.vunothoPricing.calculateNetReturn(unitPrice, weightKg, distKm, true) : {
      grossTotal: Number((unitPrice * weightKg).toFixed(2)),
      transportTotal: Number((weightKg * distKm * 0.0015 * 0.65).toFixed(2)),
      platformFeeTotal: Number((unitPrice * weightKg * 0.04).toFixed(2)),
      netTotal: Number((unitPrice * weightKg * 0.96 - weightKg * distKm * 0.0015 * 0.65).toFixed(2)),
      transportSavings: Number((weightKg * distKm * 0.0015 * 0.35).toFixed(2))
    };

    const soloTransport = Number((breakdown.transportTotal + breakdown.transportSavings).toFixed(2));

    const grossAmt = document.getElementById('calc-gross-amt');
    const transportAmt = document.getElementById('calc-transport-amt');
    const feeAmt = document.getElementById('calc-fee-amt');
    const netAmt = document.getElementById('calc-net-amt');
    const savingsAmt = document.getElementById('calc-savings-amt');
    const soloAmt = document.getElementById('calc-solo-amt');

    if (grossAmt) grossAmt.textContent = `$${breakdown.grossTotal.toFixed(2)}`;
    if (transportAmt) transportAmt.textContent = `$${breakdown.transportTotal.toFixed(2)}`;
    if (feeAmt) feeAmt.textContent = `$${breakdown.platformFeeTotal.toFixed(2)}`;
    if (netAmt) netAmt.textContent = `$${breakdown.netTotal.toFixed(2)}`;
    if (savingsAmt) savingsAmt.textContent = `$${breakdown.transportSavings.toFixed(2)} saved`;
    if (soloAmt) soloAmt.textContent = `$${soloTransport.toFixed(2)}`;
  }

  // ==========================================================================
  // ASYNC LIVE MARKETPLACE PREVIEWS
  // ==========================================================================
  async loadMarketplacePreviews() {
    const iconSprout = window.vunothoIcons.get('sprout');
    const iconBuilding = window.vunothoIcons.get('building');
    const iconBox = window.vunothoIcons.get('box');
    const iconMapPin = window.vunothoIcons.get('mapPin');

    let listings = [];
    let demands = [];

    try {
      if (window.vunothoAPI) {
        listings = (await window.vunothoAPI.getListings()) || [];
        demands = (await window.vunothoAPI.getDemands()) || [];
      } else if (window.vunothoDB) {
        listings = (await window.vunothoDB.getAll('listings')) || [];
        demands = (await window.vunothoDB.getAll('demands')) || [];
      }
    } catch (e) {
      console.warn('Marketplace preview fallback to local cache:', e);
    }

    const previewListings = Array.isArray(listings) ? listings.slice(0, 3) : [];
    const previewDemands = Array.isArray(demands) ? demands.slice(0, 3) : [];

    const listCountBadge = document.getElementById('preview-listings-count');
    const demandCountBadge = document.getElementById('preview-demands-count');
    const listContainer = document.getElementById('preview-listings-container');
    const demandContainer = document.getElementById('preview-demands-container');

    if (listCountBadge) listCountBadge.textContent = `${listings.length} Active Lots`;
    if (demandCountBadge) demandCountBadge.textContent = `${demands.length} Open Orders`;

    if (listContainer) {
      if (previewListings.length > 0) {
        listContainer.innerHTML = previewListings.map(item => `
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
              <span style="color: var(--navy-800); font-weight: 700;">${Number(item.quantity_kg || 0).toLocaleString()} kg available</span>
              <span class="status-indicator ${item.sync_status === 'Saved Offline' ? 'offline' : 'synced'}" style="font-size: 0.7rem;">${item.sync_status || 'Synced'}</span>
            </div>
          </div>
        `).join('');
      } else {
        listContainer.innerHTML = `
          <div class="preview-empty-box">
            <div style="color: var(--text-muted); margin-bottom: 0.5rem;">${iconBox}</div>
            <div style="font-weight: 700; font-size: 0.85rem; color: var(--navy-900);">No Open Harvest Listings Yet</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Are you a farmer? Sign in to list your first harvest lot for buyer matching.</div>
            <button class="btn btn-sm btn-primary-green" style="margin-top: 0.75rem;" onclick="window.landingView.openAuthModal('farmer', 'register')">
              List Harvest Lot
            </button>
          </div>
        `;
      }
    }

    if (demandContainer) {
      if (previewDemands.length > 0) {
        demandContainer.innerHTML = previewDemands.map(item => `
          <div class="preview-item-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.35rem;">
              <div>
                <strong style="font-size: 0.95rem; color: var(--navy-900);">${item.crop || 'Produce'} Demand</strong>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem;">
                  Target Destination: <strong>${item.district || 'Harare'}</strong>
                </div>
              </div>
              <span class="badge badge-gold">$${Number(item.offered_price_per_kg || 0).toFixed(2)}/kg</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; font-size: 0.8rem; border-top: 1px dashed var(--border-color); padding-top: 0.4rem;">
              <span style="color: var(--navy-800); font-weight: 700;">Target: ${Number(item.target_quantity_kg || 0).toLocaleString()} kg</span>
              <span class="badge badge-teal" style="font-size: 0.7rem;">${item.quality_tier || 'Grade A'}</span>
            </div>
          </div>
        `).join('');
      } else {
        demandContainer.innerHTML = `
          <div class="preview-empty-box">
            <div style="color: var(--text-muted); margin-bottom: 0.5rem;">${iconBuilding}</div>
            <div style="font-weight: 700; font-size: 0.85rem; color: var(--navy-900);">No Open Procurement Demands Yet</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Commercial off-takers can post bulk crop requirements and quality specs.</div>
            <button class="btn btn-sm btn-primary-navy" style="margin-top: 0.75rem;" onclick="window.landingView.openAuthModal('buyer', 'signin')">
              Post Buyer Demand
            </button>
          </div>
        `;
      }
    }
  }

  // ==========================================================================
  // AUTHENTICATION MODAL MANAGEMENT
  // ==========================================================================
  showAuthError(htmlMessage) {
    const errorBox = document.getElementById('auth-modal-error');
    if (errorBox) {
      errorBox.innerHTML = htmlMessage;
      errorBox.style.display = 'flex';
    }
  }

  hideAuthError() {
    const errorBox = document.getElementById('auth-modal-error');
    if (errorBox) {
      errorBox.innerHTML = '';
      errorBox.style.display = 'none';
    }
  }

  openAuthModal(role = 'farmer', mode = 'signin') {
    this.hideAuthError();
    this.selectRole(role);
    this.setAuthMode(mode);
    const modal = document.getElementById('auth-modal');
    if (modal) modal.classList.add('active');
  }

  setAuthMode(mode) {
    this.hideAuthError();
    const signinTab = document.getElementById('auth-mode-signin');
    const registerTab = document.getElementById('auth-mode-register');
    const submitBtn = document.getElementById('auth-submit-action-btn');
    const title = document.getElementById('auth-modal-title');

    const role = this.getSelectedRole();

    if (mode === 'register') {
      if (signinTab) signinTab.classList.remove('active');
      if (registerTab) registerTab.classList.add('active');
      if (submitBtn) {
        submitBtn.textContent = 'Create Database Account';
        submitBtn.className = role === 'farmer' ? 'btn btn-primary-green' : (role === 'buyer' ? 'btn btn-primary-navy' : 'btn btn-orange');
      }
      if (title) title.textContent = `Register as ${role.charAt(0).toUpperCase() + role.slice(1)}`;
      this.updateRegistrationFormFields(role, true);
    } else {
      if (registerTab) registerTab.classList.remove('active');
      if (signinTab) signinTab.classList.add('active');
      if (submitBtn) {
        submitBtn.textContent = 'Sign In';
        submitBtn.className = 'btn btn-primary-navy';
      }
      if (title) title.textContent = 'Sign In to Vunotho';
      this.updateRegistrationFormFields(role, false);
    }
  }

  selectRole(role) {
    document.querySelectorAll('.role-select-btn').forEach(btn => btn.classList.remove('active', 'btn-primary-green', 'btn-primary-navy', 'btn-orange'));
    const targetBtn = document.getElementById(`btn-role-${role}`);
    if (targetBtn) {
      targetBtn.classList.add('active');
      if (role === 'farmer') targetBtn.classList.add('btn-primary-green');
      else if (role === 'buyer') targetBtn.classList.add('btn-primary-navy');
      else if (role === 'transporter') targetBtn.classList.add('btn-orange');
    }

    const isRegister = this.isRegisterMode();
    const submitBtn = document.getElementById('auth-submit-action-btn');
    const title = document.getElementById('auth-modal-title');

    if (isRegister) {
      if (submitBtn) {
        submitBtn.className = role === 'farmer' ? 'btn btn-primary-green' : (role === 'buyer' ? 'btn btn-primary-navy' : 'btn btn-orange');
      }
      if (title) {
        title.textContent = `Register as ${role.charAt(0).toUpperCase() + role.slice(1)}`;
      }
    }
    this.updateRegistrationFormFields(role, isRegister);
  }

  updateRegistrationFormFields(role, isRegister) {
    const nameGroup = document.getElementById('reg-name-group');
    const orgGroup = document.getElementById('reg-org-group');
    const provinceGroup = document.getElementById('reg-province-group');
    const districtGroup = document.getElementById('reg-district-group');
    const produceGroup = document.getElementById('reg-produce-group');
    const sourcingGroup = document.getElementById('reg-sourcing-group');
    const vehicleGroup = document.getElementById('reg-vehicle-group');

    const lblName = document.getElementById('lbl-auth-name');
    const inputName = document.getElementById('auth-name');
    const lblOrg = document.getElementById('lbl-auth-org');
    const inputOrg = document.getElementById('auth-org');
    const lblProvince = document.getElementById('lbl-auth-province');
    const lblDistrict = document.getElementById('lbl-auth-district');
    const inputDistrict = document.getElementById('auth-district');

    if (!isRegister) {
      if (nameGroup) nameGroup.style.display = 'none';
      if (orgGroup) orgGroup.style.display = 'none';
      if (provinceGroup) provinceGroup.style.display = 'none';
      if (districtGroup) districtGroup.style.display = 'none';
      if (produceGroup) produceGroup.style.display = 'none';
      if (sourcingGroup) sourcingGroup.style.display = 'none';
      if (vehicleGroup) vehicleGroup.style.display = 'none';
      return;
    }

    // Always visible in register mode
    if (nameGroup) nameGroup.style.display = 'block';
    if (orgGroup) orgGroup.style.display = 'block';
    if (provinceGroup) provinceGroup.style.display = 'block';
    if (districtGroup) districtGroup.style.display = 'block';

    if (role === 'farmer') {
      if (lblName) lblName.textContent = 'Full Name';
      if (inputName) inputName.placeholder = 'e.g. Sipho Moyo or Arnold Dinga';
      if (lblOrg) lblOrg.textContent = 'Farm / Organisation Name (Optional)';
      if (inputOrg) inputOrg.placeholder = 'e.g. Green Tomatoes or Fresh Inn';
      if (lblProvince) lblProvince.textContent = 'Farming Province';
      if (lblDistrict) lblDistrict.textContent = 'District / Farming Area';
      if (inputDistrict) inputDistrict.placeholder = 'e.g. Gwanda, Masvingo, Beitbridge, Maphisa, Nyanga, Mataga';
      if (produceGroup) produceGroup.style.display = 'block';
      if (sourcingGroup) sourcingGroup.style.display = 'none';
      if (vehicleGroup) vehicleGroup.style.display = 'none';
    } else if (role === 'buyer') {
      if (lblName) lblName.textContent = 'Contact Person Full Name';
      if (inputName) inputName.placeholder = 'e.g. Tendai Shumba';
      if (lblOrg) lblOrg.textContent = 'Company / Business Name';
      if (inputOrg) inputOrg.placeholder = 'e.g. Fresh Inn Wholesalers or Harare Food Processors';
      if (lblProvince) lblProvince.textContent = 'Main Operating Province';
      if (lblDistrict) lblDistrict.textContent = 'City / Commercial Hub District';
      if (inputDistrict) inputDistrict.placeholder = 'e.g. Harare CBD, Mbare Musika, Bulawayo Belmont, Mutare';
      if (produceGroup) produceGroup.style.display = 'none';
      if (sourcingGroup) sourcingGroup.style.display = 'block';
      if (vehicleGroup) vehicleGroup.style.display = 'none';
    } else if (role === 'transporter') {
      if (lblName) lblName.textContent = 'Driver / Fleet Operator Name';
      if (inputName) inputName.placeholder = 'e.g. Simba Logistics or Elvis Nyanga';
      if (lblOrg) lblOrg.textContent = 'Transport Cooperative / Firm (Optional)';
      if (inputOrg) inputOrg.placeholder = 'e.g. Manicaland Rural Hauliers';
      if (lblProvince) lblProvince.textContent = 'Base Operating Region / Province';
      if (lblDistrict) lblDistrict.textContent = 'Base Depot / Route District';
      if (inputDistrict) inputDistrict.placeholder = 'e.g. Masvingo Depot, Nyanga Logistics Hub, Bulawayo';
      if (produceGroup) produceGroup.style.display = 'none';
      if (sourcingGroup) sourcingGroup.style.display = 'none';
      if (vehicleGroup) vehicleGroup.style.display = 'block';
    }
  }

  getSelectedRole() {
    const active = document.querySelector('.role-select-btn.active');
    if (active) {
      return active.id.replace('btn-role-', '');
    }
    return 'farmer';
  }

  isRegisterMode() {
    const regTab = document.getElementById('auth-mode-register');
    return regTab && regTab.classList.contains('active');
  }

  async handleAuthSubmit(event) {
    event.preventDefault();
    this.hideAuthError();

    const emailOrPhone = document.getElementById('auth-email-phone').value;
    const password = document.getElementById('auth-pwd').value;
    const role = this.getSelectedRole();
    const submitBtn = document.getElementById('auth-submit-action-btn');
    const originalText = submitBtn ? submitBtn.textContent : 'Submit';

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="btn-spinner"></span> Authenticating...';
    }

    try {
      if (this.isRegisterMode()) {
        const name = document.getElementById('auth-name').value;
        const organisation = document.getElementById('auth-org') ? document.getElementById('auth-org').value : '';
        const province = document.getElementById('auth-province') ? document.getElementById('auth-province').value : 'Manicaland';
        const district = document.getElementById('auth-district') ? document.getElementById('auth-district').value : 'Nyanga';
        
        let main_produce = '';
        let vehicle_type = '';

        if (role === 'farmer') {
          main_produce = document.getElementById('auth-main-produce') ? document.getElementById('auth-main-produce').value : '';
        } else if (role === 'buyer') {
          main_produce = document.getElementById('auth-sourcing-focus') ? document.getElementById('auth-sourcing-focus').value : '';
        } else if (role === 'transporter') {
          vehicle_type = document.getElementById('auth-vehicle-type') ? document.getElementById('auth-vehicle-type').value : '';
        }

        await window.vunothoAuth.register({
          name,
          organisation,
          email_or_phone: emailOrPhone,
          password,
          role,
          province,
          district,
          main_produce,
          vehicle_type
        });
      } else {
        await window.vunothoAuth.login(emailOrPhone, password, role);
      }

      // Only close modal if authentication succeeded without throwing
      const modal = document.getElementById('auth-modal');
      if (modal) modal.classList.remove('active');
    } catch (err) {
      console.warn('Auth action rejected:', err.message);

      if (err.isConflict) {
        this.showAuthError(`
          <div style="display:flex; flex-direction:column; gap:0.35rem; width:100%;">
            <span><strong>Account Already Exists:</strong> ${err.message}</span>
            <button type="button" class="btn-switch-inline" onclick="window.landingView.setAuthMode('signin'); document.getElementById('auth-pwd').focus();">
              → Click here to Sign In with your password
            </button>
          </div>
        `);
      } else if (err.isNotFound) {
        this.showAuthError(`
          <div style="display:flex; flex-direction:column; gap:0.35rem; width:100%;">
            <span><strong>Account Not Found:</strong> ${err.message}</span>
            <button type="button" class="btn-switch-inline" onclick="window.landingView.setAuthMode('register'); document.getElementById('auth-name').focus();">
              → Click here to Create a new account
            </button>
          </div>
        `);
      } else {
        this.showAuthError(`<strong>Error:</strong> ${err.message || 'Authentication request failed.'}`);
      }
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      }
    }
  }
}

window.landingView = new LandingView();
