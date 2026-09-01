<?php
/**
 * VUNOTHO ENTERPRISE LANDING PAGE (v3.0)
 * Seamless Full-Bleed Panorama Hero Background | Borderless Floating Header | Glassmorphic Widget
 */
require_once __DIR__ . '/api/session.php';
require_once __DIR__ . '/api/db.php';

$currentUser = get_current_user_profile();
$pdo = get_db_connection();

// Fetch Demands and Listings
$demands = $pdo->query("SELECT * FROM demands ORDER BY created_at DESC LIMIT 4")->fetchAll();
$listings = $pdo->query("SELECT * FROM listings ORDER BY created_at DESC LIMIT 4")->fetchAll();

if (empty($demands)) {
    $demands = [
        ['crop' => 'Roma Tomatoes', 'target_quantity_kg' => 1500, 'offered_price_per_kg' => 0.42, 'buyer_name' => 'FreshMart Bulawayo', 'delivery_hub' => 'Belmont Wholesale Depot'],
        ['crop' => 'Brown Onions', 'target_quantity_kg' => 1200, 'offered_price_per_kg' => 0.35, 'buyer_name' => 'Green Basket Stores', 'delivery_hub' => 'Bradfield Retail Hub'],
        ['crop' => 'Table Potatoes', 'target_quantity_kg' => 2500, 'offered_price_per_kg' => 0.30, 'buyer_name' => 'Harare Produce Depot', 'delivery_hub' => 'Mbare Musika Hub'],
        ['crop' => 'Leafy Greens', 'target_quantity_kg' => 600, 'offered_price_per_kg' => 0.25, 'buyer_name' => 'Bulawayo Central Kitchen', 'delivery_hub' => 'Kelvin Industrial Area']
    ];
}

if (empty($listings)) {
    $listings = [
        ['crop' => 'Roma Tomatoes', 'quantity_kg' => 180, 'quality' => 'Grade A (Supermarket Spec)', 'farmer_name' => 'Makomborero Gufe', 'district' => 'Bulawayo'],
        ['crop' => 'Brown Onions', 'quantity_kg' => 120, 'quality' => 'Grade A (10kg Pocket)', 'farmer_name' => 'Sipho Moyo', 'district' => 'Gwanda'],
        ['crop' => 'Table Potatoes', 'quantity_kg' => 80, 'quality' => 'Grade A (15kg Mesh)', 'farmer_name' => 'Farai Shumba', 'district' => 'Nyanga'],
        ['crop' => 'Leafy Greens', 'quantity_kg' => 40, 'quality' => 'Grade B (Agro-Processing)', 'farmer_name' => 'Tendai Chuma', 'district' => 'Mutare']
    ];
}

