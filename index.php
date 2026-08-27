<?php
/**
 * VUNOTHO PUBLIC LANDING PAGE (Server-Rendered PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';
require_once __DIR__ . '/api/db.php';

$currentUser = get_current_user_profile();
$pdo = get_db_connection();

// Fetch Top 10 Demands and Top 10 Listings for the Live Market Exchange
$demands = $pdo->query("SELECT * FROM demands ORDER BY created_at DESC LIMIT 10")->fetchAll();
$listings = $pdo->query("SELECT * FROM listings ORDER BY created_at DESC LIMIT 10")->fetchAll();

$pageTitle = 'Vunotho — Smallholder Agricultural Operating System';
require_once __DIR__ . '/includes/header.php';
?>

<!-- 1. HERO SECTION WITH 5-SECOND INTENTIONAL PROGRESS LOADER -->
<section id="hero-card" class="relative overflow-hidden rounded-3xl mb-12 p-8 md:p-14 text-white shadow-warm-xl border border-slate-700/50 min-h-[420px] flex flex-col justify-center" style="background-image: linear-gradient(to right, rgba(10, 25, 47, 0.94) 0%, rgba(15, 41, 66, 0.85) 60%, rgba(10, 25, 47, 0.92) 100%), url('/heroimage.jpg'); background-size: cover; background-position: center;">
  <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>
  <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>

  <!-- INTENTIONAL 3-SECOND HERO 3D ORBITAL LOADER (HIGH-IMPACT INTERACTIVE ANIMATION) -->
  <div id="hero-loader" class="relative z-20 flex flex-col items-center justify-center py-8 md:py-14 text-center space-y-6 transition-all duration-700">
    
    <!-- Large 3D Multi-Layered Animated Orbital System -->
    <div class="orbital-system">
      <!-- Breathing Ambient Halo Glow -->
      <div class="orbital-core-glow"></div>
      
      <!-- Outer Glowing Dashed 3D Ring with Emerald & Teal Satellites -->
      <div class="orbital-ring-outer">
        <div class="satellite-emerald"></div>
        <div class="satellite-teal"></div>
      </div>

      <!-- Middle Golden Counter-Rotating Ring with Amber Satellites -->
      <div class="orbital-ring-middle">
        <div class="satellite-amber"></div>
        <div class="satellite-orange"></div>
      </div>

      <!-- Inner Dashed Fast Rotation Ring -->
      <div class="orbital-ring-inner"></div>

      <!-- Floating 3D Official Vunotho Logo Core -->
      <div class="orbital-core overflow-hidden">
        <img src="/images/vunotho_logo.jpg" alt="Vunotho Core Emblem" class="w-full h-full object-cover rounded-2xl" />
      </div>
    </div>

    <!-- Scanning Progress Bar with Clean Blinking Indicator (No Text) -->
    <div class="w-80 md:w-[440px] max-w-full space-y-3 flex flex-col items-center">
      <div class="inline-flex items-center justify-center px-8 py-2 rounded-full bg-emerald-950/60 border border-emerald-500/30 shadow-sm min-w-[220px] h-7">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
      </div>
      
      <!-- Scanning Progress Bar -->
      <div class="w-full h-2 bg-slate-900/90 rounded-full overflow-hidden p-0.5 border border-slate-700/80 shadow-inner">
        <div id="hero-progress-bar" class="h-full bg-gradient-to-r from-emerald-500 via-amber-400 to-emerald-400 rounded-full relative" style="width: 10%; transition: width 0.05s linear;">
          <div class="absolute right-0 top-0 bottom-0 w-4 bg-white rounded-full blur-[2px] opacity-90"></div>
        </div>
      </div>
      
      <p class="text-xs text-slate-300 font-bold font-mono tracking-wide">Vunotho by Enactus...</p>
    </div>
  </div>

  <!-- REVEALED HERO CONTENT (FADES IN SMOOTHLY AT 5 SECONDS) -->
  <div id="hero-content" class="relative z-10 max-w-3xl opacity-0 scale-95 pointer-events-none transition-all duration-700 ease-out hidden">
    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-extrabold tracking-wide uppercase mb-6">
      🛡️ Enactus International Blueprint • Agricultural OS
    </div>

    <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight mb-6">
      Turning Smallholder Produce into <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-amber-300 to-teal-300">Protected Economic Value</span>
    </h1>

    <p class="text-base md:text-lg text-slate-300 mb-8 leading-relaxed">
      Eliminating predatory broker exploitation across Zimbabwe with transparent farmgate Net Returns, pooled 2.5T rural load logistics, and guaranteed mobile money settlements on collection.
    </p>

    <div class="flex flex-wrap items-center gap-3.5">
      <a href="/login.php?mode=register&role=farmer" class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-glow-emerald transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
        🌱 Join as Smallholder Farmer
      </a>
      <a href="/login.php?mode=signin&role=buyer" class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-100 font-bold text-sm border border-slate-600 transition-all flex items-center gap-2">
        🏢 Commercial Buyer Sourcing
      </a>
      <a href="/login.php?mode=signin&role=transporter" class="px-5 py-3 rounded-xl bg-amber-600/20 hover:bg-amber-600/30 text-amber-300 border border-amber-500/30 font-bold text-sm transition-all flex items-center gap-2">
        🚚 Haulier Fleet Desk
      </a>
    </div>

    <!-- Live Market Ticker inside Hero -->
    <div class="mt-10 pt-6 border-t border-slate-700/60 ticker-wrap">
      <div class="ticker-track text-xs font-semibold text-slate-300 space-x-8">
        <?php foreach ($demands as $d): ?>
          <span class="inline-flex items-center gap-2">
            <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">BUYER DEMAND</span>
            <strong><?= htmlspecialchars($d['crop']) ?></strong>: <?= number_format($d['target_quantity_kg']) ?> kg @ <span class="text-emerald-400 font-bold font-mono">$<?= number_format($d['offered_price_per_kg'], 2) ?>/kg</span> (<?= htmlspecialchars($d['delivery_hub'] ?? 'Harare') ?>)
          </span>
        <?php endforeach; ?>
        <?php foreach ($listings as $l): ?>
          <span class="inline-flex items-center gap-2">
            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">FARMER SUPPLY</span>
            <strong><?= htmlspecialchars($l['crop']) ?></strong>: <?= number_format($l['quantity_kg']) ?> kg ready in <strong><?= htmlspecialchars($l['district'] ?? 'Nyanga') ?></strong>
          </span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- 2. LIVE MARKETPLACE EXCHANGE (TOP 10 FARMER PRODUCE + TOP 10 BUYER DEMANDS) -->
<section class="mb-14">
  <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-extrabold mb-2 font-mono">
        ⚡ Live Market Exchange • Real-Time Order Book
      </div>
      <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
        Current Farmgate Supply & Commercial Demand
      </h2>
      <p class="text-sm text-slate-600 mt-1">
        Direct matching between verified smallholder harvests and commercial procurement purchase orders.
      </p>
    </div>
    <div class="flex items-center gap-3">
      <a href="/login.php?role=farmer" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all">
        + List Harvest
      </a>
      <a href="/login.php?role=buyer" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition-all">
        + Post Sourcing Demand
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Column 1: Available Produce From Farmers (Top 10) -->
    <div class="bg-white/90 backdrop-blur-md p-6 md:p-7 rounded-3xl border border-slate-200 shadow-warm-md flex flex-col justify-between">
      <div>
        <div class="flex justify-between items-center pb-4 mb-4 border-b border-slate-100">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
            <h3 class="font-extrabold text-base text-slate-900">Available Smallholder Produce</h3>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold font-mono">
            <?= count($listings) ?> Active Lots
          </span>
        </div>

        <div class="space-y-3">
          <?php if (empty($listings)): ?>
            <div class="text-center py-10 text-slate-400 text-xs font-semibold">No active produce lots registered currently.</div>
          <?php else: ?>
            <?php foreach ($listings as $lot): ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 hover:border-emerald-400 hover:bg-emerald-50/20 transition-all flex items-center justify-between gap-3">
                <div class="space-y-0.5">
                  <div class="flex items-center gap-2">
                    <strong class="text-slate-900 font-bold text-sm"><?= htmlspecialchars($lot['crop']) ?></strong>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold"><?= htmlspecialchars($lot['quality'] ?? 'Grade A') ?></span>
                  </div>
                  <p class="text-xs text-slate-500">
                    Farmer: <strong class="text-slate-700"><?= htmlspecialchars($lot['farmer_name']) ?></strong> • 📍 <span class="font-semibold text-slate-600"><?= htmlspecialchars($lot['district'] ?? 'Nyanga') ?></span>
                  </p>
                </div>
                <div class="text-right">
                  <div class="font-black text-slate-900 text-sm font-mono"><?= number_format($lot['quantity_kg']) ?> kg</div>
                  <span class="text-[10px] text-emerald-700 font-bold">Ready for Pickup</span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center">
        <span class="text-xs text-slate-500">Have produce ready for sale?</span>
        <a href="/login.php?role=farmer" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 underline flex items-center gap-1">
          Sell Your Harvest Lot →
        </a>
      </div>
    </div>

    <!-- Column 2: Commercial Buyer Demands (Top 10) -->
    <div class="bg-white/90 backdrop-blur-md p-6 md:p-7 rounded-3xl border border-slate-200 shadow-warm-md flex flex-col justify-between">
      <div>
        <div class="flex justify-between items-center pb-4 mb-4 border-b border-slate-100">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
            <h3 class="font-extrabold text-base text-slate-900">Commercial Off-taker Demands</h3>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-xs font-bold font-mono">
            <?= count($demands) ?> Purchase Orders
          </span>
        </div>

        <div class="space-y-3">
          <?php if (empty($demands)): ?>
            <div class="text-center py-10 text-slate-400 text-xs font-semibold">No open purchase demands currently broadcast.</div>
          <?php else: ?>
            <?php foreach ($demands as $dem): ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 hover:border-amber-400 hover:bg-amber-50/20 transition-all flex items-center justify-between gap-3">
                <div class="space-y-0.5">
                  <div class="flex items-center gap-2">
                    <strong class="text-slate-900 font-bold text-sm"><?= htmlspecialchars($dem['crop']) ?></strong>
                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Buying</span>
                  </div>
                  <p class="text-xs text-slate-500">
                    Buyer: <strong class="text-slate-700"><?= htmlspecialchars($dem['buyer_name']) ?></strong> • 🎯 <span class="font-semibold text-slate-600"><?= htmlspecialchars($dem['delivery_hub']) ?></span>
                  </p>
                </div>
                <div class="text-right">
                  <div class="font-black text-amber-800 text-base font-mono">$<?= number_format($dem['offered_price_per_kg'], 2) ?><span class="text-xs font-normal text-slate-500">/kg</span></div>
                  <span class="text-[10px] text-slate-600 font-semibold font-mono">Target: <?= number_format($dem['target_quantity_kg']) ?> kg</span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center">
        <span class="text-xs text-slate-500">Are you a commercial wholesale buyer?</span>
        <a href="/login.php?role=buyer" class="text-xs font-bold text-amber-700 hover:text-amber-800 underline flex items-center gap-1">
          Post Commercial Demand →
        </a>
      </div>
    </div>

  </div>
</section>

<!-- 3. POPULAR HAULIER FREIGHT ROUTES & 2.5T LOAD AGGREGATION -->
<section class="mb-14">
  <div class="bg-gradient-to-br from-white via-[#FAF8F5] to-[#F1F5F9] p-6 md:p-10 rounded-3xl border border-slate-200 shadow-warm-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 pb-6 border-b border-slate-200">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-orange-800 text-xs font-extrabold mb-2 font-mono">
          🚚 Rural Freight Logistics Desk • 2.5T Fleet Pooling
        </div>
        <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
          Active Clustered 2.5-Tonne Freight Routes
        </h2>
        <p class="text-sm text-slate-600 mt-1 max-w-2xl">
          Aggregating multiple smallholder harvests along primary agricultural corridors to eliminate empty return trips and reduce transport fees by 35%.
        </p>
      </div>

      <a href="/login.php?role=transporter" class="px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold text-xs shadow-glow-orange transition-all flex items-center gap-2">
        Register Fleet / Accept Manifest →
      </a>
    </div>

    <!-- 4 Popular Route Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
      
      <!-- Route 1: Nyanga to Harare -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-warm-sm flex flex-col justify-between hover:border-orange-400 transition-all">
        <div>
          <div class="flex justify-between items-start mb-2">
            <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-700 font-mono text-[10px] font-bold">ROUTE-NY-HRE</span>
            <span class="text-xs font-black text-emerald-700 font-mono">86% Full</span>
          </div>
          <h4 class="font-extrabold text-slate-900 text-sm">Nyanga Highlands ➔ Mbare Musika</h4>
          <p class="text-xs text-slate-500 mt-1">Distance: <strong>280 km</strong> • Tomatoes & Table Potatoes</p>
          
          <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden my-3">
            <div class="h-full bg-gradient-to-r from-orange-500 to-amber-500 rounded-full" style="width: 86%;"></div>
          </div>
          <div class="text-[11px] text-slate-600 font-mono">Pooled: <strong>2,150 / 2,500 kg</strong></div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
          <span class="text-emerald-700 font-bold">35% Cost Saved</span>
          <a href="/login.php?role=transporter" class="font-bold text-orange-700 hover:underline">Claim Route →</a>
        </div>
      </div>

      <!-- Route 2: Gwanda to Bulawayo -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-warm-sm flex flex-col justify-between hover:border-orange-400 transition-all">
        <div>
          <div class="flex justify-between items-start mb-2">
            <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-700 font-mono text-[10px] font-bold">ROUTE-GW-BYO</span>
            <span class="text-xs font-black text-emerald-700 font-mono">72% Full</span>
          </div>
          <h4 class="font-extrabold text-slate-900 text-sm">Gwanda Farmlands ➔ Belmont Wholesale</h4>
          <p class="text-xs text-slate-500 mt-1">Distance: <strong>125 km</strong> • Leafy Greens & Onions</p>
          
          <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden my-3">
            <div class="h-full bg-gradient-to-r from-orange-500 to-amber-500 rounded-full" style="width: 72%;"></div>
          </div>
          <div class="text-[11px] text-slate-600 font-mono">Pooled: <strong>1,800 / 2,500 kg</strong></div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
          <span class="text-emerald-700 font-bold">38% Cost Saved</span>
          <a href="/login.php?role=transporter" class="font-bold text-orange-700 hover:underline">Claim Route →</a>
        </div>
      </div>

      <!-- Route 3: Mutasa to Mutare -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-warm-sm flex flex-col justify-between hover:border-orange-400 transition-all">
        <div>
          <div class="flex justify-between items-start mb-2">
            <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-700 font-mono text-[10px] font-bold">ROUTE-MU-MTR</span>
            <span class="text-xs font-black text-emerald-700 font-mono">96% Full</span>
          </div>
          <h4 class="font-extrabold text-slate-900 text-sm">Mutasa Valley ➔ Mutare Regional Depot</h4>
          <p class="text-xs text-slate-500 mt-1">Distance: <strong>65 km</strong> • Tomatoes & Green Peppers</p>
          
          <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden my-3">
            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full" style="width: 96%;"></div>
          </div>
          <div class="text-[11px] text-slate-600 font-mono">Pooled: <strong>2,400 / 2,500 kg</strong></div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
          <span class="text-emerald-700 font-bold">Dispatched Today</span>
          <a href="/login.php?role=transporter" class="font-bold text-orange-700 hover:underline">Claim Route →</a>
        </div>
      </div>

      <!-- Route 4: Goromonzi to Harare -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-warm-sm flex flex-col justify-between hover:border-orange-400 transition-all">
        <div>
          <div class="flex justify-between items-start mb-2">
            <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-700 font-mono text-[10px] font-bold">ROUTE-GO-HRE</span>
            <span class="text-xs font-black text-amber-700 font-mono">64% Full</span>
          </div>
          <h4 class="font-extrabold text-slate-900 text-sm">Goromonzi Belt ➔ Lusaka Ave Market</h4>
          <p class="text-xs text-slate-500 mt-1">Distance: <strong>45 km</strong> • Cabbages & Squash</p>
          
          <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden my-3">
            <div class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full" style="width: 64%;"></div>
          </div>
          <div class="text-[11px] text-slate-600 font-mono">Pooled: <strong>1,600 / 2,500 kg</strong></div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
          <span class="text-emerald-700 font-bold">Accepting Stops</span>
          <a href="/login.php?role=transporter" class="font-bold text-orange-700 hover:underline">Claim Route →</a>
        </div>
      </div>

    </div>

    <!-- Participant CTAs -->
    <div class="mt-8 pt-6 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4 text-xs">
      <div class="flex items-center gap-4 text-slate-600 font-medium">
        <span>🌾 <strong>Farmers:</strong> Log your harvest to join the next pooled route</span>
        <span>🏢 <strong>Buyers:</strong> Track inbound clustered freight</span>
      </div>
      <div class="flex items-center gap-3">
        <a href="/login.php?role=farmer" class="font-bold text-emerald-700 hover:underline">Pool My Produce →</a>
        <span class="text-slate-300">|</span>
        <a href="/login.php?role=buyer" class="font-bold text-amber-700 hover:underline">Track Inbound Freight →</a>
      </div>
    </div>
  </div>
</section>

<!-- 4. INTERACTIVE PRICE INTELLIGENCE SIMULATOR -->
<section class="bg-white/95 backdrop-blur-md p-6 md:p-10 rounded-3xl border border-slate-200 shadow-warm-xl mb-14">
  <div class="max-w-2xl mb-8">
    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-extrabold mb-2 font-mono">
      🧮 Net-Return Formula Engine
    </div>
    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
      Transparent Net-Return Decision Simulator
    </h2>
    <p class="text-sm text-slate-600 mt-1">
      See your exact net payout before harvesting: <strong class="text-slate-900">Gross Price − Pooled Freight − 4% Fee = Guaranteed Net Return</strong>.
    </p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
    <div class="lg:col-span-5 space-y-6">
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Commodity</label>
        <select id="landing-crop-select" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 font-bold text-slate-900 shadow-sm" onchange="updateCalculator()">
          <option value="Tomatoes" selected>Tomatoes (Avg. $0.45/kg • $8–$16/sandak)</option>
          <option value="Table Potatoes">Table Potatoes (Avg. $0.55/kg • $6–$10/pocket)</option>
          <option value="Onions">Onions (Avg. $0.60/kg • $4.50–$8/pocket)</option>
          <option value="Leafy Greens">Leafy Greens / Tsunga (Avg. $0.50/kg • $2–$4/bundle)</option>
          <option value="Butternut Squash">Butternut Squash (Avg. $0.40/kg)</option>
          <option value="Green Peppers">Green Peppers (Avg. $0.70/kg)</option>
        </select>
      </div>

      <div>
        <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
          <span>Harvest Volume</span>
          <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-mono" id="val-sim-weight">400 kg</span>
        </div>
        <input type="range" id="sim-weight" min="50" max="2500" step="25" value="400" class="vunotho-slider" oninput="updateCalculator()" />
      </div>

      <div>
        <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
          <span>Distance to Wholesale Market Hub</span>
          <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-mono" id="val-sim-dist">35 km</span>
        </div>
        <input type="range" id="sim-dist" min="10" max="120" step="5" value="35" class="vunotho-slider" oninput="updateCalculator()" />
      </div>
    </div>

    <div class="lg:col-span-7 bg-slate-900 text-white p-6 md:p-8 rounded-2xl shadow-warm-lg">
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center pb-6 border-b border-slate-800">
        <div>
          <div class="text-xs text-slate-400 font-semibold mb-1">Gross Value</div>
          <div class="text-lg md:text-xl font-black text-slate-100 font-mono" id="sim-gross">$180.00</div>
        </div>
        <div>
          <div class="text-xs text-amber-400 font-semibold mb-1">Pooled Freight</div>
          <div class="text-lg md:text-xl font-black text-amber-400 font-mono" id="sim-transport">-$13.65</div>
        </div>
        <div>
          <div class="text-xs text-slate-400 font-semibold mb-1">Fee (4%)</div>
          <div class="text-lg md:text-xl font-black text-slate-400 font-mono" id="sim-fee">-$7.20</div>
        </div>
        <div class="bg-emerald-500/20 p-2.5 rounded-xl border border-emerald-500/40">
          <div class="text-xs text-emerald-300 font-bold mb-1">Net Take-Home</div>
          <div class="text-xl md:text-2xl font-black text-emerald-400 font-mono" id="sim-net">$159.15</div>
        </div>
      </div>

      <div class="mt-6 flex items-center justify-between text-xs text-slate-300">
        <span class="inline-flex items-center gap-1 text-emerald-400 font-bold" id="sim-savings">
          ✓ $7.35 Freight Saved via 2.5T Pooling
        </span>
        <span class="text-slate-400">Guaranteed Mobile Money Transfer</span>
      </div>
    </div>
  </div>
</section>

<!-- 5. 4-TIER CIRCULAR VALUE RECOVERY (SDG 12.3) — EXPANDED TALL CARDS WITH IMAGE PLACEHOLDERS -->
<section class="bg-white/90 backdrop-blur-md p-6 md:p-10 rounded-3xl border border-slate-200 shadow-warm-md mb-14">
  <div class="max-w-2xl mb-8">
    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-teal-100 text-teal-800 text-xs font-extrabold mb-2 font-mono">
      ♻️ SDG 12.3 Circular Bioeconomy
    </div>
    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
      4-Tier Post-Harvest Value Recovery
    </h2>
    <p class="text-sm text-slate-600 mt-1">
      Zero produce discarded in open dump sites. Every kilogram is routed to its highest-value commercial destination.
    </p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <!-- Tier 1 Card -->
    <div class="bg-slate-50 p-5 rounded-3xl border border-slate-200 border-t-8 border-t-emerald-500 shadow-warm-sm flex flex-col justify-between min-h-[520px] hover:shadow-warm-lg hover:border-emerald-400 transition-all group">
      <div>
        <!-- Image Container / Picture Frame -->
        <div class="w-full h-44 rounded-2xl overflow-hidden relative mb-4 border border-slate-200 shadow-inner bg-slate-900">
          <img src="/images/tier_placeholder.jpg" alt="Tier 1 Produce" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500" />
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
          <span class="absolute bottom-2 left-2 px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-[10px] font-black font-mono shadow-sm">
            TIER 1 • 100% VALUE
          </span>
        </div>

        <span class="text-xs font-extrabold text-emerald-800 uppercase tracking-wider font-mono">Peak Commercial Return</span>
        <h3 class="font-black text-slate-900 text-lg mt-1 mb-2">Fresh Wholesale & Supermarkets</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Pristine, cosmetic-grade fruits and vegetables meeting rigorous supermarket and terminal wholesale specifications.
        </p>
      </div>

      <div class="mt-4 pt-4 border-t border-slate-200/80 space-y-2">
        <div class="text-[11px] font-bold text-slate-700">Representative Produce:</div>
        <div class="flex flex-wrap gap-1">
          <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-semibold">Roma Tomatoes</span>
          <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-semibold">15kg Potatoes</span>
          <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-semibold">Tsunga Greens</span>
        </div>
      </div>
    </div>

    <!-- Tier 2 Card -->
    <div class="bg-slate-50 p-5 rounded-3xl border border-slate-200 border-t-8 border-t-amber-500 shadow-warm-sm flex flex-col justify-between min-h-[520px] hover:shadow-warm-lg hover:border-amber-400 transition-all group">
      <div>
        <!-- Image Container / Picture Frame -->
        <div class="w-full h-44 rounded-2xl overflow-hidden relative mb-4 border border-slate-200 shadow-inner bg-slate-900">
          <img src="/images/tier_placeholder.jpg" alt="Tier 2 Produce" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500" />
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
          <span class="absolute bottom-2 left-2 px-2.5 py-1 rounded-lg bg-amber-600 text-white text-[10px] font-black font-mono shadow-sm">
            TIER 2 • 70–80% VALUE
          </span>
        </div>

        <span class="text-xs font-extrabold text-amber-800 uppercase tracking-wider font-mono">Agro-Processing Offtake</span>
        <h3 class="font-black text-slate-900 text-lg mt-1 mb-2">Puree, Drying & Processing</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Cosmetically irregular or minor blemished produce diverted directly to food processing plants for high-value conversion.
        </p>
      </div>

      <div class="mt-4 pt-4 border-t border-slate-200/80 space-y-2">
        <div class="text-[11px] font-bold text-slate-700">Representative Produce:</div>
        <div class="flex flex-wrap gap-1">
          <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-semibold">Tomato Puree/Paste</span>
          <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-semibold">Potato Crisps</span>
          <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-semibold">Dried Squash</span>
        </div>
      </div>
    </div>

    <!-- Tier 3 Card -->
    <div class="bg-slate-50 p-5 rounded-3xl border border-slate-200 border-t-8 border-t-orange-500 shadow-warm-sm flex flex-col justify-between min-h-[520px] hover:shadow-warm-lg hover:border-orange-400 transition-all group">
      <div>
        <!-- Image Container / Picture Frame -->
        <div class="w-full h-44 rounded-2xl overflow-hidden relative mb-4 border border-slate-200 shadow-inner bg-slate-900">
          <img src="/images/tier_placeholder.jpg" alt="Tier 3 Produce" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500" />
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
          <span class="absolute bottom-2 left-2 px-2.5 py-1 rounded-lg bg-orange-600 text-white text-[10px] font-black font-mono shadow-sm">
            TIER 3 • 40–50% VALUE
          </span>
        </div>

        <span class="text-xs font-extrabold text-orange-800 uppercase tracking-wider font-mono">Animal Nutrition</span>
        <h3 class="font-black text-slate-900 text-lg mt-1 mb-2">Formulated Livestock Feed</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Overripe or off-size farm produce channelled to livestock feed compounders for cattle, pig, and poultry nutritional rations.
        </p>
      </div>

      <div class="mt-4 pt-4 border-t border-slate-200/80 space-y-2">
        <div class="text-[11px] font-bold text-slate-700">Representative Produce:</div>
        <div class="flex flex-wrap gap-1">
          <span class="px-2 py-0.5 rounded-md bg-orange-100 text-orange-800 text-[10px] font-semibold">Cattle Feed Meal</span>
          <span class="px-2 py-0.5 rounded-md bg-orange-100 text-orange-800 text-[10px] font-semibold">Pig Grower Slurry</span>
          <span class="px-2 py-0.5 rounded-md bg-orange-100 text-orange-800 text-[10px] font-semibold">Silage Biomass</span>
        </div>
      </div>
    </div>

    <!-- Tier 4 Card -->
    <div class="bg-slate-50 p-5 rounded-3xl border border-slate-200 border-t-8 border-t-teal-500 shadow-warm-sm flex flex-col justify-between min-h-[520px] hover:shadow-warm-lg hover:border-teal-400 transition-all group">
      <div>
        <!-- Image Container / Picture Frame -->
        <div class="w-full h-44 rounded-2xl overflow-hidden relative mb-4 border border-slate-200 shadow-inner bg-slate-900">
          <img src="/images/tier_placeholder.jpg" alt="Tier 4 Produce" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500" />
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
          <span class="absolute bottom-2 left-2 px-2.5 py-1 rounded-lg bg-teal-600 text-white text-[10px] font-black font-mono shadow-sm">
            TIER 4 • SOIL RECOVERY
          </span>
        </div>

        <span class="text-xs font-extrabold text-teal-800 uppercase tracking-wider font-mono">Organic Circularity</span>
        <h3 class="font-black text-slate-900 text-lg mt-1 mb-2">Bio-Compost Fertilizer</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Degraded organic matter converted into nitrogen-rich bio-fertilizer and compost, returning vital nutrients to Zimbabwean soils.
        </p>
      </div>

      <div class="mt-4 pt-4 border-t border-slate-200/80 space-y-2">
        <div class="text-[11px] font-bold text-slate-700">Representative Produce:</div>
        <div class="flex flex-wrap gap-1">
          <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 text-[10px] font-semibold">Enriched Compost</span>
          <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 text-[10px] font-semibold">Organic Humus</span>
          <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 text-[10px] font-semibold">Soil Revitalizer</span>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- 6. ANIMATED LARGE VUNOTHO VALUE CHAIN CARD (ALIVE & MOVING) -->
<section class="mb-16">
  <div class="bg-gradient-to-br from-[#0B1D2E] via-[#0F2942] to-[#081523] text-white p-8 md:p-12 rounded-3xl border border-slate-700/80 shadow-warm-xl relative overflow-hidden">
    
    <!-- Ambient Pulse Spheres -->
    <div class="absolute -top-32 -left-32 w-80 h-80 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none animate-pulse"></div>
    <div class="absolute -bottom-32 -right-32 w-80 h-80 rounded-full bg-amber-500/15 blur-3xl pointer-events-none animate-pulse" style="animation-delay: 1.5s;"></div>

    <div class="relative z-10">
      <!-- Section Header -->
      <div class="max-w-3xl mb-10">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-extrabold tracking-wide uppercase mb-3 font-mono">
          ⚡ Interactive Operating Architecture
        </div>
        <h2 class="text-2xl md:text-4xl font-black tracking-tight text-white">
          The Living Vunotho Value Chain
        </h2>
        <p class="text-sm md:text-base text-slate-300 mt-2">
          An automated, decentralized pipeline connecting farmgates directly to wholesale terminals and agro-processors.
        </p>
      </div>

      <!-- 4-Node Animated Value Chain Pipeline -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
        
        <!-- Animated Connecting Energy Line (Desktop) -->
        <div class="hidden md:block absolute top-1/2 left-10 right-10 h-1 bg-gradient-to-r from-emerald-500 via-amber-400 via-orange-400 to-teal-400 opacity-30 -translate-y-1/2 z-0"></div>
        
        <!-- Node 1: Smallholder Harvest & Grading -->
        <div class="relative z-10 bg-slate-900/80 backdrop-blur-md p-6 rounded-2xl border border-emerald-500/40 shadow-glow-emerald hover:border-emerald-400 transition-all transform hover:-translate-y-1 group">
          <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 border border-emerald-400/40 text-emerald-400 flex items-center justify-center text-2xl font-black mb-4 group-hover:scale-110 transition-all">
            🌱
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400 font-mono">Step 01 • Farmgate</span>
          <h4 class="text-lg font-black text-white mt-1 mb-2">4-Tier Harvest Logging</h4>
          <p class="text-xs text-slate-300 leading-relaxed">
            Farmers log harvest specs offline. Produce is graded into Supermarket, Processing, Feed, or Compost tiers.
          </p>
          <div class="mt-4 pt-3 border-t border-slate-800 flex items-center gap-1.5 text-[11px] text-emerald-400 font-mono">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            Zero Internet Needed
          </div>
        </div>

        <!-- Node 2: 2.5T Load Pooling Algorithm -->
        <div class="relative z-10 bg-slate-900/80 backdrop-blur-md p-6 rounded-2xl border border-amber-500/40 shadow-glow-amber hover:border-amber-400 transition-all transform hover:-translate-y-1 group">
          <div class="w-14 h-14 rounded-2xl bg-amber-500/20 border border-amber-400/40 text-amber-400 flex items-center justify-center text-2xl font-black mb-4 group-hover:scale-110 transition-all">
            🚚
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-400 font-mono">Step 02 • Logistics</span>
          <h4 class="text-lg font-black text-white mt-1 mb-2">2.5T Load Aggregation</h4>
          <p class="text-xs text-slate-300 leading-relaxed">
            Algorithm clusters fragmented harvests by crop and corridor into full 2.5-tonne rural light truck manifests.
          </p>
          <div class="mt-4 pt-3 border-t border-slate-800 flex items-center gap-1.5 text-[11px] text-amber-400 font-mono">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
            35% Transport Saved
          </div>
        </div>

        <!-- Node 3: Commercial Wholesale Offtake -->
        <div class="relative z-10 bg-slate-900/80 backdrop-blur-md p-6 rounded-2xl border border-orange-500/40 shadow-glow-orange hover:border-orange-400 transition-all transform hover:-translate-y-1 group">
          <div class="w-14 h-14 rounded-2xl bg-orange-500/20 border border-orange-400/40 text-orange-400 flex items-center justify-center text-2xl font-black mb-4 group-hover:scale-110 transition-all">
            🏢
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-orange-400 font-mono">Step 03 • Offtake</span>
          <h4 class="text-lg font-black text-white mt-1 mb-2">Terminal Hub Matching</h4>
          <p class="text-xs text-slate-300 leading-relaxed">
            Wholesalers & food processors receive graded produce directly at guaranteed contract prices without brokers.
          </p>
          <div class="mt-4 pt-3 border-t border-slate-800 flex items-center gap-1.5 text-[11px] text-orange-400 font-mono">
            <span class="w-2 h-2 rounded-full bg-orange-400 animate-ping"></span>
            100% Waste Diverted
          </div>
        </div>

        <!-- Node 4: Instant Mobile Money Settlement -->
        <div class="relative z-10 bg-slate-900/80 backdrop-blur-md p-6 rounded-2xl border border-teal-500/40 shadow-glow-teal hover:border-teal-400 transition-all transform hover:-translate-y-1 group">
          <div class="w-14 h-14 rounded-2xl bg-teal-500/20 border border-teal-400/40 text-teal-400 flex items-center justify-center text-2xl font-black mb-4 group-hover:scale-110 transition-all">
            📱
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-400 font-mono">Step 04 • Settlement</span>
          <h4 class="text-lg font-black text-white mt-1 mb-2">EcoCash Instant Payout</h4>
          <p class="text-xs text-slate-300 leading-relaxed">
            Funds disburse straight to farmer mobile wallets upon digital receipt verification at collection point.
          </p>
          <div class="mt-4 pt-3 border-t border-slate-800 flex items-center gap-1.5 text-[11px] text-teal-400 font-mono">
            <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
            Zero Broker Deductions
          </div>
        </div>

      </div>

      <!-- Bottom Interactive Status Stream -->
      <div class="mt-10 p-5 rounded-2xl bg-slate-950/60 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></div>
          <span class="text-slate-300 font-mono">Real-Time Zimbabwe Agricultural Pulse: <strong class="text-emerald-400">Active Corridors: Nyanga, Gwanda, Mutasa, Goromonzi</strong></span>
        </div>
        <a href="/why-vunotho.php" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold transition-all border border-white/20">
          Learn How Vunotho Works →
        </a>
      </div>

    </div>
  </div>
</section>

<script>
  // 1. INTENTIONAL 3-SECOND HERO 3D ORBITAL LOADER SCRIPT
  document.addEventListener('DOMContentLoaded', () => {
    const startTime = Date.now();
    const duration = 3000; // Exactly 3.0 seconds

    const timer = setInterval(() => {
      const elapsed = Date.now() - startTime;
      const progress = Math.min(100, (elapsed / duration) * 100);
      
      const bar = document.getElementById('hero-progress-bar');
      if (bar) bar.style.width = `${progress}%`;

      if (elapsed >= duration) {
        clearInterval(timer);
        const loader = document.getElementById('hero-loader');
        const content = document.getElementById('hero-content');
        if (loader && content) {
          loader.classList.add('opacity-0', 'scale-95');
          setTimeout(() => {
            loader.style.display = 'none';
            content.classList.remove('hidden', 'pointer-events-none');
            // Trigger reflow for smooth transition
            void content.offsetWidth;
            content.classList.remove('opacity-0', 'scale-95');
            content.classList.add('opacity-100', 'scale-100');
          }, 300);
        }
      }
    }, 40);
  });

  // 2. NET-RETURN SIMULATOR SCRIPT
  function updateCalculator() {
    const crop = document.getElementById('landing-crop-select').value;
    const weightKg = Number(document.getElementById('sim-weight').value);
    const distKm = Number(document.getElementById('sim-dist').value);

    document.getElementById('val-sim-weight').textContent = `${weightKg.toLocaleString()} kg`;
    document.getElementById('val-sim-dist').textContent = `${distKm} km`;

    const cropPrices = {
      'Tomatoes': 0.45,
      'Table Potatoes': 0.55,
      'Onions': 0.60,
      'Leafy Greens': 0.50,
      'Butternut Squash': 0.40,
      'Green Peppers': 0.70
    };

    const unitPrice = cropPrices[crop] || 0.45;
    const breakdown = window.vunothoPricing.calculateNetReturn(unitPrice, weightKg, distKm, true);

    document.getElementById('sim-gross').textContent = `$${breakdown.grossTotal.toFixed(2)}`;
    document.getElementById('sim-transport').textContent = `-$${breakdown.transportTotal.toFixed(2)}`;
    document.getElementById('sim-fee').textContent = `-$${breakdown.platformFeeTotal.toFixed(2)}`;
  }
  document.addEventListener('DOMContentLoaded', updateCalculator);
</script>

<!-- 7. ACCESS / DOWNLOAD VUNOTHO MULTI-PLATFORM BANNER CARD -->
<section class="mb-14">
  <div class="bg-gradient-to-br from-[#0F2942] via-[#0A192F] to-[#060D17] text-white p-8 md:p-12 rounded-3xl border border-slate-700/80 shadow-warm-xl relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8">
    <div class="space-y-4 max-w-2xl relative z-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold font-mono uppercase">
        📲 Offline-First Multi-Platform App
      </div>
      <h2 class="text-2xl md:text-4xl font-black tracking-tight leading-tight">
        Access Vunotho on Android, iOS, Windows & Linux
      </h2>
      <p class="text-xs md:text-sm text-slate-300 leading-relaxed">
        Use Vunotho even when cellular connectivity is slow or intermittent. Log harvests with zero data and auto-sync when you reconnect.
      </p>
      
      <!-- OS Icons Row -->
      <div class="flex flex-wrap items-center gap-3 pt-2">
        <span class="px-3.5 py-1.5 rounded-xl bg-slate-800/90 border border-slate-600 text-xs font-bold text-slate-200 flex items-center gap-2 shadow-sm">
          <span>🤖</span> Android
        </span>
        <span class="px-3.5 py-1.5 rounded-xl bg-slate-800/90 border border-slate-600 text-xs font-bold text-slate-200 flex items-center gap-2 shadow-sm">
          <span>🍏</span> iPhone / iOS
        </span>
        <span class="px-3.5 py-1.5 rounded-xl bg-slate-800/90 border border-slate-600 text-xs font-bold text-slate-200 flex items-center gap-2 shadow-sm">
          <span>🪟</span> Windows
        </span>
        <span class="px-3.5 py-1.5 rounded-xl bg-slate-800/90 border border-slate-600 text-xs font-bold text-slate-200 flex items-center gap-2 shadow-sm">
          <span>🐧</span> Linux
        </span>
      </div>
    </div>

    <div class="relative z-10 flex flex-col items-center sm:items-end gap-3 whitespace-nowrap">
      <a href="/access.php" class="px-7 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm shadow-glow-emerald transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
        <span>⬇</span> Download Vunotho App (Access) →
      </a>
      <span class="text-[11px] text-slate-400 font-mono">100% Free for Smallholder Farmers</span>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
