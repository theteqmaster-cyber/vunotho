<?php
/**
 * VUNOTHO COMMON HEADER COMPONENT (PHP & Tailwind CSS)
 */
require_once __DIR__ . '/../api/session.php';
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}
$currentUser = get_current_user_profile();
$pageTitle = $pageTitle ?? 'Vunotho — Farmer-to-Market Operating System';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  
  <meta name="description" content="Vunotho is Zimbabwe's integrated agricultural operating system featuring net-return price intelligence, 2.5T load aggregation, offline-first reliability, and circular post-harvest recovery." />
  <meta name="theme-color" content="#071726" />
  
  <!-- PWA Web App Manifest & Mobile App Capabilities -->
  <link rel="manifest" href="/manifest.json" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta name="apple-mobile-web-app-title" content="Vunotho" />
  <link rel="icon" type="image/jpeg" href="/images/favicon.jpg" />
  <link rel="icon" type="image/svg+xml" href="/images/icon.svg" />

  <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Compiled Tailwind CSS & Enterprise Dashboard Stylesheet -->
  <link rel="stylesheet" href="/css/tailwind.css?v=5.0" />
  <link rel="stylesheet" href="/css/portal_dashboard.css?v=2.0" />

  <style>
    body {
      background-color: #F5F7F6 !important;
      background-image: 
        radial-gradient(circle at 10% 15%, rgba(16, 185, 129, 0.04) 0%, transparent 40%),
        radial-gradient(circle at 90% 85%, rgba(11, 32, 50, 0.03) 0%, transparent 40%),
        linear-gradient(to right, rgba(226, 232, 240, 0.4) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(226, 232, 240, 0.4) 1px, transparent 1px) !important;
      background-size: 100% 100%, 100% 100%, 48px 48px, 48px 48px !important;
    }
  </style>

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
<body class="text-slate-900 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

  <!-- Master Application Header -->
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
      
      <!-- Brand Logo with Official Vunotho "V" -->
      <a href="/index.php" class="flex items-center gap-3 group">
        <div class="w-9 h-9 rounded-xl overflow-hidden shadow-md border border-emerald-500/40 flex-shrink-0 bg-[#0B2032] flex items-center justify-center p-0.5 group-hover:scale-105 transition-all">
          <img src="/images/vunotho_logo.png" alt="Official Vunotho Logo" class="w-full h-full object-cover rounded-lg" />
        </div>
        <div class="flex flex-col">
          <span class="font-extrabold text-lg tracking-tight text-slate-900 leading-none">VUNOTHO</span>
          <span class="text-[9.5px] font-bold text-emerald-700 uppercase tracking-wider">Agricultural OS</span>
        </div>
      </a>

      <!-- Center Navigation Desks -->
      <nav class="hidden md:flex items-center gap-1.5 text-xs font-bold text-slate-600">
        <a href="/farmer.php" class="px-3 py-1.5 rounded-full hover:text-emerald-700 hover:bg-emerald-50 transition-all flex items-center gap-1.5 text-emerald-800 bg-emerald-50/60">
          <span>🌱</span> Farmer Desk
        </a>
        <a href="/buyer.php" class="px-3 py-1.5 rounded-full hover:text-amber-700 hover:bg-amber-50 transition-all flex items-center gap-1.5">
          <span>🏬</span> Buyer Sourcing
        </a>
        <a href="/transporter.php" class="px-3 py-1.5 rounded-full hover:text-orange-700 hover:bg-orange-50 transition-all flex items-center gap-1.5">
          <span>🚚</span> Rural Freight
        </a>
        <a href="/index.php#simulator" class="px-3 py-1.5 rounded-full hover:text-slate-900 hover:bg-slate-100 transition-all">
          Price Simulator
        </a>
      </nav>

      <!-- Right Action Area -->
      <div class="flex items-center gap-3">
        
        <!-- PWA INSTALL APP BUTTON -->
        <button id="pwa-install-header-btn" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-extrabold text-xs transition-all border border-emerald-200">
          <span>📲</span>
          <span class="hidden sm:inline">Install App</span>
        </button>

        <?php if ($currentUser): ?>
          <a href="/farmer.php" class="px-4 py-2 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm transition-all flex items-center gap-1.5">
            <span>📊</span> Dashboard
          </a>
          <a href="/logout.php" class="px-3 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all">
            Sign Out
          </a>
        <?php else: ?>
          <a href="/farmer.php" class="px-4 py-2 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-md transition-all flex items-center gap-1.5">
            <span>🌱</span> Farmer Dashboard
          </a>
          <a href="/login.php" class="hidden sm:inline-flex px-3.5 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-all border border-slate-200">
            Sign In
          </a>
        <?php endif; ?>

      </div>
    </div>
  </header>

  <!-- Master Content Wrapper -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
