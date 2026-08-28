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
  <meta name="theme-color" content="#0F2942" />
  
  <!-- PWA Web App Manifest & Mobile App Capabilities -->
  <link rel="manifest" href="/manifest.json" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta name="apple-mobile-web-app-title" content="Vunotho" />
  <link rel="icon" type="image/jpeg" href="/images/favicon.jpg" />
  <link rel="icon" type="image/svg+xml" href="/images/icon.svg" />
  <link rel="apple-touch-icon" href="/images/apple-touch-icon.jpg" />

  <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Compiled Tailwind CSS Stylesheet -->
  <link rel="stylesheet" href="/css/tailwind.css?v=5.0" />

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

        // If already installed and launched as standalone app, hide the button completely
        if (isStandalone || window.localStorage.getItem('vunotho_pwa_installed') === 'true') {
          btn.style.display = 'none';
          return;
        }

        // Show button for standard browser visitors
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
              // iOS or non-automated browser trigger
              if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                alert('Install Vunotho on iPhone / iPad:\n1. Tap the Share icon (⎋) at the bottom.\n2. Tap "Add to Home Screen" (+).');
              } else {
                window.location.href = '/access.php';
              }
            }
          });
        }
      });
    })();
  </script>
</head>
<body class="bg-[#F4F7F6] text-slate-900 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

  <!-- Master Application Header -->
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-warm-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
      <!-- Brand Logo -->
      <a href="<?= $currentUser ? '/' . strtolower($currentUser['role']) . '.php' : '/index.php' ?>" class="flex items-center gap-3 group">
        <img src="/images/vunotho_logo.jpg" alt="Vunotho Logo" class="w-9 h-9 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-all border border-emerald-500/20" />
        <span class="font-extrabold text-lg tracking-tight text-slate-900 leading-none">VUNOTHO</span>
      </a>

      <!-- Right Action Area -->
      <div class="flex items-center gap-3">
        
        <!-- PWA INSTALL APP BUTTON (Automatically hidden if already installed) -->
        <button id="pwa-install-header-btn" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-extrabold text-xs shadow-glow-emerald transition-all transform hover:scale-105 active:scale-95 cursor-pointer border border-emerald-400/40" title="Install Vunotho app on your device">
          <span class="text-sm">📲</span>
          <span class="hidden xs:inline sm:inline">Install App</span>
        </button>

        <?php if ($currentUser): ?>
          <?php
            $role = strtolower($currentUser['role']);
            $roleBadgeClass = $role === 'farmer' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' :
                             ($role === 'buyer' ? 'bg-amber-100 text-amber-800 border-amber-200' :
                             ($role === 'transporter' ? 'bg-orange-100 text-orange-800 border-orange-200' : 'bg-slate-900 text-white border-slate-900'));
            $roleDot = $role === 'farmer' ? 'bg-emerald-500 animate-pulse' : ($role === 'buyer' ? 'bg-amber-500' : ($role === 'transporter' ? 'bg-orange-500' : 'bg-emerald-400'));
          ?>
          <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-xl border <?= $roleBadgeClass ?> text-xs font-bold font-mono">
            <span class="w-2 h-2 rounded-full <?= $roleDot ?>"></span>
            <span class="capitalize"><?= htmlspecialchars($currentUser['role']) ?>:</span>
            <span class="text-slate-900 font-extrabold"><?= htmlspecialchars($currentUser['name']) ?></span>
          </div>

          <div id="sync-status-indicator" class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200" title="Connected">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span id="sync-status-text">Synced</span>
          </div>

          <a href="/logout.php" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all border border-slate-200 shadow-sm">
            Sign Out
          </a>
        <?php else: ?>
          <a href="/login.php" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all">
            Portal Sign In
          </a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- Master Content Wrapper -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
