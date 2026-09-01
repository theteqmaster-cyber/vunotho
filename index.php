<?php
/**
 * VUNOTHO INSPIRING ENTERPRISE LANDING PAGE (PHP & Modern Design System)
 */
require_once __DIR__ . '/api/session.php';
require_once __DIR__ . '/api/db.php';

$currentUser = get_current_user_profile();
$pdo = get_db_connection();

// Fetch Demands and Listings
$demands = $pdo->query("SELECT * FROM demands ORDER BY created_at DESC LIMIT 6")->fetchAll();
$listings = $pdo->query("SELECT * FROM listings ORDER BY created_at DESC LIMIT 6")->fetchAll();

if (empty($demands)) {
    $demands = [
        ['crop' => 'Tomatoes', 'target_quantity_kg' => 1500, 'offered_price_per_kg' => 0.55, 'buyer_name' => 'Bulawayo Fresh Wholesalers', 'delivery_hub' => 'Belmont Wholesale Hub (Bulawayo)'],
        ['crop' => 'Green Peppers', 'target_quantity_kg' => 600, 'offered_price_per_kg' => 0.75, 'buyer_name' => 'Bulawayo Retail Chain', 'delivery_hub' => 'Belmont Wholesale Hub (Bulawayo)'],
        ['crop' => 'Table Potatoes', 'target_quantity_kg' => 2500, 'offered_price_per_kg' => 0.50, 'buyer_name' => 'Harare Fresh Produce Depot', 'delivery_hub' => 'Mbare Musika Hub (Harare)'],
        ['crop' => 'Onions', 'target_quantity_kg' => 1200, 'offered_price_per_kg' => 0.45, 'buyer_name' => 'Mutare Agro-Processing Ltd', 'delivery_hub' => 'Mutare Industrial Site']
    ];
}

if (empty($listings)) {
    $listings = [
        ['crop' => 'Table Potatoes', 'quantity_kg' => 450, 'quality' => 'Grade A (Premium)', 'farmer_name' => 'Farai Shumba', 'district' => 'Nyanga'],
        ['crop' => 'Tomatoes', 'quantity_kg' => 250, 'quality' => 'Grade A (Premium)', 'farmer_name' => 'Sipho Moyo', 'district' => 'Gwanda'],
        ['crop' => 'Onions', 'quantity_kg' => 380, 'quality' => 'Grade A (Premium)', 'farmer_name' => 'Tendai Chuma', 'district' => 'Mutare'],
        ['crop' => 'Leafy Greens', 'quantity_kg' => 120, 'quality' => 'Grade B (Agro-Processing)', 'farmer_name' => 'Auxillia Dube', 'district' => 'Bulawayo']
    ];
}

$pageTitle = 'Vunotho — Turning Produce into Protected Economic Value';
require_once __DIR__ . '/includes/header.php';
?>

