<?php
/**
 * VUNOTHO ENTERPRISE SIDEBAR COMPONENT
 * Official Vunotho "V" Logo | Fully Collapsible | HD Vector Icons | Direct Sign Out | Offline Switch
 */
$currentUser = $user ?? get_current_user_profile();
$userName = $currentUser['name'] ?? 'Makomborero Gufe';
$userRole = ucfirst($currentUser['role'] ?? 'Farmer');
$userLocation = ($currentUser['district'] ?? 'Bulawayo') . ', ' . ($currentUser['province'] ?? 'Zimbabwe');
$activeTab = $_GET['tab'] ?? 'dashboard';
?>

<!-- Mobile Sidebar Backdrop Overlay -->
<div id="sidebar-backdrop" class="sidebar-backdrop" onclick="toggleMobileSidebar(false)"></div>

<!-- Master Portal Sidebar -->
<aside id="portal-sidebar" class="vn-sidebar">
  
  <!-- 1. Brand Logo Header & Collapse Button -->
  <div class="vn-sidebar-header">
    <a href="/farmer.php" class="flex items-center gap-3 group text-decoration-none min-w-0">
      <div class="w-9 h-9 rounded-xl overflow-hidden shadow-md border border-emerald-500/40 flex-shrink-0 bg-[#0B2032] flex items-center justify-center p-0.5">
        <img src="/images/vunotho_logo.png" alt="Vunotho Official Logo" class="w-full h-full object-cover rounded-lg" />
      </div>
      <div class="flex flex-col min-w-0 vn-sidebar-text">
        <span class="font-black text-base tracking-wider text-white leading-tight">VUNOTHO</span>
        <span class="text-[10px] font-semibold text-emerald-400 leading-none mt-0.5">Agricultural OS</span>
      </div>
    </a>
    
    <!-- Collapse Toggle Button (Desktop & Mobile) -->
    <button class="vn-collapse-btn ml-auto" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
        <line x1="9" y1="3" x2="9" y2="21"/>
        <path d="M14 9l-3 3 3 3"/>
      </svg>
    </button>
  </div>

  <!-- 2. 11 Fully-Functional Navigation Links with HD Apple/SF-Style SVG Icons -->
  <nav class="vn-sidebar-nav">
    
    <!-- 1. Dashboard -->
    <a href="/farmer.php?tab=dashboard" class="vn-nav-link <?= ($activeTab === 'dashboard' || empty($activeTab)) ? 'active' : '' ?>" title="Dashboard">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      </span>
      <span class="vn-sidebar-text">Dashboard</span>
    </a>

    <!-- 2. My Produce -->
    <a href="/farmer.php?tab=produce" class="vn-nav-link <?= $activeTab === 'produce' ? 'active' : '' ?>" title="My Produce">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
      </span>
      <span class="vn-sidebar-text">My Produce</span>
    </a>

    <!-- 3. Market Prices -->
    <a href="/farmer.php?tab=prices" class="vn-nav-link <?= $activeTab === 'prices' ? 'active' : '' ?>" title="Market Prices">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
      </span>
      <span class="vn-sidebar-text">Market Prices</span>
    </a>

    <!-- 4. Buyers -->
    <a href="/farmer.php?tab=buyers" class="vn-nav-link <?= $activeTab === 'buyers' ? 'active' : '' ?>" title="Buyers">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </span>
      <span class="vn-sidebar-text">Buyers</span>
    </a>

    <!-- 5. Transport -->
    <a href="/farmer.php?tab=transport" class="vn-nav-link <?= $activeTab === 'transport' ? 'active' : '' ?>" title="Transport">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      </span>
      <span class="vn-sidebar-text">Transport</span>
    </a>

    <!-- 6. Orders -->
    <a href="/farmer.php?tab=orders" class="vn-nav-link <?= $activeTab === 'orders' ? 'active' : '' ?>" title="Orders">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
      </span>
      <span class="vn-sidebar-text">Orders</span>
    </a>

    <!-- 7. Payments -->
    <a href="/farmer.php?tab=payments" class="vn-nav-link <?= $activeTab === 'payments' ? 'active' : '' ?>" title="Payments">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      </span>
      <span class="vn-sidebar-text">Payments</span>
    </a>

    <!-- 8. Messages -->
    <a href="/farmer.php?tab=messages" class="vn-nav-link <?= $activeTab === 'messages' ? 'active' : '' ?>" title="Messages">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </span>
      <span class="vn-sidebar-text">Messages</span>
    </a>

    <!-- 9. Reports -->
    <a href="/farmer.php?tab=reports" class="vn-nav-link <?= $activeTab === 'reports' ? 'active' : '' ?>" title="Reports">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      </span>
      <span class="vn-sidebar-text">Reports</span>
    </a>

    <!-- 10. Learning Hub -->
    <a href="/farmer.php?tab=learning" class="vn-nav-link <?= $activeTab === 'learning' ? 'active' : '' ?>" title="Learning Hub">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
      </span>
      <span class="vn-sidebar-text">Learning Hub</span>
    </a>

    <!-- 11. Settings -->
    <a href="/farmer.php?tab=settings" class="vn-nav-link <?= $activeTab === 'settings' ? 'active' : '' ?>" title="Settings">
      <span class="vn-nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      </span>
      <span class="vn-sidebar-text">Settings</span>
    </a>

  </nav>

  <!-- 3. Sidebar Footer: Profile, Sign Out, and Offline Switch -->
  <div class="vn-sidebar-footer">
    
    <!-- User Profile Card -->
    <div class="vn-user-card">
      <div class="vn-user-avatar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/>
          <path d="M12 6a4 4 0 1 0 4 4 4 4 0 0 0-4-4zm0 6a2 2 0 1 1 2-2 2 2 0 0 1-2 2z"/>
        </svg>
      </div>
      <div class="flex flex-col min-w-0 flex-1 vn-sidebar-footer-text">
        <span class="text-xs font-bold text-white truncate"><?= htmlspecialchars($userName) ?></span>
        <span class="text-[11px] font-semibold text-emerald-400"><?= htmlspecialchars($userRole) ?></span>
        <div class="flex items-center gap-1 text-[10px] text-slate-400 mt-0.5 truncate">
          <span>📍</span>
          <span class="truncate"><?= htmlspecialchars($userLocation) ?></span>
        </div>
      </div>
      
      <!-- Explicit Sign Out Action Button -->
      <a href="/logout.php" class="text-slate-400 hover:text-rose-400 transition-colors p-1.5 rounded-lg hover:bg-white/10 vn-sidebar-footer-text" title="Sign Out of Portal">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </a>
    </div>

    <!-- Offline Mode Toggle Switch -->
    <div class="vn-offline-switch-row">
      <div class="flex items-center gap-2 vn-offline-switch-text">
        <span id="offline-status-dot" class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span class="text-xs font-medium text-slate-200">Offline Mode</span>
      </div>
      <label class="vn-switch-toggle" title="Toggle Offline Mode">
        <input type="checkbox" id="offline-mode-toggle" onchange="handleOfflineToggle(this.checked)" />
        <span class="vn-switch-slider"></span>
      </label>
    </div>

  </div>

</aside>
