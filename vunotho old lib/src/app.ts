/**
 * VUNOTHO ENTERPRISE WEB APP CONTROLLER & SECURITY ROUTE GUARD (TypeScript)
 * Enforces Strict Role-Based Access Control (RBAC), Session Verification & Navigation Protection
 */

import { vunothoAuth } from './auth';
import { vunothoSync } from './sync';
import { landingView } from './views/landingView';
import { farmerView } from './views/farmerView';
import { buyerView } from './views/buyerView';
import { transporterView } from './views/transporterView';
import { adminView } from './views/adminView';
import { vunothoIcons } from './icons';

export class VunothoApp {
  private currentRole: 'landing' | 'farmer' | 'buyer' | 'transporter' | 'admin' = 'landing';
  private appContainer: HTMLElement | null = null;

  async init() {
    this.appContainer = document.getElementById('app-main-content');
    vunothoSync.init();

    // Attach Hash Change Listener
    window.addEventListener('hashchange', () => this.handleRouting());

    // Dismiss Splash Screen smoothly
    setTimeout(() => {
      const splash = document.getElementById('orbital-splash');
      if (splash) {
        splash.style.opacity = '0';
        splash.style.transition = 'opacity 0.4s ease';
        setTimeout(() => splash.remove(), 400);
      }
    }, 450);

    // Initial Route Execution
    this.handleRouting();
    this.updateHeaderNav();
  }

  /**
   * Strict Security Route Guard
   * Prevents unauthorized access and validates role permissions
   */
  handleRouting() {
    const rawHash = window.location.hash.toLowerCase().replace('#', '').trim();
    const user = vunothoAuth.getUser();

    // 1. Unauthenticated Visitor Flow (Guest)
    if (!user) {
      if (rawHash === 'admin') {
        this.renderAdminLoginGate();
        this.updateHeaderNav();
        return;
      } else if (rawHash === 'farmer' || rawHash === 'buyer' || rawHash === 'transporter') {
        // Redirect to landing and trigger the appropriate role login modal
        this.currentRole = 'landing';
        this.renderRoleView();
        this.updateHeaderNav();
        setTimeout(() => {
          landingView.openAuthModal(rawHash as any, 'signin');
        }, 150);
        return;
      } else {
        this.currentRole = 'landing';
        this.renderRoleView();
        this.updateHeaderNav();
        return;
      }
    }

    // 2. Authenticated User Role Guard
    if (rawHash === '' || rawHash === 'landing') {
      // Logged-in user navigated to home/landing: route directly to their assigned portal
      this.currentRole = user.role as any;
      window.location.hash = user.role;
      this.renderRoleView();
      this.updateHeaderNav();
      return;
    }

    // Check if user has permission for the requested route
    if (rawHash !== user.role) {
      // User is attempting to access a route that doesn't match their role
      this.renderAccessDeniedGate(rawHash, user);
      this.updateHeaderNav();
      return;
    }

    // Route matches authorized role
    this.currentRole = user.role as any;
    this.renderRoleView();
    this.updateHeaderNav();
  }

  renderRoleView() {
    if (!this.appContainer) return;
    window.scrollTo({ top: 0, behavior: 'smooth' });

    switch (this.currentRole) {
      case 'farmer':
        farmerView.render(this.appContainer);
        break;
      case 'buyer':
        buyerView.render(this.appContainer);
        break;
      case 'transporter':
        transporterView.render(this.appContainer);
        break;
      case 'admin':
        adminView.render(this.appContainer);
        break;
      default:
        landingView.render(this.appContainer);
        break;
    }
  }

  /**
   * Render 403 Forbidden Security Gate when a logged-in user attempts to switch to an unauthorized portal
   */
  renderAccessDeniedGate(attemptedRoute: string, user: any) {
    if (!this.appContainer) return;
    window.scrollTo({ top: 0, behavior: 'smooth' });

    const roleTitles: Record<string, string> = {
      admin: 'Executive Super Administrator Command Center',
      buyer: 'Commercial Off-taker Procurement Desk',
      transporter: 'Rural Freight Logistics Desk',
      farmer: 'Smallholder Produce Hub'
    };

    const requestedTitle = roleTitles[attemptedRoute] || 'Restricted Area';
    const currentTitle = roleTitles[user.role] || user.role;

    this.appContainer.innerHTML = `
      <div class="max-w-2xl mx-auto my-12 glass-panel-elevated p-8 md:p-12 text-center border-l-4 border-l-rose-500">
        <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-3xl font-black mx-auto mb-6">
          🛡️
        </div>

        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-800 text-xs font-extrabold border border-rose-200 mb-3">
          403 Forbidden • Access Control Restriction
        </div>

        <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-3">
          Access Restricted: Unauthorized Portal
        </h2>

        <p class="text-sm text-slate-600 mb-6 leading-relaxed">
          You are currently authenticated as <strong class="text-slate-900">${user.name}</strong> with the active role <strong class="text-emerald-700 capitalize">${user.role}</strong>.
          <br />
          You do not have administrative authorization to access the <strong class="text-slate-900">${requestedTitle}</strong>.
        </p>

        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500 mb-8 max-w-md mx-auto text-left">
          <div>• User ID: <code class="font-mono text-slate-700 font-bold">${user.id}</code></div>
          <div>• Authorized Portal: <strong class="text-slate-800">${currentTitle}</strong></div>
          <div>• Security Policy: Strict Role Separation Enforced</div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
          <button class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all" onclick="window.location.hash='${user.role}'">
            Return to My ${user.role.toUpperCase()} Desk →
          </button>
          <button class="px-5 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs border border-slate-300 transition-all" onclick="window.vunothoAuth.logout()">
            Sign Out & Switch Account
          </button>
        </div>
      </div>
    `;
  }

