<?php
/**
 * VUNOTHO EXECUTIVE ADMIN COMMAND CENTER (Server-Protected PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';
require_once __DIR__ . '/api/db.php';

// Strict Server-Side Role Guard
$user = require_role('admin');
$pdo = get_db_connection();

$message = '';

// Handle Direct Server-Side KYC Status Toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_kyc') {
    if (validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $targetUserId = sanitize_string($_POST['user_id'] ?? '');
        $newStatus = sanitize_string($_POST['new_status'] ?? 'Approved');
        $stmt = $pdo->prepare("UPDATE users SET kyc_status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $targetUserId]);
        $message = "User KYC status updated to {$newStatus}!";
    }
}

// Handle Direct Server-Side Economic Parameters Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_configs') {
    if (validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $fee = sanitize_string($_POST['platform_fee_pct'] ?? '4.0');
        $trans = sanitize_string($_POST['transport_per_km'] ?? '0.0015');
        $target = sanitize_string($_POST['enactus_target_usd'] ?? '50000');

        $pdo->prepare("UPDATE system_configs SET config_value = ? WHERE config_key = 'platform_fee_pct'")->execute([$fee]);
        $pdo->prepare("UPDATE system_configs SET config_value = ? WHERE config_key = 'transport_per_km'")->execute([$trans]);
        $pdo->prepare("UPDATE system_configs SET config_value = ? WHERE config_key = 'enactus_target_usd'")->execute([$target]);
        $message = "Platform economic parameters saved successfully!";
    }
}

// Fetch All Registered Users
$usersStmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $usersStmt->fetchAll();

// Fetch System Configs
$cfgRows = $pdo->query("SELECT config_key, config_value FROM system_configs")->fetchAll();
$configs = [];
foreach ($cfgRows as $r) {
    $configs[$r['config_key']] = $r['config_value'];
}

// Fetch 4-Tier Circular Value Recovery Logs
$vrStmt = $pdo->query("SELECT * FROM value_recovery ORDER BY timestamp DESC");
$vrLogs = $vrStmt->fetchAll();

// Compute Enactus Stats
$totalListed = $pdo->query("SELECT COALESCE(SUM(quantity_kg), 0) FROM listings")->fetchColumn();
$txData = $pdo->query("SELECT COALESCE(SUM(quantity_kg), 0) as sold_kg, COALESCE(SUM(net_payout), 0) as net_usd, COALESCE(SUM(platform_fee), 0) as surplus_usd FROM transactions")->fetch();
$totalDiverted = $pdo->query("SELECT COALESCE(SUM(kg_diverted), 0) as div_kg, COALESCE(SUM(recovered_value_usd), 0) as rec_usd FROM value_recovery")->fetch();

$pageTitle = 'Executive Command Center — Vunotho';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Status Alert -->
<?php if (!empty($message)): ?>
  <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold mb-6 flex items-center justify-between shadow-warm-sm">
    <span>✓ <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></span>
    <button onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">✕</button>
  </div>
<?php endif; ?>

<!-- 1. EXECUTIVE HEADER CARD -->
<div class="bg-gradient-to-r from-white via-[#FAF8F5] to-[#F1F5F9] p-6 md:p-8 rounded-3xl border border-slate-200 shadow-warm-md mb-8 relative overflow-hidden">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
    <div>
      <div class="flex items-center gap-2 flex-wrap mb-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-bold font-mono">
          🛡️ Executive Command Center • Super Admin
        </span>
        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono">Enactus Zimbabwe Live</span>
      </div>
      <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
        National Governance & Impact Dashboard
      </h1>
      <p class="text-xs md:text-sm text-slate-600 mt-1 max-w-2xl">
        Real-time oversight over smallholder financial lift, 4-tier circular post-harvest recovery, KYC approvals, and economic platform parameters.
      </p>
    </div>
  </div>
</div>

<!-- 2. ENACTUS IMPACT SCORECARD -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-emerald-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Smallholder Net Income</div>
    <div class="text-2xl font-black text-slate-900 font-mono">$<?= number_format($txData['net_usd'] ?? 148.16, 2) ?></div>
    <div class="text-xs text-emerald-700 font-bold mt-1">▲ +32.5% vs Broker Benchmark</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-teal-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Waste Diverted (SDG 12.3)</div>
    <div class="text-2xl font-black text-slate-900 font-mono"><?= number_format($totalDiverted['div_kg'] ?? 120) ?> kg</div>
    <div class="text-xs text-teal-700 font-bold mt-1">Value: $<?= number_format($totalDiverted['rec_usd'] ?? 66, 2) ?></div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-orange-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Freight Pool Efficiency</div>
    <div class="text-2xl font-black text-slate-900 font-mono">35%</div>
    <div class="text-xs text-orange-700 font-bold mt-1">Logistics cost reduction</div>
  </div>
  <div class="bg-white/90 p-5 rounded-2xl border border-slate-200 shadow-warm-sm border-l-4 border-l-amber-500">
    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Youth Jobs & Surplus</div>
    <div class="text-2xl font-black text-slate-900 font-mono">1 Agent</div>
    <div class="text-xs text-amber-700 font-bold mt-1">Platform Surplus: $<?= number_format($txData['surplus_usd'] ?? 6.60, 2) ?></div>
  </div>
</div>

<!-- 3. USER KYC MANAGEMENT TABLE -->
<div class="bg-white/90 p-6 rounded-3xl border border-slate-200 shadow-warm-md mb-8">
  <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
    <div>
      <h3 class="font-extrabold text-base text-slate-900">User Identity & KYC Approvals</h3>
      <p class="text-xs text-slate-500 mt-0.5">Manage smallholders, commercial off-takers, and hauliers in the central PostgreSQL registry.</p>
    </div>
    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-800 text-xs font-bold font-mono"><?= count($users) ?> Users</span>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider">
        <tr>
          <th class="p-3.5 rounded-l-xl">Name / Entity</th>
          <th class="p-3.5">Contact</th>
          <th class="p-3.5">Role</th>
          <th class="p-3.5">District</th>
          <th class="p-3.5">KYC Status</th>
          <th class="p-3.5 rounded-r-xl text-right">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($users as $u): ?>
          <tr class="hover:bg-slate-50 transition-all">
            <td class="p-3.5 font-bold text-slate-900"><?= htmlspecialchars($u['name']) ?></td>
            <td class="p-3.5 font-mono text-slate-600"><?= htmlspecialchars($u['email_or_phone']) ?></td>
            <td class="p-3.5"><span class="px-2 py-0.5 rounded uppercase font-bold text-[10px] <?= $u['role'] === 'farmer' ? 'bg-emerald-100 text-emerald-800' : ($u['role'] === 'buyer' ? 'bg-amber-100 text-amber-800' : 'bg-orange-100 text-orange-800') ?>"><?= htmlspecialchars($u['role']) ?></span></td>
            <td class="p-3.5 text-slate-600"><?= htmlspecialchars($u['district'] ?? 'Nyanga') ?></td>
            <td class="p-3.5">
              <span class="px-2 py-0.5 rounded-full text-xs font-bold <?= ($u['kyc_status'] === 'Approved' || $u['kyc_status'] === 'Super Admin') ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                <?= htmlspecialchars($u['kyc_status'] ?? 'Pending KYC') ?>
              </span>
            </td>
            <td class="p-3.5 text-right">
              <form method="POST" action="/admin.php" class="inline">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
                <input type="hidden" name="action" value="update_kyc" />
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($u['id'], ENT_QUOTES, 'UTF-8') ?>" />
                <input type="hidden" name="new_status" value="<?= ($u['kyc_status'] === 'Approved') ? 'Pending KYC' : 'Approved' ?>" />
                <button type="submit" class="px-2.5 py-1 rounded bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs">
                  <?= ($u['kyc_status'] === 'Approved') ? 'Revoke' : 'Approve KYC' ?>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 4. 4-TIER VALUE RECOVERY & PARAMETERS (2-Col Grid) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
  <!-- Left: 4-Tier Circular Value Recovery Ledger -->
  <div class="lg:col-span-7 bg-white/90 p-6 rounded-3xl border border-slate-200 shadow-warm-md space-y-4">
    <div class="flex justify-between items-center pb-4 border-b border-slate-100">
      <h3 class="font-extrabold text-base text-slate-900">4-Tier Circular Value Recovery Ledger</h3>
      <span class="px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800 text-xs font-bold font-mono"><?= count($vrLogs) ?> Streams</span>
    </div>

    <div class="space-y-3">
      <?php foreach ($vrLogs as $log): ?>
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex justify-between items-center">
          <div>
            <strong class="text-slate-900 text-sm font-black"><?= htmlspecialchars($log['crop']) ?> (<?= number_format($log['kg_diverted']) ?> kg)</strong>
            <div class="text-xs text-slate-500 mt-0.5">Stream: <strong class="text-teal-700"><?= htmlspecialchars($log['pathway']) ?></strong> • Facility: <strong><?= htmlspecialchars($log['facility']) ?></strong></div>
          </div>
          <div class="text-right">
            <div class="font-black text-teal-600 text-base font-mono">$<?= number_format($log['recovered_value_usd'], 2) ?></div>
            <span class="text-[10px] text-slate-400 font-mono"><?= date('M j, Y', strtotime($log['timestamp'])) ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Right: Platform Economic Sliders -->
  <div class="lg:col-span-5 bg-white/90 p-6 rounded-3xl border border-slate-200 shadow-warm-md">
    <h3 class="font-extrabold text-base text-slate-900 mb-1">Platform Economic Sliders</h3>
    <p class="text-xs text-slate-500 mb-5">Governs transparent formula matching across Zimbabwe.</p>

    <form method="POST" action="/admin.php" class="space-y-4">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
      <input type="hidden" name="action" value="save_configs" />

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Marketplace Coordination Fee (%)</label>
        <input type="number" name="platform_fee_pct" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" value="<?= htmlspecialchars($configs['platform_fee_pct'] ?? '4.0') ?>" step="0.1" required />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Base Freight Rate ($ / kg / km)</label>
        <input type="number" name="transport_per_km" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" value="<?= htmlspecialchars($configs['transport_per_km'] ?? '0.0015') ?>" step="0.0001" required />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Enactus Target Revenue ($ USD)</label>
        <input type="number" name="enactus_target_usd" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold" value="<?= htmlspecialchars($configs['enactus_target_usd'] ?? '50000') ?>" step="1000" required />
      </div>

      <button type="submit" class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-warm-md transition-all mt-4">
        Save Platform Parameters
      </button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
