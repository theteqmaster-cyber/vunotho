<?php
/**
 * VUNOTHO ENTERPRISE LANDING PAGE
 * Inspired by AgriConnect Modern Agritech UI: Hero split layout with maize_hero, 
 * 4 solutions cards, deep green impact counter, mobile + farmland tech showcase, 
 * and interactive pricing engine.
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

$pageTitle = 'Vunotho — Empowering Farmers. Growing Tomorrow.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- 1. HERO SECTION (SPLIT LAYOUT WITH MAIZE IMAGE & FLOATING CROP SCORE) -->
<section class="relative pt-4 pb-14 md:py-12 overflow-hidden">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">
    
    <!-- Left Column: Copy & CTAs -->
    <div class="lg:col-span-6 space-y-6">
      
      <!-- Sub-headline Pill Badge -->
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100/80 text-emerald-900 border border-emerald-200/80 text-xs font-bold tracking-wide">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        <span>Smart Farming for a Sustainable Future</span>
      </div>

      <!-- Main Headline -->
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-950 leading-[1.12]">
        Empowering Farmers.<br />
        <span class="text-emerald-700">Growing Tomorrow.</span>
      </h1>

      <!-- Value Proposition Description -->
      <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl font-medium">
        Vunotho brings transparent farmgate price intelligence, 2.5T load aggregation, and guaranteed mobile money settlements to help you increase net earnings, eliminate middleman exploitation, and build a sustainable future.
      </p>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center gap-3.5 pt-2">
        <a href="/farmer.php" class="px-7 py-3.5 rounded-full bg-emerald-800 hover:bg-emerald-900 text-white font-black text-sm shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
          <span>Get Started</span>
          <span>→</span>
        </a>
        <a href="#solutions" class="px-6 py-3.5 rounded-full bg-white hover:bg-slate-50 text-slate-800 font-bold text-sm border border-slate-300 shadow-sm transition-all flex items-center gap-2">
          <span>Explore Solutions</span>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/></svg>
        </a>
      </div>

      <!-- Verified Metric Badges -->
      <div class="pt-4 flex items-center gap-6 text-xs text-slate-500 font-medium">
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          <span>Zero Middleman Markups</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-amber-500"></span>
          <span>EcoCash Instant Settlement</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-teal-500"></span>
          <span>35% Pooled Transport</span>
        </div>
      </div>

    </div>

    <!-- Right Column: High-Res Hero Image with Glassmorphic Score Widget -->
    <div class="lg:col-span-6 relative">
      <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-emerald-900/10 bg-slate-900 group">
        <!-- High-Res Maize Field Farmer Image -->
        <img 
          src="/images/maize_hero.png" 
          alt="Farmer checking crop health in lush green field" 
          class="w-full h-[380px] sm:h-[480px] object-cover object-center transform group-hover:scale-102 transition-transform duration-700" 
        />

        <!-- Subtle Ambient Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent pointer-events-none"></div>

        <!-- Glassmorphic Floating Crop Score Widget -->
        <div class="absolute bottom-6 right-6 left-6 sm:left-auto bg-slate-900/80 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-white shadow-xl flex items-center justify-between sm:gap-6 max-w-sm">
          <div class="space-y-1.5">
            <div class="flex items-center gap-2 text-xs text-slate-300">
              <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
              <span>Crop Health: <strong class="text-white font-bold">Grade A</strong></span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-300">
              <span class="w-2 h-2 rounded-full bg-teal-400"></span>
              <span>Moisture / Quality: <strong class="text-white font-bold">Optimal</strong></span>
            </div>
          </div>

          <!-- Circular Score Indicator -->
          <div class="flex items-center gap-2 pl-3 border-l border-white/20">
            <div class="w-14 h-14 rounded-full bg-emerald-500/20 border-2 border-emerald-400 flex flex-col items-center justify-center">
              <span class="text-base font-black text-emerald-300 font-mono leading-none">88%</span>
              <span class="text-[8px] font-bold text-slate-300 uppercase leading-none mt-0.5">Net Index</span>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>

<!-- 2. WHAT WE OFFER — SMART SOLUTIONS FOR MODERN FARMING -->
<section id="solutions" class="py-12 border-t border-slate-200/60">
  <div class="text-center max-w-2xl mx-auto mb-12">
    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold mb-3 font-mono">
      🌿 WHAT WE OFFER
    </div>
    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
      Smart Solutions for Modern Farming
    </h2>
    <p class="text-sm text-slate-600 mt-2 leading-relaxed">
      Everything you need to manage your harvest lots, pooled freight, and commercial buyers efficiently and profitably.
    </p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    
    <!-- Solution Card 1: Crop Management -->
    <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-emerald-100/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between group">
      <div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-5 group-hover:scale-105 transition-transform border border-emerald-100">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <h3 class="font-extrabold text-slate-900 text-lg mb-2">Crop Management</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Plan, monitor, and register your produce lots with quality grading tiers and real-time benchmark rates.
        </p>
      </div>
      <a href="/farmer.php?tab=produce" class="mt-5 text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
        <span>Manage Harvest Lots</span>
        <span>→</span>
      </a>
    </div>

    <!-- Solution Card 2: Transport & Load Pooling -->
    <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-emerald-100/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between group">
      <div>
        <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-700 flex items-center justify-center mb-5 group-hover:scale-105 transition-transform border border-orange-100">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <h3 class="font-extrabold text-slate-900 text-lg mb-2">2.5T Transport Pooling</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Clustered rural route scheduling to save 35% on transport and ensure on-time collection right from the farm gate.
        </p>
      </div>
      <a href="/farmer.php?tab=transport" class="mt-5 text-xs font-bold text-orange-700 hover:text-orange-800 flex items-center gap-1">
        <span>Book Freight Desk</span>
        <span>→</span>
      </a>
    </div>

    <!-- Solution Card 3: Transparent Net Returns -->
    <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-emerald-100/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between group">
      <div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center mb-5 group-hover:scale-105 transition-transform border border-amber-100">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M15 9.5a2.5 2.5 0 0 0-5 0c0 3 5 2 5 5a2.5 2.5 0 0 1-5 0"/></svg>
        </div>
        <h3 class="font-extrabold text-slate-900 text-lg mb-2">Fair Price Intelligence</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Know your guaranteed net payout before dispatch: <span class="font-mono text-slate-800 font-bold">Gross − Freight − 4% Fee = Net Take-Home</span>.
        </p>
      </div>
      <a href="#simulator" class="mt-5 text-xs font-bold text-amber-700 hover:text-amber-800 flex items-center gap-1">
        <span>Run Pricing Simulator</span>
        <span>→</span>
      </a>
    </div>

    <!-- Solution Card 4: Market Insights & Buyers -->
    <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-emerald-100/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between group">
      <div>
        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-5 group-hover:scale-105 transition-transform border border-teal-100">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
        </div>
        <h3 class="font-extrabold text-slate-900 text-lg mb-2">Commercial Demands</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Get direct access to wholesale off-takers with verified purchase orders and automated EcoCash escrow disbursement.
        </p>
      </div>
      <a href="/farmer.php?tab=buyers" class="mt-5 text-xs font-bold text-teal-700 hover:text-teal-800 flex items-center gap-1">
        <span>View Buyer Directory</span>
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
</section>

<!-- 3. DEEP FOREST GREEN IMPACT STATS BANNER -->
<section class="mb-14">
  <div class="bg-gradient-to-br from-[#071726] via-[#0A251A] to-[#064E3B] text-white p-8 md:p-12 rounded-3xl border border-emerald-500/20 shadow-xl">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center divide-y sm:divide-y-0 sm:divide-x divide-white/10">
      
      <div class="pt-4 sm:pt-0">
        <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center mx-auto mb-2">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-white">1,420+</div>
        <div class="text-xs font-semibold text-emerald-200 mt-1">Active Smallholders</div>
      </div>

      <div class="pt-4 sm:pt-0">
        <div class="w-10 h-10 rounded-full bg-amber-500/20 text-amber-300 flex items-center justify-center mx-auto mb-2">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5"/></svg>
        </div>
        <div class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-amber-300">1.2M+</div>
        <div class="text-xs font-semibold text-amber-200 mt-1">Kg Harvest Tracked</div>
      </div>

      <div class="pt-4 sm:pt-0">
        <div class="w-10 h-10 rounded-full bg-teal-500/20 text-teal-300 flex items-center justify-center mx-auto mb-2">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
        </div>
        <div class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-teal-300">28.4%</div>
        <div class="text-xs font-semibold text-teal-200 mt-1">Avg. Net Income Lift</div>
      </div>

      <div class="pt-4 sm:pt-0">
        <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center mx-auto mb-2">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/></svg>
        </div>
        <div class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-emerald-400">42.8 T</div>
        <div class="text-xs font-semibold text-emerald-200 mt-1">Food Saved from Waste</div>
      </div>

    </div>
  </div>
</section>

<!-- 4. TECHNOLOGY THAT GROWS WITH YOU (FARMLAND + APP SHOWCASE) -->
<section class="py-12 mb-12">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
    
    <!-- Left Column: Copy & Feature Checkmarks -->
    <div class="lg:col-span-5 space-y-6">
      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono">
        🌿 SMART. SIMPLE. POWERFUL.
      </div>
      <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
        Technology that grows with you
      </h2>
      <p class="text-sm text-slate-600 leading-relaxed">
        Vunotho is your digital farming partner. Access real-time data, verified commercial buyers, and smart tools — all in one unified platform designed for Zimbabwean rural connectivity.
      </p>

      <div class="space-y-3 pt-2">
        <div class="flex items-start gap-3 text-xs text-slate-700 font-semibold">
          <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0 mt-0.5 font-bold">✓</div>
          <span><strong>Real-time field monitoring:</strong> Grade your harvest lots and track pickup readiness in seconds.</span>
        </div>
        <div class="flex items-start gap-3 text-xs text-slate-700 font-semibold">
          <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0 mt-0.5 font-bold">✓</div>
          <span><strong>2.5T load clustering:</strong> Automatically combine smaller harvest lots into consolidated high-capacity freight.</span>
        </div>
        <div class="flex items-start gap-3 text-xs text-slate-700 font-semibold">
          <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0 mt-0.5 font-bold">✓</div>
          <span><strong>Offline-first resilience:</strong> Never lose records during network drops with automatic background sync.</span>
        </div>
      </div>

      <div class="pt-3">
        <a href="/farmer.php" class="px-6 py-3 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition-all inline-flex items-center gap-2">
          <span>Open Farmer Desk</span>
          <span>→</span>
        </a>
      </div>
    </div>

    <!-- Right Column: Mobile Mockup + Farmland Dashboard Preview -->
    <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-12 gap-5 items-center">
      
      <!-- Mobile Smartphone Card Preview -->
      <div class="sm:col-span-5 bg-slate-950 p-3 rounded-3xl border-4 border-slate-800 shadow-2xl text-white space-y-3">
        <div class="flex justify-between items-center px-2 pt-1 border-b border-slate-800 pb-2">
          <div class="flex items-center gap-1.5">
            <img src="/images/vunotho_logo.png" class="w-5 h-5 rounded object-cover" alt="Vunotho" />
            <span class="text-[10px] font-bold">Vunotho Mobile</span>
          </div>
          <span class="text-[9px] text-emerald-400 font-mono">● Online</span>
        </div>

        <div class="bg-slate-900 p-3 rounded-2xl border border-slate-800 space-y-2 text-[11px]">
          <div class="flex justify-between text-slate-400"><span>Available Lots:</span><strong class="text-white font-mono">420 kg</strong></div>
          <div class="flex justify-between text-slate-400"><span>Weekly Payout:</span><strong class="text-amber-300 font-mono">$186.40</strong></div>
          <div class="flex justify-between text-slate-400"><span>Active Buyers:</span><strong class="text-teal-300 font-mono">12 verified</strong></div>
        </div>

        <div class="p-2.5 rounded-xl bg-emerald-950/80 border border-emerald-800 text-[10px] text-emerald-300 font-medium">
          Pickup scheduled: Gwanda ➔ Bulawayo corridor
        </div>
      </div>

      <!-- Farmland Aerial Overview Card (Using /images/farmland.png) -->
      <div class="sm:col-span-7 bg-white p-4 rounded-3xl border border-slate-200 shadow-xl space-y-3">
        <div class="flex justify-between items-center">
          <span class="font-extrabold text-xs text-slate-900">Farm Field Overview</span>
          <span class="text-[10px] text-slate-500 font-mono">GPS Clustered</span>
        </div>

        <!-- Farmland Image with Crisp Rounded Corners -->
        <div class="rounded-2xl overflow-hidden border border-slate-100 relative h-36">
          <img src="/images/farmland.png" alt="Farmland field aerial overview" class="w-full h-full object-cover" />
          <div class="absolute bottom-2 left-2 bg-slate-950/80 backdrop-blur-sm text-white px-2 py-0.5 rounded-md text-[10px] font-mono">
            Field A • 450 kg Ready
          </div>
        </div>

        <div class="grid grid-cols-2 gap-2 text-[10.5px]">
          <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
            <span class="text-slate-400 block text-[9.5px]">Benchmark Price</span>
            <strong class="text-slate-900 font-mono font-bold">$0.42 /kg</strong>
          </div>
          <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-100">
            <span class="text-emerald-700 block text-[9.5px]">Freight Discount</span>
            <strong class="text-emerald-800 font-mono font-bold">35% Saved</strong>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- 5. INTERACTIVE NET-RETURN PRICE ENGINE (RETAINED & POLISHED) -->
<section id="simulator" class="mb-14">
  <div class="bg-gradient-to-br from-[#071726] to-[#0B2032] text-white p-8 md:p-12 rounded-3xl border border-slate-800 shadow-2xl relative overflow-hidden">
    <div class="max-w-3xl relative z-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold font-mono mb-3">
        🧮 Interactive Pricing Engine
      </div>
      <h2 class="text-2xl md:text-4xl font-black tracking-tight mb-3">
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

<!-- 6. BOTTOM CALLOUT BANNER -->
<section class="mb-14">
  <div class="bg-white/90 backdrop-blur-md p-6 sm:p-8 rounded-3xl border border-emerald-100 shadow-md flex flex-col md:flex-row items-center justify-between gap-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
        <img src="/images/vunotho_logo.png" class="w-8 h-8 object-contain" alt="Vunotho" />
      </div>
      <div>
        <h3 class="font-extrabold text-slate-900 text-base">Together, let's build a greener and more prosperous tomorrow.</h3>
        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 font-semibold mt-1">
          <span>🌿 Sustainable Farming</span>
          <span>📈 Better Farmgate Yield</span>
          <span>👥 Stronger Communities</span>
        </div>
      </div>
    </div>
    <a href="/farmer.php" class="px-6 py-3 rounded-full bg-emerald-800 hover:bg-emerald-900 text-white font-black text-xs shadow-md transition-all whitespace-nowrap">
      Join Vunotho 🌿
    </a>
  </div>
</section>

<!-- Pricing Simulator Script -->
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