$pageTitle = 'Vunotho — Eliminate Middlemen. Secure Your Real Harvest Value.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  
  <meta name="description" content="Vunotho is Zimbabwe's decentralized agricultural operating system: Transparent farmgate price intelligence, 2.5T load aggregation, circular post-harvest recovery, and guaranteed EcoCash mobile settlements." />
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
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Stylesheets -->
  <link rel="stylesheet" href="/css/tailwind.css?v=5.0" />
  <link rel="stylesheet" href="/css/portal_dashboard.css?v=4.0" />
  <link rel="stylesheet" href="/css/landing_page.css?v=3.0" />

  <!-- Dynamic PWA Controller Script -->
  <script>
    (function() {
      let deferredPrompt = null;
      const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

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

  <!-- ==================== 1. FULL-BLEED PANORAMIC HERO SECTION WITH FLOATING HEADER ==================== -->
  <div class="vn-hero-wrapper">
    
    <!-- Seamless Directional Gradient: Soft Translucent on Left (text) -> Clear & Vivid on Right (farmer & field) -->
    <div class="vn-hero-bg-overlay"></div>

    <!-- Floating Borderless Navbar Blending into Hero -->
    <header class="vn-navbar-transparent">
      <div class="vn-container">
        <div class="vn-nav-inner">
          
          <!-- Brand Identity with Rounded Squircle Logo -->
          <a href="/index.php" class="vn-nav-brand">
            <img src="/images/vunotho_logo.png" alt="Official Vunotho Logo" class="vn-nav-brand-logo" />
            <div class="flex flex-col">
              <span class="font-black text-lg tracking-tight text-slate-900 leading-none">VUNOTHO</span>
              <span class="text-[10px] font-semibold text-emerald-800 leading-none mt-0.5">Agricultural OS</span>
            </div>
          </a>

          <!-- Center Navigation Links -->
          <nav class="hidden md:block">
            <ul class="vn-nav-links">
              <li><a href="/index.php" class="vn-nav-link-item active">Home</a></li>
              <li><a href="/index.php#solutions" class="vn-nav-link-item">Solutions</a></li>
              <li><a href="/index.php#simulator" class="vn-nav-link-item">Price Simulator</a></li>
              <li><a href="/index.php#impact" class="vn-nav-link-item">Impact</a></li>
              <li><a href="/index.php#technology" class="vn-nav-link-item">Technology</a></li>
              <li><a href="/farmer.php" class="vn-nav-link-item text-emerald-900 font-extrabold">Farmer Desk</a></li>
            </ul>
          </nav>

          <!-- Right Action CTA -->
          <div class="flex items-center gap-3">
            <button id="pwa-install-header-btn" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/80 backdrop-blur-sm text-emerald-900 hover:bg-white font-extrabold text-xs transition-all border border-emerald-200/60 shadow-sm">
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

    <!-- Hero Content: Text on Left Overlay, Clear Field View & Floating Widget on Right -->
    <section class="vn-hero-content-section">
      <div class="vn-container relative z-10 w-full">
        <div class="vn-hero-grid">
          
          <!-- Left Column: Sharp Dark Copy on Soft Misty Fade -->
          <div class="vn-hero-text-col">
            <div class="vn-hero-badge">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
              <span>Zimbabwe's Agricultural Operating System</span>
            </div>

            <h1 class="vn-hero-title">
              Eliminate Middlemen.<br />
              <span class="vn-hero-title-highlight">Secure Real Value.</span>
            </h1>

            <p class="vn-hero-desc">
              Vunotho connects Zimbabwean smallholder farmers directly to verified commercial buyers, pooled 2.5T rural freight, and guaranteed mobile money settlements — turning fresh produce into protected economic prosperity.
            </p>

            <div class="vn-hero-actions">
              <a href="/farmer.php" class="vn-btn-primary">
                <span>Get Started</span>
                <span>→</span>
              </a>
              <a href="#solutions" class="vn-btn-secondary">
                <span>Explore Solutions</span>
                <span>🌿</span>
              </a>
            </div>
          </div>

          <!-- Right Column: Translucent Floating Glass Widget directly over the Field & Farmer -->
          <div class="vn-hero-visual-col">
            <div class="vn-hero-floating-glass-card">
              <div class="space-y-1">
                <div class="vn-glass-stat-row">
                  <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_#34d399]"></span>
                  <span class="text-xs text-slate-200">Net Return Yield: <strong class="text-white font-bold">88% Payout</strong></span>
                </div>
                <div class="vn-glass-stat-row">
                  <span class="w-2.5 h-2.5 rounded-full bg-teal-400 shadow-[0_0_8px_#2dd4bf]"></span>
                  <span class="text-xs text-slate-200">Transport Route: <strong class="text-white font-bold">Gwanda ➔ Bulawayo</strong></span>
                </div>
              </div>

              <div class="vn-score-ring">
                <span class="vn-score-number">35%</span>
                <span class="vn-score-label">Saved</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

  </div>

  <!-- Main Body Content -->
  <div class="flex-1">

    <!-- 2. WHAT WE OFFER (VUNOTHO'S 4 CORE SOLUTIONS) -->
    <section id="solutions" class="vn-solutions-section">
      <div class="vn-container">
        
        <div class="vn-section-header-center">
          <div class="vn-section-badge">
            <span>🌿</span>
            <span>WHAT WE OFFER</span>
          </div>
          <h2 class="vn-section-heading">Smart Solutions for Zimbabwean Farmers</h2>
          <p class="vn-section-subtext">
            Everything you need to eliminate middleman exploitation, pool rural transport, and guarantee fair farmgate earnings.
          </p>
        </div>

        <div class="vn-solutions-grid">
          
          <!-- Card 1: Crop Grading & Lots -->
          <div class="vn-solution-card">
            <div>
              <div class="vn-solution-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
              </div>
              <h3 class="vn-solution-title">Crop Grading & Lots</h3>
              <p class="vn-solution-desc">
                Register and grade your harvest lots (Grade A Supermarket, Grade B Agro-Processing) with transparent digital tracking.
              </p>
            </div>
            <a href="/farmer.php?tab=produce" class="vn-solution-link">
              <span>Manage Produce Lots</span>
              <span>→</span>
            </a>
          </div>

          <!-- Card 2: 2.5T Transport Pooling -->
          <div class="vn-solution-card">
            <div>
              <div class="vn-solution-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
              </div>
              <h3 class="vn-solution-title">2.5T Freight Pooling</h3>
              <p class="vn-solution-desc">
                Clustered rural route aggregation to save 35% on transport costs and guarantee on-time farmgate collection.
              </p>
            </div>
            <a href="/farmer.php?tab=transport" class="vn-solution-link">
              <span>Book Transport Route</span>
              <span>→</span>
            </a>
          </div>

          <!-- Card 3: Price Intelligence -->
          <div class="vn-solution-card">
            <div>
              <div class="vn-solution-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M15 9.5a2.5 2.5 0 0 0-5 0c0 3 5 2 5 5a2.5 2.5 0 0 1-5 0"/></svg>
              </div>
              <h3 class="vn-solution-title">Price Intelligence</h3>
              <p class="vn-solution-desc">
                Real-time wholesale benchmark rates across Belmont, Mbare, and Sakubva with transparent net payout formulas.
              </p>
            </div>
            <a href="#simulator" class="vn-solution-link">
              <span>Run Price Simulator</span>
              <span>→</span>
            </a>
          </div>

          <!-- Card 4: Commercial Buyers -->
          <div class="vn-solution-card">
            <div>
              <div class="vn-solution-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
              </div>
              <h3 class="vn-solution-title">Commercial Buyers</h3>
              <p class="vn-solution-desc">
                Direct access to verified supermarket off-takers and food processors with automated EcoCash escrow disbursement.
              </p>
            </div>
            <a href="/farmer.php?tab=buyers" class="vn-solution-link">
              <span>View Verified Buyers</span>
              <span>→</span>
            </a>
          </div>

        </div>

        <div class="text-center">
          <a href="/farmer.php" class="inline-flex items-center gap-2 font-bold text-xs text-emerald-800 hover:text-emerald-900 underline">
            <span>Explore All Farmer Operations Features</span>
            <span>→</span>
          </a>
        </div>

      </div>
    </section>

    <!-- 3. DEEP FOREST GREEN IMPACT STATS BAR (PHASE 1 PILOT TARGETS) -->
    <section id="impact" class="vn-container">
      <div class="vn-impact-banner">
        
        <div class="text-center mb-6">
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-extrabold uppercase tracking-wider font-mono">
            <span>🌾</span>
            <span>PHASE 1 PILOT TARGETS & CIRCULAR VALUE PROJECTIONS</span>
          </div>
          <p class="text-xs text-slate-300 max-w-xl mx-auto mt-2 leading-relaxed">
            Projected milestones for Vunotho's Phase 1 pilot implementation across Manicaland smallholder corridors — recovering maximum dollar value from every harvested kilogram.
          </p>
        </div>

        <div class="vn-impact-counter-grid">
          
          <div class="vn-impact-item">
            <div class="vn-impact-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
            <div class="vn-impact-number">650+</div>
            <div class="vn-impact-label">Target Smallholders</div>
            <span class="text-[10px] text-slate-400 mt-1 block">Nyanga, Mutasa & Chipinge</span>
          </div>

          <div class="vn-impact-item">
            <div class="vn-impact-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5"/></svg>
            </div>
            <div class="vn-impact-number">180K+</div>
            <div class="vn-impact-label">Kg Produce Monetized</div>
            <span class="text-[10px] text-slate-400 mt-1 block">Direct to verified off-takers</span>
          </div>

          <div class="vn-impact-item">
            <div class="vn-impact-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
            </div>
            <div class="vn-impact-number">+28.5%</div>
            <div class="vn-impact-label">Projected Income Lift</div>
            <span class="text-[10px] text-slate-400 mt-1 block">Zero middlemen + 35% pooled freight</span>
          </div>

          <div class="vn-impact-item">
            <div class="vn-impact-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/></svg>
            </div>
            <div class="vn-impact-number">35+ T</div>
            <div class="vn-impact-label">Waste Recovered</div>
            <span class="text-[10px] text-slate-400 mt-1 block">Grade B purees & Grade C compost</span>
          </div>

        </div>

        <div class="mt-6 pt-4 border-t border-emerald-500/20 text-center">
          <p class="text-[11px] text-slate-300 italic">
            *Note: Metrics represent startup pilot targets for regional deployment. Vunotho unlocks 100% circular value extraction — from fresh supermarket retail to puree processing and bio-compost.
          </p>
        </div>

      </div>
    </section>

    <!-- 4. TECHNOLOGY THAT GROWS WITH YOU -->
    <section id="technology" class="vn-tech-section">
      <div class="vn-container">
        <div class="vn-tech-grid">
          
          <!-- Left Column: Feature Checkpoints & CTA -->
          <div>
            <div class="vn-section-badge">
              <span>🌿</span>
              <span>OFFLINE-FIRST • 2.5T POOLING • ECOCASH ESCROW</span>
            </div>
            <h2 class="vn-section-heading">Digital tools built for Zimbabwean smallholders</h2>
            <p class="vn-section-subtext">
              Built specifically for rural connectivity constraints, Vunotho functions completely offline and automatically synchronizes when cellular network is restored.
            </p>

            <div class="vn-tech-checklist">
              <div class="vn-tech-check-item">
                <div class="vn-check-icon">✓</div>
                <span><strong>100% Value Recovery & Multi-Tier Monetization:</strong> Monetize Grade A fresh retail, Grade B puree & processing, and Grade C bio-fertilizer compost.</span>
              </div>
              <div class="vn-tech-check-item">
                <div class="vn-check-icon">✓</div>
                <span><strong>2.5T Rural Corridor Clustering:</strong> Automatically combine smaller harvest lots into high-capacity consolidated freight to save 35% on transport.</span>
              </div>
              <div class="vn-tech-check-item">
                <div class="vn-check-icon">✓</div>
                <span><strong>Guaranteed Mobile Money Escrow:</strong> Direct EcoCash / OneMoney wallet payouts upon weigh-in verification at the wholesale depot.</span>
              </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
              <a href="/farmer.php" class="vn-btn-primary">
                <span>Open Farmer Desk</span>
                <span>→</span>
              </a>
              <a href="/access.php" class="px-5 py-3 rounded-full bg-emerald-50 text-emerald-900 border border-emerald-200 font-extrabold text-xs hover:bg-emerald-100 transition-all flex items-center gap-1.5 shadow-sm">
                <span>📲</span>
                <span>Download App</span>
              </a>
            </div>
          </div>

          <!-- Right Column: Clean Farmland & Corridor Hub Showcase -->
          <div>
            <div class="vn-tablet-overview-card">
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                  <span class="font-extrabold text-sm text-slate-900">Rural Farmland Corridor Hub</span>
                </div>
                <span class="text-[11px] font-mono text-emerald-800 bg-emerald-100/80 px-2.5 py-1 rounded-full font-bold border border-emerald-200">
                  GPS Clustered • 2.5T Pooling
                </span>
              </div>

              <div class="relative rounded-2xl overflow-hidden shadow-inner group">
                <img src="/images/farmland.png" alt="Farmland corridor overview" class="vn-farmland-thumb group-hover:scale-105 transition-transform duration-500" />
                <div class="absolute bottom-3 left-3 right-3 bg-slate-900/85 backdrop-blur-md px-3 py-2 rounded-xl text-white flex items-center justify-between text-xs">
                  <div class="flex items-center gap-2">
                    <span class="text-emerald-400">📍</span>
                    <span class="font-bold">Nyanga / Mutasa Corridor ➔ Harare Central</span>
                  </div>
                  <span class="font-mono text-emerald-300 text-[11px] font-bold">● Active Route</span>
                </div>
              </div>

              <div class="grid grid-cols-3 gap-2.5 text-[11px]">
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-center">
                  <span class="text-slate-500 block text-[10px] font-medium">Benchmark Rate</span>
                  <strong class="text-slate-900 font-mono text-xs font-black">$0.42 /kg</strong>
                </div>
                <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-100 text-center">
                  <span class="text-emerald-700 block text-[10px] font-medium">Pooled Freight</span>
                  <strong class="text-emerald-800 font-mono text-xs font-black">35% Saved</strong>
                </div>
                <div class="p-2.5 rounded-xl bg-teal-50 border border-teal-100 text-center">
                  <span class="text-teal-700 block text-[10px] font-medium">Offline Engine</span>
                  <strong class="text-teal-800 font-mono text-xs font-black">Auto-Sync</strong>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- 5. INTERACTIVE NET-RETURN PRICE CALCULATOR -->
    <section id="simulator" class="vn-container">
      <div class="vn-calculator-card">
        <div class="max-w-3xl">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold font-mono mb-3">
            🧮 Interactive Pricing Engine
          </div>
          <h2 class="text-2xl sm:text-4xl font-black tracking-tight mb-3">
            Guaranteed Farmgate Net-Return Calculator
          </h2>
          <p class="text-sm text-slate-300 mb-8 leading-relaxed">
            See your exact transparent take-home earnings before dispatching produce:
            <span class="text-emerald-400 font-mono font-bold">Gross Price − 35% Discounted Freight − 4% Fee = Net Take-Home</span>.
          </p>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white/5 p-6 rounded-2xl border border-white/10">
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-2">Select Commodity</label>
              <select id="sim-crop" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 text-white border border-slate-700 text-sm font-semibold focus:outline-none" onchange="runSimulator()">
                <option value="0.42" selected>🍅 Roma Tomatoes ($0.42/kg)</option>
                <option value="0.35">🧅 Brown Onions ($0.35/kg)</option>
                <option value="0.30">🥔 Table Potatoes ($0.30/kg)</option>
                <option value="0.25">🥬 Leafy Greens ($0.25/kg)</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 mb-2">Harvest Weight: <span id="sim-qty-label" class="text-emerald-400 font-mono">180 kg</span></label>
              <input type="range" id="sim-qty" min="50" max="2500" step="10" value="180" class="w-full accent-emerald-500" oninput="runSimulator()" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 mb-2">Distance to Hub: <span id="sim-dist-label" class="text-amber-400 font-mono">35 km</span></label>
              <input type="range" id="sim-dist" min="5" max="150" step="5" value="35" class="w-full accent-amber-500" oninput="runSimulator()" />
            </div>
          </div>

          <!-- Live Calculated Output -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-700/60 text-center">
            <div>
              <div class="text-[11px] text-slate-400 uppercase font-bold">Gross Value</div>
              <div id="sim-gross" class="text-xl font-black text-white font-mono mt-1">$75.60</div>
            </div>
            <div>
              <div class="text-[11px] text-slate-400 uppercase font-bold">Pooled Freight</div>
              <div id="sim-freight" class="text-xl font-black text-orange-400 font-mono mt-1">-$6.14</div>
            </div>
            <div>
              <div class="text-[11px] text-slate-400 uppercase font-bold">Vunotho Fee (4%)</div>
              <div id="sim-fee" class="text-xl font-black text-amber-400 font-mono mt-1">-$3.02</div>
            </div>
            <div class="bg-emerald-500/20 p-2.5 rounded-xl border border-emerald-400/30">
              <div class="text-[11px] text-emerald-300 uppercase font-bold">Net Take-Home</div>
              <div id="sim-net" class="text-2xl font-black text-emerald-400 font-mono mt-0.5">$66.44</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 6. BOTTOM COMMUNITY BANNER -->
    <section class="vn-container">
      <div class="vn-community-bar">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0">
            <img src="/images/vunotho_logo.png" class="w-8 h-8 rounded-lg object-cover" alt="Vunotho" />
          </div>
          <div>
            <h3 class="font-extrabold text-slate-900 text-base">Together, let's eliminate post-harvest waste and build rural prosperity.</h3>
            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 font-semibold mt-1">
              <span>🌿 Fair Farmgate Pricing</span>
              <span>📈 35% Freight Savings</span>
              <span>👥 Zero Produce Waste</span>
            </div>
          </div>
        </div>
        <a href="/farmer.php" class="vn-btn-primary whitespace-nowrap">
          <span>Join Vunotho</span>
          <span>🌿</span>
        </a>
      </div>
    </section>

  </div>

  <!-- 7. CLEAN PROFESSIONAL FOOTER -->
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
            <li><a href="/index.php#impact" class="hover:text-emerald-700">Circular Impact Statement</a></li>
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
          <a href="#" class="hover:text-slate-600">Privacy Policy</a>
          <a href="#" class="hover:text-slate-600">Data Security</a>
          <a href="#" class="hover:text-slate-600">Contact Team</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Simulator Calculation Script -->
  <script>
    function runSimulator() {
      const price = parseFloat(document.getElementById('sim-crop').value);
      const qty = parseFloat(document.getElementById('sim-qty').value);
      const dist = parseFloat(document.getElementById('sim-dist').value);

      document.getElementById('sim-qty-label').textContent = qty + ' kg';
      document.getElementById('sim-dist-label').textContent = dist + ' km';

      const gross = qty * price;
      const freight = qty * dist * 0.0015 * 0.65;
      const fee = gross * 0.04;
      const net = Math.max(0, gross - freight - fee);

      document.getElementById('sim-gross').textContent = '$' + gross.toFixed(2);
      document.getElementById('sim-freight').textContent = '-$' + freight.toFixed(2);
      document.getElementById('sim-fee').textContent = '-$' + fee.toFixed(2);
      document.getElementById('sim-net').textContent = '$' + net.toFixed(2);
    }
    document.addEventListener('DOMContentLoaded', runSimulator);
  </script>

</body>
</html>
