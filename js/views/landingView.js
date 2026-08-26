/**
 * VUNOTHO HERO LANDING PAGE VIEW (ZERO-EMOJI ENTERPRISE DESIGN)
 * Visual transformation story, core value pillars, and authentic database registration/login.
 */

class LandingView {
  render(container) {
    const iconSprout = window.vunothoIcons.get('sprout');
    const iconBuilding = window.vunothoIcons.get('building');
    const iconTruck = window.vunothoIcons.get('truck');
    const iconCalculator = window.vunothoIcons.get('calculator');
    const iconRecycle = window.vunothoIcons.get('recycle');
    const iconWallet = window.vunothoIcons.get('wallet');
    const iconChart = window.vunothoIcons.get('chart');
    const iconBox = window.vunothoIcons.get('box');

    container.innerHTML = `
      <!-- Hero Section with Local Agricultural Background Image Texture -->
      <section class="landing-hero">
        <div class="landing-hero-backdrop">
          <img src="hero%20image.jpg" alt="Vunotho Local Smallholder Agriculture" class="landing-hero-img" />
          <div class="landing-hero-overlay"></div>
        </div>
        <div class="landing-hero-content">
          <div class="hero-pill">
            ${iconSprout}
            <span>Enactus International Blueprint • Agri-Logistics OS</span>
          </div>
          <h1 class="hero-title">
            Turning Smallholder Produce into <span class="hero-highlight">Measurable Economic Value</span>
          </h1>
          <p class="hero-desc">
            A farmer should not have to solve pricing opacity, logistics costs, and waste alone. Vunotho coordinates the entire farmer-to-market value chain with transparent net returns, pooled transport, and circular value recovery.
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

      <!-- Transformation Flow Strip -->
      <section class="transformation-strip">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--navy-900);">The Vunotho Unified Value Pipeline</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted);">One platform coordinating every stage from farmgate to final payout.</p>
          </div>
          <span class="badge badge-gold">End-to-End Orchestration</span>
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

      <!-- 3 Core Pillars -->
      <section class="pillars-grid">
        <!-- Pillar 1 -->
        <div class="pillar-card p-price">
          <div class="pillar-icon-box gold">${iconCalculator}</div>
          <h3 class="h3-title" style="margin-bottom: 0.5rem;">Transparent Net Returns</h3>
          <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
            We never tell a farmer just "Price = $X". We compute and display:
            <strong style="color: var(--navy-900); display: block; margin-top: 0.4rem;">Gross Price - Transport Cost - Fee = Net Return</strong>
            empowering smallholders with true financial decision-making power.
          </p>
        </div>

        <!-- Pillar 2 -->
        <div class="pillar-card p-logistics">
          <div class="pillar-icon-box orange">${iconTruck}</div>
          <h3 class="h3-title" style="margin-bottom: 0.5rem;">Pooled Route Logistics</h3>
          <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
            Small individual lots (50kg–300kg) are uneconomical to transport solo. Vunotho clusters nearby smallholders into consolidated 2.5T truck manifests, reducing transport costs by <strong>35%</strong>.
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

      <!-- Professional Authentication Modal (Database-driven, No Demo Shortcuts) -->
      <div id="auth-modal" class="modal-backdrop">
        <div class="modal-box" style="max-width: 460px;">
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
              <div class="form-group" id="reg-name-group" style="display: none;">
                <label class="form-label">Full Name / Organization Name</label>
                <input type="text" class="form-control" id="auth-name" placeholder="e.g. Tendai Moyo or Harare Wholesalers" />
              </div>

              <div class="form-group">
                <label class="form-label">Phone Number or Email Address</label>
                <input type="text" class="form-control" id="auth-email-phone" placeholder="e.g. 0787146103 or email@domain.com" required />
              </div>

              <div class="form-group" id="reg-district-group" style="display: none;">
                <label class="form-label">District / Farming Area</label>
                <input type="text" class="form-control" id="auth-district" placeholder="e.g. Nyanga, Marondera, Harare" value="Nyanga" />
              </div>

              <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" id="auth-pwd" placeholder="Enter secure password" required />
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
  }

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
    const nameGroup = document.getElementById('reg-name-group');
    const districtGroup = document.getElementById('reg-district-group');
    const submitBtn = document.getElementById('auth-submit-action-btn');
    const title = document.getElementById('auth-modal-title');

    if (mode === 'register') {
      if (signinTab) signinTab.classList.remove('active');
      if (registerTab) registerTab.classList.add('active');
      if (nameGroup) nameGroup.style.display = 'block';
      if (districtGroup) districtGroup.style.display = 'block';
      if (submitBtn) {
        submitBtn.textContent = 'Create Database Account';
        submitBtn.className = 'btn btn-primary-green';
      }
      if (title) title.textContent = 'Create New Account';
    } else {
      if (registerTab) registerTab.classList.remove('active');
      if (signinTab) signinTab.classList.add('active');
      if (nameGroup) nameGroup.style.display = 'none';
      if (districtGroup) districtGroup.style.display = 'none';
      if (submitBtn) {
        submitBtn.textContent = 'Sign In';
        submitBtn.className = 'btn btn-primary-navy';
      }
      if (title) title.textContent = 'Sign In to Vunotho';
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
        const district = document.getElementById('auth-district').value;
        await window.vunothoAuth.register(name, emailOrPhone, password, role, district);
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
