<?php
/**
 * VUNOTHO RURAL FREIGHT LOGISTICS DESK (Server-Protected PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';
require_once __DIR__ . '/api/db.php';

// Strict Server-Side Role Guard
$user = require_role('transporter');
$pdo = get_db_connection();

// Fetch All Listings for Clustered Manifest Generation
$listingsStmt = $pdo->query("SELECT * FROM listings ORDER BY created_at DESC");
$listings = $listingsStmt->fetchAll();

// Cluster listings into 2.5T manifests in PHP
$clusters = [];
foreach ($listings as $item) {
    $crop = $item['crop'] ?? 'Produce';
    $district = $item['district'] ?? 'Nyanga';
    $key = "{$crop}_{$district}";
    if (!isset($clusters[$key])) {
        $clusters[$key] = [
            'id' => 'MAN-' . strtoupper(substr(md5($key . date('Ymd')), 0, 6)),
            'crop' => $crop,
            'district' => $district,
            'origin' => $district,
            'destination' => ($district === 'Gwanda') ? 'Belmont Wholesale Hub (Bulawayo)' : (($district === 'Mutare') ? 'Mutare Regional Depot' : 'Mbare Musika Wholesale Hub (Harare)'),
            'totalWeightKg' => 0,
            'stops' => []
        ];
    }
    $clusters[$key]['totalWeightKg'] += floatval($item['quantity_kg'] ?? 0);
    $clusters[$key]['stops'][] = $item;
}

// Fetch Settled Freight Remittances
$txStmt = $pdo->query("SELECT * FROM transactions WHERE transport_deduction > 0 ORDER BY created_at DESC");
$settledFreight = $txStmt->fetchAll();
$totalFreightEarned = array_reduce($settledFreight, function($sum, $t) { return $sum + floatval($t['transport_deduction'] ?? 0); }, 0);

$pageTitle = 'Rural Freight Logistics Desk — Vunotho';
require_once __DIR__ . '/includes/header.php';
?>

<!-- 1. IN-PORTAL HEADER CARD -->
<div class="bg-gradient-to-r from-white via-[#FAF8F5] to-[#F1F5F9] p-6 md:p-8 rounded-3xl border border-slate-200 shadow-warm-md mb-8 relative overflow-hidden">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
    <div>
      <div class="flex items-center gap-2 flex-wrap mb-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-orange-800 text-xs font-bold border border-orange-200">
          🚚 Rural Freight Logistics Desk • <?= htmlspecialchars($user['district'] ?? 'Nyanga Base') ?>
        </span>
        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono">Fleet: 2.5T Light Truck</span>
      </div>
      <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
        Freight Aggregation — <span class="text-orange-600">2.5T Pooled Route Manifests</span>
      </h1>
      <p class="text-xs md:text-sm text-slate-600 mt-1 max-w-2xl">
        Eliminate empty backhauls by aggregating multi-farmer smallholder harvests into guaranteed 2.5-tonne rural collection routes.
      </p>
    </div>
  </div>
</div>

<!-- 2. QUICK FLEET METRICS -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-orange-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Available Manifests</div>
    <div class="text-2xl font-black text-slate-900 font-mono"><?= count($clusters) ?> Routes</div>
    <div class="text-xs text-orange-700 font-medium mt-1">Clustered rural collection</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-emerald-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Fleet Capacity</div>
    <div class="text-2xl font-black text-slate-900 font-mono">2,500 kg</div>
    <div class="text-xs text-emerald-700 font-medium mt-1">Standard rural truck unit</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-teal-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Remittances</div>
    <div class="text-2xl font-black text-slate-900 font-mono">$<?= number_format($totalFreightEarned, 2) ?></div>
    <div class="text-xs text-teal-700 font-medium mt-1">Direct freight payout</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-amber-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Diesel Cost Recovery</div>
    <div class="text-2xl font-black text-slate-900 font-mono">Guaranteed</div>
    <div class="text-xs text-amber-700 font-medium mt-1">Escrowed per order</div>
  </div>
</div>

<!-- 3. AGGREGATED ROUTE MANIFESTS -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
  <?php foreach ($clusters as $m): ?>
    <?php
      $utilPct = min(100, round(($m['totalWeightKg'] / 2500) * 100));
      $estKm = 20 + count($m['stops']) * 6;
      $payout = ($m['totalWeightKg'] * 0.05) + ($estKm * 0.45);
    ?>
    <div class="bg-white/90 p-6 rounded-3xl border border-slate-200 shadow-warm-md space-y-4 hover:border-orange-300 transition-all">
      <div class="flex justify-between items-start">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-orange-700 font-mono"><?= $m['id'] ?></span>
          <h4 class="text-base font-black text-slate-900 mt-0.5"><?= htmlspecialchars($m['crop']) ?> Clustered Load (<?= htmlspecialchars($m['origin']) ?> ➔ <?= htmlspecialchars($m['destination']) ?>)</h4>
        </div>
        <span class="px-3 py-1 rounded-xl bg-orange-100 text-orange-800 font-black text-xs font-mono">
          $<?= number_format($payout, 2) ?> Est. Payout
        </span>
      </div>

      <!-- Capacity Utilization Bar -->
      <div>
        <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
          <span>2.5T Capacity: <?= number_format($m['totalWeightKg']) ?> / 2,500 kg</span>
          <span class="font-mono text-orange-700"><?= $utilPct ?>% Full</span>
        </div>
        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
          <div class="h-full bg-gradient-to-r from-orange-500 to-amber-500 rounded-full" style="width: <?= $utilPct ?>%;"></div>
        </div>
      </div>

      <!-- Pickup Waypoints List -->
      <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-2">
        <div class="font-bold text-slate-700 flex items-center gap-1.5">
          📍 Collection Waypoints (<?= count($m['stops']) ?> smallholder stops):
        </div>
        <ul class="space-y-1 text-slate-600 pl-4 list-disc">
          <?php foreach ($m['stops'] as $stop): ?>
            <li><strong><?= htmlspecialchars($stop['farmer_name'] ?? 'Smallholder') ?></strong> (<?= htmlspecialchars($stop['district'] ?? 'Nyanga') ?>): <?= number_format($stop['quantity_kg']) ?> kg <?= htmlspecialchars($stop['crop']) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="flex justify-between items-center pt-3 border-t border-dashed border-slate-200 text-xs">
        <span class="text-slate-500">Est. Distance: <strong><?= $estKm ?> km</strong></span>
        <button class="px-4 py-2 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold transition-all shadow-glow-orange" onclick="showToast('Manifest <?= $m['id'] ?> dispatched! Waypoint coordinates sent to driver.', 'success')">
          Accept & Dispatch Route →
        </button>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