<!-- 1. INSPIRING ENTERPRISE HERO SECTION -->
<section class="relative overflow-hidden rounded-3xl mb-12 p-8 md:p-14 text-white shadow-2xl border border-slate-700/60" style="background: linear-gradient(135deg, #071726 0%, #0B2032 60%, #064E3B 100%);">
  
  <!-- Subtle Ambient Glows -->
  <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>
  <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>

  <div class="relative z-10 max-w-4xl">
    <!-- Enactus Badge -->
    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-extrabold tracking-wide uppercase mb-6 shadow-sm">
      <span>🌱</span>
      <span>Enactus International Competition Blueprint • Agricultural OS</span>
    </div>

    <!-- Main Headline -->
    <h1 class="text-3xl sm:text-4xl md:text-6xl font-black tracking-tight leading-[1.15] mb-6">
      Turning Smallholder Produce into <br class="hidden sm:inline" />
      <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-amber-300 to-teal-300">
        Protected Economic Value
      </span>
    </h1>

    <!-- Subtitle -->
    <p class="text-base sm:text-lg text-slate-200 mb-8 leading-relaxed max-w-2xl font-medium opacity-90">
      Eliminating predatory middleman exploitation across Zimbabwe with transparent farmgate Net Returns, pooled 2.5T rural load logistics, and guaranteed mobile money settlements on collection.
    </p>

    <!-- Key CTA Action Buttons -->
    <div class="flex flex-wrap items-center gap-3.5 mb-10">
      <a href="/farmer.php" class="px-6 py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm shadow-glow-emerald transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
        <span>🌱</span>
        <span>Open Farmer Dashboard →</span>
      </a>
      <a href="/buyer.php" class="px-5 py-3.5 rounded-2xl bg-slate-800/90 hover:bg-slate-700 text-white font-bold text-sm border border-slate-600/80 transition-all flex items-center gap-2">
        <span>🏬</span>
        <span>Commercial Buyer Sourcing</span>
      </a>
      <a href="/transporter.php" class="px-5 py-3.5 rounded-2xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/30 font-bold text-sm transition-all flex items-center gap-2">
        <span>🚚</span>
        <span>Haulier Fleet Desk</span>
      </a>
    </div>

    <!-- 4 Live Highlight KPI Metrics in Hero -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-slate-700/60 text-xs">
      <div class="bg-white/5 p-3.5 rounded-2xl border border-white/10 backdrop-blur-sm">
        <div class="text-slate-400 font-medium">Available Harvest</div>
        <div class="text-lg font-black text-white font-mono mt-0.5">420 kg</div>
        <div class="text-emerald-400 text-[11px] font-bold mt-0.5">● Ready for Pickup</div>
      </div>
      <div class="bg-white/5 p-3.5 rounded-2xl border border-white/10 backdrop-blur-sm">
        <div class="text-slate-400 font-medium">Avg. Farmer Payout</div>
        <div class="text-lg font-black text-amber-300 font-mono mt-0.5">$186.40</div>
        <div class="text-amber-400 text-[11px] font-bold mt-0.5">● EcoCash Mobile Wallet</div>
      </div>
      <div class="bg-white/5 p-3.5 rounded-2xl border border-white/10 backdrop-blur-sm">
        <div class="text-slate-400 font-medium">Active Off-Takers</div>
        <div class="text-lg font-black text-teal-300 font-mono mt-0.5">12 Buyers</div>
        <div class="text-teal-400 text-[11px] font-bold mt-0.5">● Verified Purchase Orders</div>
      </div>
      <div class="bg-white/5 p-3.5 rounded-2xl border border-white/10 backdrop-blur-sm">
        <div class="text-slate-400 font-medium">Transport Cost</div>
        <div class="text-lg font-black text-orange-300 font-mono mt-0.5">35% Saved</div>
        <div class="text-orange-400 text-[11px] font-bold mt-0.5">● Clustered 2.5T Routes</div>
      </div>
    </div>

  </div>
</section>

<!-- 2. LIVE MARKETPLACE EXCHANGE (PRODUCE LOTS + BUYER DEMANDS) -->
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
      <a href="/farmer.php" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs shadow-md transition-all">
        + List My Produce
      </a>
      <a href="/buyer.php" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition-all">
        + Post Buyer Demand
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Column 1: Available Produce From Farmers -->
    <div class="vn-card flex flex-col justify-between">
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
          <?php foreach ($listings as $lot): ?>
            <?php 
              $crop = $lot['crop'] ?? 'Produce';
              $icon = '🍅';
              if (stripos($crop, 'potato') !== false) $icon = '🥔';
              elseif (stripos($crop, 'onion') !== false) $icon = '🧅';
              elseif (stripos($crop, 'green') !== false) $icon = '🥬';
            ?>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 hover:border-emerald-400 hover:bg-emerald-50/20 transition-all flex items-center justify-between gap-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-xl shadow-sm">
                  <?= $icon ?>
                </div>
                <div class="space-y-0.5">
                  <div class="flex items-center gap-2">
                    <strong class="text-slate-900 font-bold text-sm"><?= htmlspecialchars($crop) ?></strong>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold"><?= htmlspecialchars($lot['quality'] ?? 'Grade A') ?></span>
                  </div>
                  <p class="text-xs text-slate-500">
                    Farmer: <strong class="text-slate-700"><?= htmlspecialchars($lot['farmer_name']) ?></strong> • 📍 <span class="font-semibold text-slate-600"><?= htmlspecialchars($lot['district'] ?? 'Nyanga') ?></span>
                  </p>
                </div>
              </div>
              <div class="text-right">
                <div class="font-black text-slate-900 text-sm font-mono"><?= number_format($lot['quantity_kg']) ?> kg</div>
                <span class="text-[10px] text-emerald-700 font-bold">Ready for Pickup</span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center">
        <span class="text-xs text-slate-500">Have produce ready for sale?</span>
        <a href="/farmer.php" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 underline flex items-center gap-1">
          Open Farmer Dashboard →
        </a>
      </div>
    </div>

    <!-- Column 2: Commercial Buyer Demands -->
    <div class="vn-card flex flex-col justify-between">
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
        </div>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center">
        <span class="text-xs text-slate-500">Are you a commercial wholesale buyer?</span>
        <a href="/buyer.php" class="text-xs font-bold text-amber-700 hover:text-amber-800 underline flex items-center gap-1">
          Post Commercial Sourcing Order →
        </a>
      </div>
    </div>

  </div>