  /**
   * Render Dedicated Admin Login Gate for Guest visitors trying to access #admin
   */
  renderAdminLoginGate() {
    if (!this.appContainer) return;
    window.scrollTo({ top: 0, behavior: 'smooth' });

    this.appContainer.innerHTML = `
      <div class="max-w-md mx-auto my-12 glass-panel-elevated p-8 text-center border-t-4 border-t-slate-900 shadow-warm-xl">
        <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-2xl font-black mx-auto mb-4">
          🔐
        </div>

        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-xs font-bold font-mono mb-3">
          Super Administrator Gateway
        </div>

        <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-2">
          Executive Admin Authentication
        </h2>
        <p class="text-xs text-slate-500 mb-6">
          Access to national Enactus governance, user KYC controls, and economic parameters requires verified Super Admin credentials.
        </p>

        <form id="admin-gate-form" onsubmit="window.vunothoApp.handleAdminLogin(event)" class="text-left space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Admin Email or Master Account</label>
            <input type="text" id="admin-gate-email" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono" placeholder="admin@vunotho@gmail.com" required />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Master Security Password</label>
            <input type="password" id="admin-gate-pass" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm" placeholder="••••••••" required />
          </div>

          <button type="submit" class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-warm-md transition-all mt-4" id="btn-admin-auth">
            Authorize Super Admin Session
          </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
          <button class="text-xs text-slate-500 hover:text-slate-800 font-bold" onclick="window.location.hash=''">
            ← Back to Public Overview
          </button>
        </div>
      </div>
    `;
  }

  async handleAdminLogin(event: Event) {
    event.preventDefault();
    const email = (document.getElementById('admin-gate-email') as HTMLInputElement).value;
    const password = (document.getElementById('admin-gate-pass') as HTMLInputElement).value;

    try {
      await vunothoAuth.login(email, password, 'admin');
      window.location.hash = 'admin';
      this.handleRouting();
    } catch (e: any) {
      alert(`Admin Authentication Failed: ${e.message}`);
    }
  }

  /**
   * Update Top Navigation Header cleanly based on authentication state
   */
  updateHeaderNav() {
    const user = vunothoAuth.getUser();
    const navLinks = document.getElementById('header-nav-links');
    const authAction = document.getElementById('header-auth-action');

    if (navLinks) {
      if (user) {
        // Authenticated user: DO NOT show role switcher tabs. Show active portal context only.
        const roleColor = user.role === 'farmer' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : (user.role === 'buyer' ? 'bg-amber-100 text-amber-800 border-amber-200' : (user.role === 'transporter' ? 'bg-orange-100 text-orange-800 border-orange-200' : 'bg-slate-900 text-white border-slate-900'));
        navLinks.innerHTML = `
          <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl border ${roleColor} text-xs font-bold">
            <span class="w-2 h-2 rounded-full ${user.role === 'farmer' ? 'bg-emerald-500 animate-pulse' : (user.role === 'buyer' ? 'bg-amber-500' : 'bg-orange-500')}"></span>
            <span class="capitalize">${user.role} Desk:</span>
            <span>${user.name}</span>
            ${user.district ? `<span class="opacity-75 font-normal">(${user.district})</span>` : ''}
          </div>
        `;
      } else {
        // Public guest visitor links
        navLinks.innerHTML = `
          <a href="#landing" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all">Overview</a>
          <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all" onclick="window.landingView?.openAuthModal('farmer', 'signin')">Farmer Portal</button>
          <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all" onclick="window.landingView?.openAuthModal('buyer', 'signin')">Buyer Portal</button>
          <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all" onclick="window.landingView?.openAuthModal('transporter', 'signin')">Haulier Portal</button>
          <a href="#admin" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-900 hover:text-emerald-700 transition-all">Admin Access</a>
        `;
      }
    }

    if (authAction) {
      if (user) {
        authAction.innerHTML = `
          <div class="flex items-center gap-2">
            <button class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all border border-slate-200 shadow-sm" onclick="window.vunothoAuth.logout()">
              Sign Out
            </button>
          </div>
        `;
      } else {
        authAction.innerHTML = `
          <button class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all" onclick="window.landingView?.openAuthModal('farmer', 'signin')">
            Portal Sign In
          </button>
        `;
      }
    }
  }

  handleAuthChange() {
    this.handleRouting();
  }

  refreshCurrentView() {
    this.renderRoleView();
  }

  showToast(message: string, type: 'info' | 'success' | 'warning' | 'error' = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    const colorClasses = type === 'success' ? 'bg-emerald-700 text-white' : (type === 'warning' ? 'bg-amber-600 text-white' : (type === 'error' ? 'bg-rose-600 text-white' : 'bg-slate-900 text-white'));

    toast.className = `toast-item px-4 py-3 rounded-xl shadow-warm-lg text-xs font-bold flex items-center justify-between gap-3 ${colorClasses}`;
    toast.innerHTML = `
      <span>${message}</span>
      <button class="opacity-70 hover:opacity-100 font-bold text-sm" onclick="this.parentElement.remove()">✕</button>
    `;

    container.appendChild(toast);
    setTimeout(() => {
      if (toast.parentElement) {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }
    }, 4000);
  }
}

export const vunothoApp = new VunothoApp();
if (typeof window !== 'undefined') {
  (window as any).vunothoApp = vunothoApp;
  document.addEventListener('DOMContentLoaded', () => vunothoApp.init());
}
