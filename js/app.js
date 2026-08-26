/**
 * VUNOTHO MAIN APPLICATION CONTROLLER (ZERO-EMOJI ENTERPRISE DESIGN)
 * Router, Role-Based Access Control (RBAC), Splash Screen, and Database Authentication
 */

class VunothoApp {
  constructor() {
    this.currentView = null;
  }

  async init() {
    console.log('Initializing Vunotho Operating System...');

    // 1. Initialize DB and Sync Engine
    await window.vunothoDB.init();
    window.vunothoSync.init();

    // 2. Splash Screen Lifecycle (3s once per session)
    this.initSplashScreen();

    // 3. Listen to Hash / Route Changes (e.g. #admin or /admin)
    window.addEventListener('hashchange', () => this.handleRouting());
    window.addEventListener('popstate', () => this.handleRouting());

    // 4. Initial Route & Auth Check
    await this.handleRouting();

    // 5. Register Service Worker if supported
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('./sw.js').catch((err) => {
        console.warn('Service Worker skipped:', err);
      });
    }

    console.log('Vunotho Platform initialized successfully.');
  }

  initSplashScreen() {
    const splash = document.getElementById('splash-screen');
    const progressBar = document.getElementById('splash-progress-bar');
    const statusText = document.getElementById('splash-status-text');
    if (!splash) return;

    const hasShown = sessionStorage.getItem('vunotho_splash_shown');
    if (hasShown) {
      splash.style.display = 'none';
      return;
    }

    sessionStorage.setItem('vunotho_splash_shown', 'true');

    const stages = [
      { pct: 20, text: 'Loading assets....', time: 100 },
      { pct: 45, text: 'Initializing offline storage engine...', time: 600 },
      { pct: 75, text: 'Connecting to agricultural ledger...', time: 1300 },
      { pct: 92, text: 'Syncing market intelligence & price models...', time: 2000 },
      { pct: 100, text: 'Ready!', time: 2600 }
    ];

    stages.forEach(stage => {
      setTimeout(() => {
        if (progressBar) progressBar.style.width = `${stage.pct}%`;
        if (statusText) statusText.textContent = stage.text;
      }, stage.time);
    });

    setTimeout(() => {
      this.dismissSplash();
    }, 3100);
  }

  dismissSplash() {
    const splash = document.getElementById('splash-screen');
    if (splash) {
      splash.classList.add('fade-out');
      setTimeout(() => {
        splash.style.display = 'none';
      }, 500);
    }
  }

  renderSystemLoader(container, message = 'Loading workspace...') {
    if (!container) return;
    container.innerHTML = `
      <div class="view-loader-container">
        <div class="orbital-loader-container" style="margin-bottom: 1.5rem;">
          <!-- 5 Orbiting Tiny Satellites -->
          <div class="orbital-ring">
            <div class="orbital-particle particle-1"></div>
            <div class="orbital-particle particle-2"></div>
            <div class="orbital-particle particle-3"></div>
            <div class="orbital-particle particle-4"></div>
            <div class="orbital-particle particle-5"></div>
          </div>

          <!-- Floating Core Emblem Disc -->
          <div class="orbital-core-disc">
            <span class="orbital-v-logo">V</span>
          </div>
        </div>
        <div class="splash-progress-wrapper" style="max-width: 320px;">
          <div class="splash-progress-track">
            <div class="splash-progress-bar" style="width: 80%; animation: pulse-fill 1.6s ease-in-out infinite alternate;"></div>
          </div>
          <div class="splash-status-caption" style="color: var(--navy-800); font-weight: 600;">${message}</div>
        </div>
      </div>
    `;
  }

  async handleRouting() {
    const isPathAdmin = window.location.pathname.endsWith('/admin') || window.location.hash === '#admin';

    // 1. Dedicated /admin route
    if (isPathAdmin) {
      if (window.vunothoAuth.isAdmin()) {
        await this.renderRoleView('admin');
        this.updateHeaderNav('admin');
      } else {
        this.renderAdminLogin();
        this.updateHeaderNav('admin_wall');
      }
      return;
    }

    // 2. If logged in as a normal role -> Render role portal
    if (window.vunothoAuth.isLoggedIn()) {
      const user = window.vunothoAuth.getUser();
      await this.renderRoleView(user.role);
      this.updateHeaderNav(user.role);
      return;
    }

    // 3. Logged out -> Render Public Landing Page
    this.renderLandingPage();
    this.updateHeaderNav('guest');
  }

  handleAuthChange() {
    this.handleRouting();
  }

  renderLandingPage() {
    const container = document.getElementById('app-view-container');
    if (!container) return;
    this.currentView = 'landing';
    window.landingView.render(container);
  }

  renderAdminLogin() {
    const container = document.getElementById('app-view-container');
    if (!container) return;
    this.currentView = 'admin_login';

    const iconLandmark = window.vunothoIcons.get('landmark', '', 36);
    const iconLock = window.vunothoIcons.get('lock');

    container.innerHTML = `
      <div class="admin-gatekeeper-banner">
        <div style="color: var(--gold-500); margin-bottom: 0.75rem; display: flex; justify-content: center;">
          ${iconLandmark}
        </div>
        <span class="badge badge-gold" style="margin-bottom: 0.5rem;">RESTRICTED ACCESS</span>
        <h2 style="font-size: 1.6rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem;">
          Vunotho Executive Portal
        </h2>
        <p style="color: #cbd5e1; font-size: 0.85rem; margin-bottom: 1.5rem;">
          Master Administrator gatekeeper. Enter verified executive credentials to monitor ecosystem health and manage system configurations.
        </p>

        <form onsubmit="event.preventDefault(); window.vunothoAuth.login(document.getElementById('admin-email').value, document.getElementById('admin-key').value, 'admin')">
          <div class="form-group" style="text-align: left;">
            <label class="form-label" style="color: #ffffff;">Admin Email or Username</label>
            <input type="text" class="form-control" id="admin-email" placeholder="admin@vunotho@gmail.com" required />
          </div>
          <div class="form-group" style="text-align: left;">
            <label class="form-label" style="color: #ffffff;">Master Security Key</label>
            <input type="password" class="form-control" id="admin-key" placeholder="••••••••" required />
          </div>
          <button type="submit" class="btn btn-gold" style="width: 100%; margin-top: 0.5rem;">
            ${iconLock} Authenticate Executive Session
          </button>
        </form>

        <div style="margin-top: 1.5rem;">
          <a href="./" style="color: #94a3b8; font-size: 0.8rem; text-decoration: none;" onclick="window.location.hash='';">
            ← Return to Public Marketplace
          </a>
        </div>
      </div>
    `;
  }

  async renderRoleView(role) {
    const container = document.getElementById('app-view-container');
    if (!container) return;

    this.currentView = role;
    this.renderSystemLoader(container, `Loading ${role.charAt(0).toUpperCase() + role.slice(1)} Portal...`);

    try {
      if (role === 'farmer') await window.farmerView.render(container);
      else if (role === 'buyer') await window.buyerView.render(container);
      else if (role === 'transporter') await window.transporterView.render(container);
      else if (role === 'admin') await window.adminView.render(container);
    } catch (err) {
      console.error('Failed to render view:', err);
      container.innerHTML = `
        <div class="empty-state">
          <h3>Failed to load ${role} portal</h3>
          <p class="text-sub">${err.message}</p>
          <button class="btn btn-primary-green" onclick="window.vunothoApp.refreshCurrentView()">Retry</button>
        </div>
      `;
    }
  }

  updateHeaderNav(state) {
    const navContainer = document.getElementById('header-right-nav');
    if (!navContainer) return;

    const iconLogOut = window.vunothoIcons.get('logOut', '', 14);

    if (state === 'admin_wall') {
      navContainer.innerHTML = `
        <div style="font-size: 0.75rem; color: var(--gold-500); font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;">
          🔒 Security Gatekeeper
        </div>
      `;
    } else if (state === 'guest') {
      navContainer.innerHTML = `
        <button class="btn btn-sm btn-primary-green" onclick="document.getElementById('auth-modal').classList.add('active')">
          Sign In / Register
        </button>
      `;
    } else {
      const user = window.vunothoAuth.getUser() || { name: 'Member', role: 'farmer' };
      const initials = (user.name || 'U').substring(0, 2).toUpperCase();
      navContainer.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <div class="user-profile-badge">
            <div class="user-profile-avatar">${initials}</div>
            <div class="user-profile-info">
              <span class="user-profile-name">${user.name}</span>
              <span class="user-profile-role">${user.role}</span>
            </div>
          </div>
          <button class="btn-signout" onclick="window.vunothoAuth.logout()" title="Sign out of your session" style="display: flex; align-items: center; gap: 0.35rem;">
            ${iconLogOut} Sign Out
          </button>
        </div>
      `;
    }
  }

  async refreshCurrentView() {
    await this.handleRouting();
  }

  /**
   * Global Toast Alert System
   */
  showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
      <span>${message}</span>
      <button style="background:none;border:none;color:#fff;cursor:pointer;font-size:1rem;margin-left:0.5rem;" onclick="this.parentElement.remove()">✕</button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      if (toast.parentElement) {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
      }
    }, 3800);
  }
}

// Global App Singleton
window.vunothoApp = new VunothoApp();

// Boot application when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.vunothoApp.init();
});
