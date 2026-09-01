<?php
/**
 * VUNOTHO COMMON FOOTER COMPONENT
 * Consistent Across All Portal & Informational Pages
 */
?>
  </div><!-- /flex-1 -->

  <!-- Consistent Global Footer -->
  <footer class="vn-landing-footer">
    <div class="vn-container">
      <div class="vn-footer-grid">
        
        <!-- Brand Column with Clean Rounded Squircle Logo -->
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <img src="/images/vunotho_logo.png" alt="Vunotho Logo" class="vn-footer-brand-logo" />
            <div>
              <span class="font-black text-base text-slate-900 tracking-tight leading-none block">VUNOTHO</span>
              <span class="text-[10px] font-semibold text-emerald-700 leading-none">Agricultural OS</span>
            </div>
          </div>
          <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
            Zimbabwe's decentralized agricultural operating system developed to eliminate predatory middleman exploitation, secure transparent farmgate net returns, and divert 100% of post-harvest produce into high-value commercial channels.
          </p>
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-[11px] font-bold">
            <span>🌱</span>
            <span>An Enactus Zimbabwe Action Innovation</span>
          </div>
        </div>

        <!-- Column 2: Platform Desks -->
        <div class="space-y-2">
          <h4 class="font-bold text-xs uppercase tracking-wider text-slate-900">Platform Desks</h4>
          <ul class="space-y-1.5 text-xs text-slate-500">
            <li><a href="/farmer.php" class="hover:text-emerald-700">Smallholder Produce Hub</a></li>
            <li><a href="/buyer.php" class="hover:text-emerald-700">Commercial Procurement Desk</a></li>
            <li><a href="/transporter.php" class="hover:text-emerald-700">Rural Freight Fleet Desk</a></li>
            <li><a href="/index.php#simulator" class="hover:text-emerald-700">Price Intelligence Simulator</a></li>
          </ul>
        </div>

        <!-- Column 3: Knowledge & Legal -->
        <div class="space-y-2">
          <h4 class="font-bold text-xs uppercase tracking-wider text-slate-900">Knowledge & Legal</h4>
          <ul class="space-y-1.5 text-xs text-slate-500">
            <li><a href="/access.php" class="hover:text-emerald-700">Download Vunotho App (Access)</a></li>
            <li><a href="/farmer.php?tab=learning" class="hover:text-emerald-700">Agronomy Knowledge Hub</a></li>
            <li><a href="/privacy.php" class="hover:text-emerald-700">Privacy Policy</a></li>
            <li><a href="/data-policy.php" class="hover:text-emerald-700">Data Protection Policy</a></li>
            <li><a href="https://wa.me/263779634613" target="_blank" class="hover:text-emerald-700">Contact Support Desk</a></li>
          </ul>
        </div>

        <!-- Column 4: National Hubs -->
        <div class="space-y-2">
          <h4 class="font-bold text-xs uppercase tracking-wider text-slate-900">National Hubs</h4>
          <ul class="space-y-1.5 text-xs text-slate-500">
            <li>📍 Belmont Wholesale Depot, Bulawayo</li>
            <li>📍 Mbare Musika Produce Depot, Harare</li>
            <li>📍 Sakubva Commercial Hub, Mutare</li>
            <li>📞 Hotline: <strong class="text-slate-700">+263 77 963 4613</strong></li>
          </ul>
        </div>

      </div>

      <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-3">
        <div>© 2026 Vunotho Agricultural Platform. All rights reserved. Registered in Zimbabwe.</div>
        <div class="flex items-center gap-4">
          <a href="/privacy.php" class="hover:text-slate-600">Privacy Policy</a>
          <a href="/data-policy.php" class="hover:text-slate-600">Data Security</a>
          <a href="/contact.php" class="hover:text-slate-600">Contact Team</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Public Mobile PWA Bottom Floating Bar -->
  <nav class="vn-pwa-bottom-bar" aria-label="Public Mobile Navigation">
    <?php $currentScript = basename($_SERVER['SCRIPT_NAME'] ?? ''); ?>
    <a href="/index.php" class="vn-pwa-tab-item <?= $currentScript === 'index.php' ? 'active' : '' ?>">
      <div class="vn-pwa-tab-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
      </div>
      <span class="vn-pwa-tab-label">Home</span>
    </a>

    <a href="/index.php#simulator" class="vn-pwa-tab-item">
      <div class="vn-pwa-tab-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="4" y="2" width="16" height="20" rx="2"/>
          <line x1="8" y1="6" x2="16" y2="6"/>
          <line x1="8" y1="10" x2="16" y2="10"/>
        </svg>
      </div>
      <span class="vn-pwa-tab-label">Simulator</span>
    </a>

    <!-- Center Floating Action Button (Download / Launch App) -->
    <div class="vn-pwa-fab-container">
      <a href="/access.php" class="vn-pwa-fab-btn" title="Download Vunotho App">
        📲
      </a>
    </div>

    <a href="/farmer.php" class="vn-pwa-tab-item <?= $currentScript === 'farmer.php' ? 'active' : '' ?>">
      <div class="vn-pwa-tab-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>
      </div>
      <span class="vn-pwa-tab-label">Farmer Desk</span>
    </a>

    <a href="/access.php" class="vn-pwa-tab-item <?= $currentScript === 'access.php' ? 'active' : '' ?>">
      <div class="vn-pwa-tab-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
          <line x1="12" y1="18" x2="12.01" y2="18"/>
        </svg>
      </div>
      <span class="vn-pwa-tab-label">Get App</span>
    </a>
  </nav>

</body>
</html>