</section>

<!-- 3. INTERACTIVE NET-RETURN PRICE CALCULATOR -->
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
            <option value="0.45" selected>🍅 Tomatoes ($0.45/kg)</option>
            <option value="0.55">🥔 Table Potatoes ($0.55/kg)</option>
            <option value="0.60">🧅 Onions ($0.60/kg)</option>
            <option value="0.35">🥬 Leafy Greens ($0.35/kg)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-2">Harvest Weight (kg): <span id="sim-qty-label" class="text-emerald-400 font-mono">450 kg</span></label>
          <input type="range" id="sim-qty" min="50" max="2500" step="50" value="450" class="w-full accent-emerald-500" oninput="runSimulator()" />
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
          <div id="sim-gross" class="text-xl font-black text-white font-mono mt-1">$202.50</div>
        </div>
        <div>
          <div class="text-[11px] text-slate-400 uppercase font-bold">Pooled Freight</div>
          <div id="sim-freight" class="text-xl font-black text-orange-400 font-mono mt-1">-$15.35</div>
        </div>
        <div>
          <div class="text-[11px] text-slate-400 uppercase font-bold">Vunotho Fee (4%)</div>
          <div id="sim-fee" class="text-xl font-black text-amber-400 font-mono mt-1">-$8.10</div>
        </div>
        <div class="bg-emerald-500/20 p-2.5 rounded-xl border border-emerald-400/30">
          <div class="text-[11px] text-emerald-300 uppercase font-bold">Net Take-Home</div>
          <div id="sim-net" class="text-2xl font-black text-emerald-400 font-mono mt-0.5">$179.05</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 4. ENACTUS CIRCULAR IMPACT COUNTER -->
<section id="impact" class="mb-14">
  <div class="vn-card">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 pb-6 border-b border-slate-100">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono mb-1.5">
          🌍 Enactus Zimbabwe Circular Impact
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Verified Smallholder Community Progress</h2>
      </div>
      <a href="/farmer.php" class="px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm transition-all">
        View Impact Scorecard →
      </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
      <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
        <div class="text-3xl font-black text-slate-900 font-mono">1,420+</div>
        <div class="text-xs font-bold text-slate-500 uppercase mt-1">Smallholder Farmers Linked</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
        <div class="text-3xl font-black text-emerald-600 font-mono">$148.5K</div>
        <div class="text-xs font-bold text-slate-500 uppercase mt-1">Net Earnings Generated</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
        <div class="text-3xl font-black text-amber-600 font-mono">890+</div>
        <div class="text-xs font-bold text-slate-500 uppercase mt-1">Pooled Freight Trips</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
        <div class="text-3xl font-black text-teal-600 font-mono">42.8 T</div>
        <div class="text-xs font-bold text-slate-500 uppercase mt-1">Produce Diverted from Waste</div>
      </div>
    </div>
  </div>
</section>

<!-- Script for Pricing Calculator -->
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
