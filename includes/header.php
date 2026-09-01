<?php
/**
 * VUNOTHO ENTERPRISE HEADER COMPONENT
 * Clean AgriConnect Navbar with Floating Logo, Direct Navigation & PWA Capability
 */
require_once __DIR__ . '/../api/session.php';
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}
$currentUser = get_current_user_profile();
$pageTitle = $pageTitle ?? 'Vunotho — Empowering Farmers. Growing Tomorrow.';
$currentScript = basename($_SERVER['SCRIPT_NAME'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  
  <meta name="description" content="Vunotho is Zimbabwe's agricultural operating system: Transparent farmgate net returns, 2.5T load pooling, and guaranteed mobile settlements." />
  <meta name="theme-color" content="#071726" />
  
  <!-- PWA Web App Manifest & Mobile App Capabilities -->
  <link rel="manifest" href="/manifest.json" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta name="apple-mobile-web-app-title" content="Vunotho" />
  <link rel="icon" type="image/png" href="/images/vunotho_logo.png" />
  <link rel="apple-touch-icon" href="/images/vunotho_logo.png" />

  <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Stylesheets -->
  <link rel="stylesheet" href="/css/tailwind.css?v=5.0" />
  <link rel="stylesheet" href="/css/portal_dashboard.css?v=4.0" />
  <link rel="stylesheet" href="/css/landing_page.css?v=1.0" />

  <!-- Dynamic PWA Controller Script -->
  <script>
    (function() {
      let deferredPrompt = null;
      const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

      // Register Service Worker
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
          navigator.serviceWorker.register('/sw.js').catch((err) => {
            console.log('SW registration error:', err);
          });
        });
      }

      function updatePwaButton() {
        const btn = document.getElementById('pwa-install-header-btn');
        if (!btn) return;

        if (isStandalone || window.localStorage.getItem('vunotho_pwa_installed') === 'true') {
          btn.style.display = 'none';
          return;
        }
        btn.style.display = 'inline-flex';
      }

      window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        updatePwaButton();
      });

      window.addEventListener('appinstalled', () => {
        window.localStorage.setItem('vunotho_pwa_installed', 'true');
        const btn = document.getElementById('pwa-install-header-btn');
        if (btn) btn.style.display = 'none';
      });

      document.addEventListener('DOMContentLoaded', () => {
        updatePwaButton();
        const btn = document.getElementById('pwa-install-header-btn');
        if (btn) {
          btn.addEventListener('click', async () => {
            if (deferredPrompt) {
              deferredPrompt.prompt();
              const { outcome } = await deferredPrompt.userChoice;
              if (outcome === 'accepted') {
                window.localStorage.setItem('vunotho_pwa_installed', 'true');
                btn.style.display = 'none';
              }
              deferredPrompt = null;
            } else {
              window.location.href = '/access.php';
            }
          });
        }
      });
    })();
  </script>
</head>
<body class="vn-landing-body text-slate-900 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

  <!-- Streamlined AgriConnect Navbar -->
  <header class="vn-navbar">
    <div class="vn-container">
      <div class="vn-nav-inner">
        
        <!-- Left: Brand Logo with Floating "V" Logo -->
        <a href="/index.php" class="vn-nav-brand">
          <img src="/images/vunotho_logo.png" alt="Official Vunotho Logo" class="vn-nav-brand-logo" />
          <div class="flex flex-col">
            <span class="font-black text-lg tracking-tight text-slate-900 leading-none">VUNOTHO</span>
            <span class="text-[10px] font-semibold text-emerald-700 leading-none mt-0.5">Grow Better. Live Better.</span>
          </div>
        </a>

        <!-- Center Navigation Links -->
        <nav class="hidden md:block">
          <ul class="vn-nav-links">
            <li>
              <a href="/index.php" class="vn-nav-link-item <?= $currentScript === 'index.php' ? 'active' : '' ?>">Home</a>
            </li>
            <li>
              <a href="/index.php#solutions" class="vn-nav-link-item">Solutions</a>
            </li>
            <li>
              <a href="/index.php#impact" class="vn-nav-link-item">Impact</a>
            </li>
            <li>
              <a href="/index.php#technology" class="vn-nav-link-item">Technology</a>
            </li>
            <li>
              <a href="/index.php#simulator" class="vn-nav-link-item">Price Intelligence</a>
            </li>
            <li>
              <a href="/farmer.php" class="vn-nav-link-item text-emerald-800 font-bold">Farmer Desk</a>
            </li>
          </ul>
        </nav>

        <!-- Right Action Area -->
        <div class="flex items-center gap-3">
          <!-- PWA Install Button -->
          <button id="pwa-install-header-btn" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-extrabold text-xs transition-all border border-emerald-200">
            <span>📲</span>
            <span class="hidden sm:inline">Install App</span>
          </button>

          <?php if ($currentUser): ?>
            <a href="/farmer.php" class="vn-nav-cta-btn">
              <span>Operations Hub</span>
              <span>→</span>
            </a>
          <?php else: ?>
            <a href="/farmer.php" class="vn-nav-cta-btn">
              <span>Get Started</span>
              <span>🌿</span>
            </a>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </header>

  <!-- Main Content Wrapper -->
  <div class="flex-1">
