<?php
/**
 * VUNOTHO COMMERCIAL PROCUREMENT DESK (Server-Protected PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';
require_once __DIR__ . '/api/db.php';

// Strict Server-Side Role Guard
$user = require_role('buyer');
$pdo = get_db_connection();

$message = '';
$error = '';

// Handle Direct Server-Side Demand Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_demand') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security session expired. Please refresh and try again.';
    } else {
        $crop = sanitize_string($_POST['crop'] ?? 'Tomatoes');
        $targetKg = sanitize_numeric($_POST['target_quantity_kg'] ?? 0);
        $offeredPrice = sanitize_numeric($_POST['offered_price_per_kg'] ?? 0);
        $quality = sanitize_string($_POST['quality_required'] ?? 'Grade A (Supermarket Spec)');
        $hub = sanitize_string($_POST['delivery_hub'] ?? 'Harare Central Market');

        if ($targetKg <= 0 || $offeredPrice <= 0) {
            $error = 'Please enter valid target volume and price.';
        } else {
            $demId = 'DEM-' . time() . '-' . strtoupper(substr(uniqid(), -4));
            $stmt = $pdo->prepare("
                INSERT INTO demands (id, buyer_id, buyer_name, crop, target_quantity_kg, offered_price_per_kg, quality_required, delivery_hub, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $demId,
                $user['id'],
                $user['name'],
                $crop,
                $targetKg,
                $offeredPrice,
                $quality,
                $hub,
                'Active',
                date('c')
            ]);
            $message = "Procurement demand for {$targetKg}kg {$crop} published to marketplace!";
        }
    }
}

// Fetch Active Farmgate Supply Lots
$listingsStmt = $pdo->query("SELECT * FROM listings ORDER BY created_at DESC LIMIT 12");
$listings = $listingsStmt->fetchAll();

// Fetch Buyer's Demands
$myDemandsStmt = $pdo->prepare("SELECT * FROM demands WHERE buyer_id = ? OR buyer_name = ? ORDER BY created_at DESC");
$myDemandsStmt->execute([$user['id'], $user['name']]);
$myDemands = $myDemandsStmt->fetchAll();

// Fetch Settled Orders for this Buyer
$ordersStmt = $pdo->prepare("SELECT * FROM transactions WHERE buyer_id = ? OR buyer_name = ? ORDER BY created_at DESC");
$ordersStmt->execute([$user['id'], $user['name']]);
$orders = $ordersStmt->fetchAll();

$pageTitle = 'Commercial Procurement Desk — Vunotho';
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
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200">
          🏢 Commercial Procurement Desk • <?= htmlspecialchars($user['district'] ?? 'Harare CBD') ?>, <?= htmlspecialchars($user['province'] ?? 'Harare') ?>
        </span>
        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono">KYC: Verified Off-taker</span>
      </div>
      <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
        Commercial Sourcing Hub — <span class="text-amber-600">Verified Farmgate Supply</span>
      </h1>
      <p class="text-xs md:text-sm text-slate-600 mt-1 max-w-2xl">
        Source verified smallholder produce directly at transparent wholesale rates, with aggregated 2.5T truck fulfillment and guaranteed quality specs.
      </p>
    </div>

    <div class="flex items-center gap-3 flex-wrap">
      <button class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-warm-md transition-all flex items-center gap-2" onclick="document.getElementById('new-demand-modal').classList.add('active')">
        + Post Sourcing Demand
      </button>
    </div>
  </div>
</div>

<!-- 2. QUICK PROCUREMENT METRICS -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-amber-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">My Active Demands</div>
    <div class="text-2xl font-black text-slate-900 font-mono"><?= count($myDemands) ?> Orders</div>
    <div class="text-xs text-amber-700 font-medium mt-1">Open commercial contracts</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-emerald-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Available Supply</div>
    <div class="text-2xl font-black text-slate-900 font-mono"><?= count($listings) ?> Lots</div>
    <div class="text-xs text-emerald-700 font-medium mt-1">Direct from smallholders</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-teal-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Purchases Settled</div>
    <div class="text-2xl font-black text-slate-900 font-mono"><?= count($orders) ?> Orders</div>
    <div class="text-xs text-teal-700 font-medium mt-1">Dispatched via pooled freight</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-orange-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Fulfillment Reliability</div>
    <div class="text-2xl font-black text-slate-900 font-mono">98.4%</div>
    <div class="text-xs text-orange-700 font-medium mt-1">Escrow verified pickup</div>
  </div>
</div>

<!-- 3. MAIN PORTAL CONTENT (2-Col Grid) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
  <!-- Left: Available Farmgate Produce Supply -->
  <div class="lg:col-span-7 bg-white/90 p-6 rounded-3xl border border-slate-200 shadow-warm-md space-y-4">
    <div class="flex justify-between items-center pb-4 border-b border-slate-100">
      <h3 class="font-extrabold text-base text-slate-900">Available Smallholder Farmgate Lots</h3>
      <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono"><?= count($listings) ?> Lots</span>
    </div>

    <div class="space-y-3">
      <?php foreach ($listings as $lot): ?>
        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-amber-400 transition-all">
          <div class="flex justify-between items-start mb-2">
            <div>
              <strong class="text-slate-900 text-base font-black"><?= htmlspecialchars($lot['crop']) ?></strong>
              <p class="text-xs text-slate-500 mt-0.5">Origin: <strong><?= htmlspecialchars($lot['district'] ?? 'Nyanga') ?></strong> • Farmer: <strong><?= htmlspecialchars($lot['farmer_name']) ?></strong></p>
            </div>
            <span class="px-3 py-1 rounded-xl bg-emerald-100 text-emerald-800 font-black text-xs font-mono"><?= number_format($lot['quantity_kg']) ?> kg</span>
          </div>

          <div class="flex justify-between items-center pt-3 border-t border-dashed border-slate-200 text-xs">
            <span class="text-slate-500">Grade: <strong><?= htmlspecialchars($lot['quality'] ?? 'Grade A') ?></strong></span>
            <button class="px-3.5 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs" onclick="orderHarvestLot('<?= htmlspecialchars($lot['id']) ?>', '<?= htmlspecialchars($lot['crop']) ?>', <?= $lot['quantity_kg'] ?>)">
              Order & Lock Lot →
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Right: My Posted Demands -->
  <div class="lg:col-span-5 bg-white/90 p-6 rounded-3xl border border-slate-200 shadow-warm-md space-y-4">
    <div class="flex justify-between items-center pb-4 border-b border-slate-100">
      <h3 class="font-extrabold text-base text-slate-900">My Sourcing Demands</h3>
      <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold font-mono"><?= count($myDemands) ?> Active</span>
    </div>

    <div class="space-y-3">
      <?php if (empty($myDemands)): ?>
        <div class="text-center py-8 text-slate-400 text-xs font-semibold">
          No demands published. Click "+ Post Sourcing Demand" to broadcast your procurement needs.
        </div>
      <?php else: ?>
        <?php foreach ($myDemands as $dem): ?>
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <div class="flex justify-between items-start mb-1">
              <strong class="text-slate-900 text-sm font-black"><?= htmlspecialchars($dem['crop']) ?> Sourcing</strong>
              <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-black font-mono">$<?= number_format($dem['offered_price_per_kg'], 2) ?>/kg</span>
            </div>
            <div class="text-xs text-slate-500">
              Target: <strong><?= number_format($dem['target_quantity_kg']) ?> kg</strong> • Destination: <strong><?= htmlspecialchars($dem['delivery_hub']) ?></strong>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- 4. MODAL: POST SOURCING DEMAND -->
<div id="new-demand-modal" class="vunotho-modal-backdrop">
  <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-warm-xl max-w-md w-full relative">
    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
      <h3 class="text-lg font-black text-slate-900">Post Commercial Purchase Demand</h3>
      <button class="text-slate-400 hover:text-slate-700 font-bold" onclick="document.getElementById('new-demand-modal').classList.remove('active')">✕</button>
    </div>

    <form method="POST" action="/buyer.php" class="space-y-4">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
      <input type="hidden" name="action" value="create_demand" />

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Required Commodity</label>
        <select name="crop" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold" required>
          <option value="Tomatoes" selected>Tomatoes (Round / Roma Sandak)</option>
          <option value="Table Potatoes">Table Potatoes (15kg Mesh Pocket)</option>
          <option value="Onions">Onions (10kg Pocket)</option>
          <option value="Leafy Greens">Leafy Greens (Tsunga / Covo / Rape)</option>
          <option value="Butternut Squash">Butternut Squash (10kg Pocket)</option>
          <option value="Green Peppers">Green Peppers (20L Tin)</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Target Volume (Kilograms)</label>
        <input type="number" name="target_quantity_kg" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" placeholder="e.g. 1000" min="50" step="50" required />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Offered Price ($/kg)</label>
        <input type="number" name="offered_price_per_kg" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" placeholder="e.g. 0.55" min="0.10" max="3.00" step="0.01" required />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Delivery Wholesale Hub</label>
        <input type="text" name="delivery_hub" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm" value="Harare Central Produce Market" required />
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
        <button type="button" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs" onclick="document.getElementById('new-demand-modal').classList.remove('active')">Cancel</button>
        <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs">Publish Demand Order</button>
      </div>
    </form>
  </div>
</div>

<script>
  async function orderHarvestLot(listingId, crop, quantityKg) {
    showToast(`Locking ${quantityKg}kg ${crop} for commercial delivery...`, 'info');

    try {
      const res = await fetch('/api/transactions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          crop: crop,
          quantity_kg: quantityKg,
          gross_total: quantityKg * 0.50,
          transport_deduction: quantityKg * 35 * 0.0015 * 0.65,
          platform_fee: (quantityKg * 0.50) * 0.04,
          net_payout: (quantityKg * 0.50 * 0.96) - (quantityKg * 35 * 0.0015 * 0.65),
          farmer_name: 'Smallholder Farmer',
          buyer_name: '<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>',
          payment_method: 'EcoCash Mobile Wallet'
        })
      });
      const data = await res.json();
      showToast('Order locked! Dispatched to pooled transporter route.', 'success');
      setTimeout(() => location.reload(), 1000);
    } catch (e) {
      showToast('Order error: ' + e.message, 'error');
    }
  }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
