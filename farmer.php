<?php
/**
 * VUNOTHO SMALLHOLDER FARMER OPERATIONS DESK (Server-Protected PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';
require_once __DIR__ . '/api/db.php';

// Strict Server-Side Role Guard
$user = require_role('farmer');
$pdo = get_db_connection();

$message = '';
$error = '';

// Handle Direct Server-Side Produce Lot Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_listing') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security session expired. Please refresh and try again.';
    } else {
        $crop = sanitize_string($_POST['crop'] ?? 'Tomatoes');
        $quantityKg = sanitize_numeric($_POST['quantity_kg'] ?? 0);
        $quality = sanitize_string($_POST['quality'] ?? 'Grade A (Supermarket Spec)');
        $lat = sanitize_numeric($_POST['lat'] ?? -18.2167);
        $lng = sanitize_numeric($_POST['lng'] ?? 32.7500);

        if ($quantityKg <= 0) {
            $error = 'Please enter a valid harvest volume in kilograms.';
        } else {
            $listId = 'LIST-' . time() . '-' . strtoupper(substr(uniqid(), -4));
            $stmt = $pdo->prepare("
                INSERT INTO listings (id, farmer_id, farmer_name, crop, quantity_kg, quality, lat, lng, district, province, sync_status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $listId,
                $user['id'],
                $user['name'],
                $crop,
                $quantityKg,
                $quality,
                $lat,
                $lng,
                $user['district'] ?? 'Nyanga',
                $user['province'] ?? 'Manicaland',
                'Synced',
                date('c')
            ]);
            $message = "Harvest lot of {$quantityKg}kg {$crop} logged successfully to central registry!";
        }
    }
}

// Fetch Farmer's Listings
$myListingsStmt = $pdo->prepare("SELECT * FROM listings WHERE farmer_id = ? OR farmer_name = ? ORDER BY created_at DESC");
$myListingsStmt->execute([$user['id'], $user['name']]);
$myListings = $myListingsStmt->fetchAll();

// Fetch Live Commercial Buyer Demands
$demandsStmt = $pdo->query("SELECT * FROM demands ORDER BY created_at DESC LIMIT 8");
$demands = $demandsStmt->fetchAll();

// Fetch Settled Transactions for this Farmer
$txStmt = $pdo->prepare("SELECT * FROM transactions WHERE farmer_id = ? OR farmer_name = ? ORDER BY created_at DESC");
$txStmt->execute([$user['id'], $user['name']]);
$transactions = $txStmt->fetchAll();

$totalEarnings = array_reduce($transactions, function($sum, $t) { return $sum + floatval($t['net_payout'] ?? 0); }, 0);
$totalVolume = array_reduce($myListings, function($sum, $l) { return $sum + floatval($l['quantity_kg'] ?? 0); }, 0);

$pageTitle = 'Smallholder Produce Hub — Vunotho';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Status Alerts -->
<?php if (!empty($message)): ?>
  <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold mb-6 flex items-center justify-between shadow-warm-sm">
    <span>✓ <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></span>
    <button onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">✕</button>
  </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold mb-6 flex items-center justify-between shadow-warm-sm">
    <span>⚠️ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
    <button onclick="this.parentElement.remove()" class="text-rose-700 font-bold">✕</button>
  </div>
<?php endif; ?>

<!-- 1. IN-PORTAL HEADER CARD -->
<div class="bg-gradient-to-r from-white via-[#FAF8F5] to-[#F1F5F9] p-6 md:p-8 rounded-3xl border border-slate-200 shadow-warm-md mb-8 relative overflow-hidden">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
    <div>
      <div class="flex items-center gap-2 flex-wrap mb-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200">
          🌱 Smallholder Operations Desk • <?= htmlspecialchars($user['district'] ?? 'Nyanga') ?>, <?= htmlspecialchars($user['province'] ?? 'Manicaland') ?>
        </span>
        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold font-mono">KYC: Verified Smallholder</span>
      </div>
      <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
        Smallholder Produce Hub — <span class="text-emerald-600">Guaranteed Farmgate Net Value</span>
      </h1>
      <p class="text-xs md:text-sm text-slate-600 mt-1 max-w-2xl">
        Transparent take-home returns (<strong class="text-slate-900">Gross Price − Pooled Freight − 4% Fee</strong>), 2.5T rural truck aggregation, and direct EcoCash settlements on collection.
      </p>
    </div>

    <div class="flex items-center gap-3 flex-wrap">
      <button class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all flex items-center gap-2" onclick="document.getElementById('new-harvest-modal').classList.add('active')">
        + Log New Harvest
      </button>
    </div>
  </div>

  <!-- Animated Live Market Marquee Ticker -->
  <div class="mt-6 pt-4 border-t border-slate-200/80 ticker-wrap">
    <div class="ticker-track text-xs font-semibold text-slate-700 space-x-8">
      <?php foreach ($demands as $d): ?>
        <span class="inline-flex items-center gap-2">
          <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-200">OPEN DEMAND</span>
          <strong><?= htmlspecialchars($d['crop']) ?></strong>: <?= number_format($d['target_quantity_kg']) ?> kg @ <span class="text-emerald-700 font-bold font-mono">$<?= number_format($d['offered_price_per_kg'], 2) ?>/kg</span> (<?= htmlspecialchars($d['delivery_hub'] ?? 'Harare') ?>)
        </span>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- 2. QUICK METRICS OVERVIEW -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-emerald-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">My Active Lots</div>
    <div class="text-2xl font-black text-slate-900 font-mono"><?= count($myListings) ?> Lots</div>
    <div class="text-xs text-emerald-700 font-medium mt-1">Volume: <?= number_format($totalVolume) ?> kg</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-amber-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Net Earnings</div>
    <div class="text-2xl font-black text-slate-900 font-mono">$<?= number_format($totalEarnings, 2) ?></div>
    <div class="text-xs text-amber-700 font-medium mt-1">Disbursed via EcoCash</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-teal-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Commercial Demands</div>
    <div class="text-2xl font-black text-slate-900 font-mono"><?= count($demands) ?> Orders</div>
    <div class="text-xs text-teal-700 font-medium mt-1">Matching verified off-takers</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-orange-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Logistics Pooling</div>
    <div class="text-2xl font-black text-slate-900 font-mono">35% Savings</div>
    <div class="text-xs text-orange-700 font-medium mt-1">Via clustered 2.5T routes</div>
  </div>
</div>

<!-- 3. MAIN PORTAL CONTENT (2-Col Grid) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
  <!-- Left: Live Demand Exchange -->
  <div class="lg:col-span-7 bg-white/90 p-6 rounded-3xl border border-slate-200 shadow-warm-md space-y-4">
    <div class="flex justify-between items-center pb-4 border-b border-slate-100">
      <h3 class="font-extrabold text-base text-slate-900">Verified Commercial Buyer Demands</h3>
      <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold font-mono"><?= count($demands) ?> Demands</span>
    </div>

    <div class="space-y-3">
      <?php foreach ($demands as $item): ?>
        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-emerald-400 transition-all">
          <div class="flex justify-between items-start mb-3">
            <div>
              <h4 class="font-extrabold text-slate-900 text-base"><?= htmlspecialchars($item['crop']) ?> Purchase Order</h4>
              <p class="text-xs text-slate-500 mt-0.5">Buyer: <strong><?= htmlspecialchars($item['buyer_name']) ?></strong> • Dest: <strong><?= htmlspecialchars($item['delivery_hub']) ?></strong></p>
            </div>
            <span class="px-3 py-1 rounded-xl bg-amber-100 text-amber-800 font-black text-sm font-mono">$<?= number_format($item['offered_price_per_kg'], 2) ?>/kg</span>
          </div>

          <div class="flex justify-between items-center pt-3 border-t border-dashed border-slate-200 text-xs">
            <span class="text-slate-600 font-bold">Target: <?= number_format($item['target_quantity_kg']) ?> kg</span>
            <button class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition-all shadow-sm" onclick="matchBuyerDemand('<?= htmlspecialchars($item['id']) ?>', '<?= htmlspecialchars($item['crop']) ?>', <?= $item['offered_price_per_kg'] ?>)">
              Match & Accept Order →
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Right: My Registered Lots -->
  <div class="lg:col-span-5 bg-white/90 p-6 rounded-3xl border border-slate-200 shadow-warm-md space-y-4">
    <div class="flex justify-between items-center pb-4 border-b border-slate-100">
      <h3 class="font-extrabold text-base text-slate-900">My Registered Produce Lots</h3>
      <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono"><?= count($myListings) ?> Lots</span>
    </div>

    <div class="space-y-3">
      <?php if (empty($myListings)): ?>
        <div class="text-center py-8 text-slate-400 text-xs font-semibold">
          No harvest lots registered yet. Click "+ Log New Harvest" above to register your produce.
        </div>
      <?php else: ?>
        <?php foreach ($myListings as $lot): ?>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <div class="flex justify-between items-start mb-2">
              <strong class="text-slate-900 text-sm font-black"><?= number_format($lot['quantity_kg']) ?> kg • <?= htmlspecialchars($lot['crop']) ?></strong>
              <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Ready</span>
            </div>
            <div class="text-xs text-slate-500">
              Grade: <strong><?= htmlspecialchars($lot['quality'] ?? 'Grade A') ?></strong> • District: <strong><?= htmlspecialchars($lot['district'] ?? 'Nyanga') ?></strong>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- 4. MODAL: LOG NEW HARVEST -->
<div id="new-harvest-modal" class="vunotho-modal-backdrop">
  <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-warm-xl max-w-md w-full relative">
    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
      <h3 class="text-lg font-black text-slate-900">Log New Smallholder Harvest</h3>
      <button class="text-slate-400 hover:text-slate-700 font-bold" onclick="document.getElementById('new-harvest-modal').classList.remove('active')">✕</button>
    </div>

    <form method="POST" action="/farmer.php" class="space-y-4">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
      <input type="hidden" name="action" value="create_listing" />
      <input type="hidden" name="lat" value="-18.2167" />
      <input type="hidden" name="lng" value="32.7500" />

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Agricultural Commodity</label>
        <select name="crop" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold" required>
          <option value="Tomatoes" selected>Tomatoes (Round / Roma Sandak)</option>
          <option value="Table Potatoes">Table Potatoes (15kg Mesh Pocket)</option>
          <option value="Onions">Onions (10kg Pocket)</option>
          <option value="Leafy Greens">Leafy Greens (Tsunga / Covo / Rape)</option>
          <option value="Butternut Squash">Butternut Squash (10kg Pocket)</option>
          <option value="Cabbages">Cabbages (Bulk Heads)</option>
          <option value="Green Peppers">Green Peppers (20L Tin)</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Harvest Volume (Kilograms)</label>
        <input type="number" name="quantity_kg" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" placeholder="e.g. 350" min="10" step="5" required />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Quality Grading Spec</label>
        <select name="quality" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold" required>
          <option value="Grade A (Supermarket Spec)" selected>Tier 1: Grade A (Supermarket & Wholesale Spec)</option>
          <option value="Grade B (Agro-Processing)">Tier 2: Grade B (Agro-Processing - Crisps, Flour, Starch)</option>
          <option value="Grade C (Animal Feed / Livestock)">Tier 3: Grade C (Livestock Feed / Pig & Cattle Rations)</option>
          <option value="Bio-Compost Biomass">Tier 4: Bio-Compost Organic Biomass</option>
        </select>
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
        <button type="button" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs" onclick="document.getElementById('new-harvest-modal').classList.remove('active')">Cancel</button>
        <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald">Register Harvest Lot</button>
      </div>
    </form>
  </div>
</div>

<script>
  async function matchBuyerDemand(demandId, crop, offeredPrice) {
    showToast(`Matching your ${crop} harvest to commercial demand at $${Number(offeredPrice).toFixed(2)}/kg...`, 'info');
    
    try {
      const res = await fetch('/api/transactions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          crop: crop,
          quantity_kg: 250,
          gross_total: 250 * offeredPrice,
          transport_deduction: 250 * 35 * 0.0015 * 0.65,
          platform_fee: (250 * offeredPrice) * 0.04,
          net_payout: (250 * offeredPrice * 0.96) - (250 * 35 * 0.0015 * 0.65),
          farmer_name: '<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>',
          farmer_id: '<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>',
          buyer_name: 'Bulawayo Fresh Wholesalers',
          payment_method: 'EcoCash Mobile Wallet'
        })
      });
      const data = await res.json();
      showToast('Order matched & settled via EcoCash!', 'success');
      setTimeout(() => location.reload(), 1000);
    } catch (e) {
      showToast('Match error: ' + e.message, 'error');
    }
  }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
