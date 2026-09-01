<?php
/**
 * VUNOTHO SMALLHOLDER FARMER OPERATIONS DESK
 * Enterprise Edition: Official Brand Logo, Calm Botanical Atmosphere, HD Vector Icons, Collapsible Sidebar, Direct Sign Out
 */
require_once __DIR__ . '/api/session.php';
require_once __DIR__ . '/api/db.php';

// Strict Server-Side Role Guard
$user = require_role('farmer');
$pdo = get_db_connection();

$message = '';
$error = '';
$activeTab = sanitize_string($_GET['tab'] ?? 'dashboard');

// =========================================================================
// 1. SERVER-SIDE FORM ACTIONS
// =========================================================================
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = $_POST['action'] ?? '';
    $csrf = $_POST['csrf_token'] ?? '';

    if (!validate_csrf_token($csrf)) {
        $error = 'Security session expired. Please refresh and try again.';
    } else {
        if ($action === 'create_listing') {
            $crop = sanitize_string($_POST['crop'] ?? 'Tomatoes');
            $quantityKg = sanitize_numeric($_POST['quantity_kg'] ?? 0);
            $quality = sanitize_string($_POST['quality'] ?? 'Tier 1: Fresh Market Supermarket Spec');
            $district = sanitize_string($_POST['district'] ?? ($user['district'] ?? 'Bulawayo'));

            if ($quantityKg <= 0) {
                $error = 'Please enter a valid harvest volume in kilograms.';
            } else {
                $listId = 'LOT-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -3));
                $stmt = $pdo->prepare("
                    INSERT INTO listings (id, farmer_id, farmer_name, crop, quantity_kg, quality, lat, lng, district, sync_status, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $listId, $user['id'], $user['name'], $crop, $quantityKg, $quality, -20.1500, 28.5833, $district, 'Synced', 'Open', date('c')
                ]);
                $message = "Harvest lot of {$quantityKg}kg {$crop} ({$listId}) logged successfully!";
            }
        } elseif ($action === 'delete_listing') {
            $lotId = sanitize_string($_POST['lot_id'] ?? '');
            if ($lotId) {
                $stmt = $pdo->prepare("DELETE FROM listings WHERE id = ? AND (farmer_id = ? OR farmer_name = ?)");
                $stmt->execute([$lotId, $user['id'], $user['name']]);
                $message = "Produce lot {$lotId} removed from inventory.";
            }
        } elseif ($action === 'accept_order') {
            $crop = sanitize_string($_POST['crop'] ?? 'Tomatoes');
            $buyerName = sanitize_string($_POST['buyer_name'] ?? 'Bulawayo Wholesalers');
            $quantityKg = sanitize_numeric($_POST['quantity_kg'] ?? 100);
            $pricePerKg = sanitize_numeric($_POST['price_per_kg'] ?? 0.42);

            $gross = $quantityKg * $pricePerKg;
            $freight = $quantityKg * 35 * 0.0015 * 0.65;
            $fee = $gross * 0.04;
            $net = max(0, $gross - $freight - $fee);

            $txId = 'TX-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -4));
            $stmt = $pdo->prepare("
                INSERT INTO transactions (id, reference, payment_method, farmer_id, farmer_name, buyer_id, buyer_name, crop, quantity_kg, gross_total, transport_deduction, platform_fee, net_payout, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $txId, 'ECO-' . rand(100000, 999999), 'EcoCash Mobile Wallet', $user['id'], $user['name'],
                'BUYER-' . strtoupper(substr(md5($buyerName), 0, 6)), $buyerName, $crop, $quantityKg,
                $gross, $freight, $fee, $net, 'Settled', date('c')
            ]);
            $message = "Order accepted! \${$net} net payout queued for {$quantityKg}kg {$crop} via EcoCash.";
        } elseif ($action === 'book_transport') {
            $route = sanitize_string($_POST['route'] ?? 'Gwanda - Bulawayo');
            $crop = sanitize_string($_POST['crop'] ?? 'Tomatoes');
            $weightKg = sanitize_numeric($_POST['weight_kg'] ?? 150);

            $manifestId = 'MAN-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -3));
            $stmt = $pdo->prepare("
                INSERT INTO manifests (id, cluster_id, transporter_id, crop, district, total_weight_kg, stops_count, est_payout, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $manifestId, 'CLUSTER-' . strtoupper(substr(md5($route), 0, 4)), 'TR-789', $crop,
                $user['district'] ?? 'Bulawayo', $weightKg, 2, $weightKg * 0.05, 'Confirmed Scheduled', date('c')
            ]);
            $message = "Transport pickup confirmed for {$weightKg}kg {$crop} along {$route} corridor!";
        } elseif ($action === 'update_profile') {
            $name = sanitize_string($_POST['name'] ?? $user['name']);
            $phone = sanitize_string($_POST['phone'] ?? ($user['email_or_phone'] ?? ''));
            $district = sanitize_string($_POST['district'] ?? ($user['district'] ?? 'Bulawayo'));
            $province = sanitize_string($_POST['province'] ?? ($user['province'] ?? 'Zimbabwe'));
            $mainProduce = sanitize_string($_POST['main_produce'] ?? 'Tomatoes, Onions, Potatoes');

            $stmt = $pdo->prepare("UPDATE users SET name = ?, email_or_phone = ?, district = ?, province = ?, main_produce = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $district, $province, $mainProduce, $user['id']]);

            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email_or_phone'] = $phone;
            $_SESSION['user']['district'] = $district;
            $_SESSION['user']['province'] = $province;
            $user = $_SESSION['user'];

            $message = "Farm profile and payment settings updated successfully!";
        }
    }
}

// =========================================================================
// 2. FETCH REAL DATABASE RECORDS
// =========================================================================
$myListingsStmt = $pdo->prepare("SELECT * FROM listings WHERE farmer_id = ? OR farmer_name = ? ORDER BY created_at DESC");
$myListingsStmt->execute([$user['id'], $user['name']]);
$myListings = $myListingsStmt->fetchAll();

if (empty($myListings)) {
    $myListings = [
        ['id' => 'LOT-01', 'crop' => 'Tomatoes', 'quantity_kg' => 180, 'quality' => 'Tier 1: Fresh Market Supermarket Spec', 'est_price' => 0.42, 'demand_status' => 'Good Demand', 'status' => 'Ready for Pickup', 'created_at' => date('Y-m-d H:i', strtotime('-1 day'))],
        ['id' => 'LOT-02', 'crop' => 'Onions', 'quantity_kg' => 120, 'quality' => 'Tier 1: Grade A (10kg Pocket)', 'est_price' => 0.35, 'demand_status' => 'Moderate Demand', 'status' => 'Ready for Pickup', 'created_at' => date('Y-m-d H:i', strtotime('-2 days'))],
        ['id' => 'LOT-03', 'crop' => 'Potatoes', 'quantity_kg' => 80, 'quality' => 'Tier 1: Grade A (15kg Mesh)', 'est_price' => 0.30, 'demand_status' => 'High Demand', 'status' => 'In Storage', 'created_at' => date('Y-m-d H:i', strtotime('-3 days'))],
        ['id' => 'LOT-04', 'crop' => 'Leafy Greens', 'quantity_kg' => 40, 'quality' => 'Tier 2: Agro-Processing Spec', 'est_price' => 0.25, 'demand_status' => 'Good Demand', 'status' => 'Ready for Pickup', 'created_at' => date('Y-m-d H:i', strtotime('-4 days'))]
    ];
}

$demandsStmt = $pdo->query("SELECT * FROM demands ORDER BY created_at DESC LIMIT 8");
$demands = $demandsStmt->fetchAll();

if (empty($demands)) {
    $demands = [
        ['id' => 'DEM-01', 'buyer_name' => 'FreshMart Bulawayo', 'crop' => 'Tomatoes', 'target_quantity_kg' => 100, 'offered_price_per_kg' => 0.42, 'delivery_hub' => 'Belmont Wholesale Depot, Bulawayo', 'status' => 'Confirmed'],
        ['id' => 'DEM-02', 'buyer_name' => 'Green Basket Stores', 'crop' => 'Onions', 'target_quantity_kg' => 80, 'offered_price_per_kg' => 0.35, 'delivery_hub' => 'Bradfield Shopping Centre', 'status' => 'Processing'],
        ['id' => 'DEM-03', 'buyer_name' => 'Urban Chefs', 'crop' => 'Potatoes', 'target_quantity_kg' => 60, 'offered_price_per_kg' => 0.30, 'delivery_hub' => 'Bulawayo Central Kitchen', 'status' => 'Pending'],
        ['id' => 'DEM-04', 'buyer_name' => 'Hlanganani Foods', 'crop' => 'Leafy Greens', 'target_quantity_kg' => 40, 'offered_price_per_kg' => 0.25, 'delivery_hub' => 'Kelvin Industrial Area', 'status' => 'Confirmed']
    ];
}

$txStmt = $pdo->prepare("SELECT * FROM transactions WHERE farmer_id = ? OR farmer_name = ? ORDER BY created_at DESC");
$txStmt->execute([$user['id'], $user['name']]);
$transactions = $txStmt->fetchAll();

if (empty($transactions)) {
    $transactions = [
        ['id' => 'TX-8921', 'reference' => 'ECO-782190', 'payment_method' => 'EcoCash Mobile Wallet', 'buyer_name' => 'FreshMart Bulawayo', 'crop' => 'Tomatoes', 'quantity_kg' => 100, 'gross_total' => 42.00, 'transport_deduction' => 3.41, 'platform_fee' => 1.68, 'net_payout' => 36.91, 'status' => 'Settled', 'created_at' => date('d M Y, H:i', strtotime('-2 hours'))],
        ['id' => 'TX-8920', 'reference' => 'ECO-671239', 'payment_method' => 'EcoCash Mobile Wallet', 'buyer_name' => 'Hlanganani Foods', 'crop' => 'Leafy Greens', 'quantity_kg' => 40, 'gross_total' => 10.00, 'transport_deduction' => 0.91, 'platform_fee' => 0.40, 'net_payout' => 8.69, 'status' => 'Settled', 'created_at' => date('d M Y, H:i', strtotime('-1 day'))]
    ];
}

$totalVolume = array_reduce($myListings, function($sum, $l) { return $sum + floatval($l['quantity_kg'] ?? 0); }, 0);
if ($totalVolume == 0) $totalVolume = 420;

$totalEarnings = array_reduce($transactions, function($sum, $t) { return $sum + floatval($t['net_payout'] ?? 0); }, 0);
if ($totalEarnings == 0) $totalEarnings = 186.40;

$nameParts = explode(' ', trim($user['name'] ?? 'Mako Gufe'));
$firstName = $nameParts[0];

$pageTitle = ucfirst($activeTab) . ' — Vunotho Farmer Operations';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  
  <meta name="description" content="Vunotho Farmer Operations Desk — Turning Produce into Protected Economic Value." />
  <meta name="theme-color" content="#071726" />
  
  <!-- Manifest & Favicons -->
  <link rel="manifest" href="/manifest.json" />
  <link rel="icon" type="image/png" href="/images/vunotho_logo.png" />
  <link rel="apple-touch-icon" href="/images/vunotho_logo.png" />

  <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <!-- Stylesheets -->
  <link rel="stylesheet" href="/css/tailwind.css?v=5.0" />
  <link rel="stylesheet" href="/css/portal_dashboard.css?v=4.0" />
</head>
<body>

<div class="vunotho-app-layout">

  <!-- ==================== 1. SIDEBAR ==================== -->
  <?php require_once __DIR__ . '/includes/portal_sidebar.php'; ?>

  <!-- ==================== 2. MAIN APP CANVAS ==================== -->
  <main class="vn-main-canvas">

    <!-- Top Header Canvas -->
    <header class="vn-top-header">
      <div class="flex items-center gap-3">
        <button class="lg:hidden p-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold" onclick="toggleMobileSidebar(true)">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <div>
          <h1 class="text-2xl lg:text-[1.75rem] font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
            <span>Welcome back, <?= htmlspecialchars($firstName) ?>!</span>
            <img src="/images/vunotho_logo.png" class="w-7 h-7 inline-block rounded-xl object-cover shadow-sm border border-emerald-500/30" alt="Vunotho Logo" />
          </h1>
          <p class="text-xs text-slate-500 font-medium mt-0.5">
            Here's what's happening with your farm today.
          </p>
        </div>
      </div>

      <!-- Right Header Actions -->
      <div class="flex items-center gap-4">
        <!-- Notification Bell -->
        <div class="relative">
          <button class="w-10 h-10 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-700 hover:text-slate-900 transition-all" onclick="showToast('You have 3 active updates: Tomato price surge, new buyer match, transport confirmed.', 'info')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span class="absolute top-0 right-0 w-4 h-4 rounded-full bg-amber-500 text-white font-bold text-[9px] flex items-center justify-center">
              3
            </span>
          </button>
        </div>

        <!-- User Profile Pill with Working Dropdown & Direct Sign Out -->
        <div class="relative">
          <div id="user-header-pill" class="vn-user-header-pill" onclick="toggleUserDropdown(event)">
            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-600 to-emerald-800 flex items-center justify-center text-white text-xs font-bold shadow-sm">
              <?= strtoupper(substr($firstName, 0, 1)) ?>
            </div>
            <div class="flex flex-col text-left pr-1">
              <span class="text-xs font-bold text-slate-900 leading-tight"><?= htmlspecialchars($user['name'] ?? 'Mako Gufe') ?></span>
              <span class="text-[10px] text-slate-500"><?= htmlspecialchars($user['district'] ?? 'Bulawayo') ?>, Zimbabwe</span>
            </div>
            <span class="text-slate-400 text-xs pl-1">⌵</span>
          </div>

          <!-- Working Dropdown Menu -->
          <div id="user-dropdown-menu" class="vn-user-dropdown-menu">
            <a href="/farmer.php?tab=settings" class="vn-dropdown-link">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
              <span>Farm Profile</span>
            </a>
            <a href="/farmer.php?tab=payments" class="vn-dropdown-link">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
              <span>EcoCash Wallet</span>
            </a>
            <div class="my-1 border-t border-slate-100"></div>
            <a href="/logout.php" class="vn-dropdown-link logout">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
              <span>Sign Out</span>
            </a>
          </div>
        </div>
      </div>
    </header>

    <!-- Global Toast / Alert Banners -->
    <?php if (!empty($message)): ?>
      <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
        <span>✓ <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></span>
        <button onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">✕</button>
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-sm">
        <span>⚠️ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
        <button onclick="this.parentElement.remove()" class="text-rose-700 font-bold">✕</button>
      </div>
    <?php endif; ?>

    <!-- ==================== 3. TOP 4 METRIC KPI CARDS ==================== -->
    <section class="vn-kpi-grid">
      <!-- KPI 1: My Produce (HD Bag Icon) -->
      <a href="/farmer.php?tab=produce" class="vn-kpi-card text-decoration-none">
        <div class="vn-kpi-icon-circle icon-green">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 7H16V6C16 3.79086 14.2091 2 12 2C9.79086 2 8 3.79086 8 6V7H5C3.89543 7 3 7.89543 3 9V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V9C21 7.89543 20.1046 7 19 7ZM10 6C10 4.89543 10.8954 4 12 4C13.1046 4 14 4.89543 14 6V7H10V6Z" fill="#FFFFFF"/>
          </svg>
        </div>
        <div class="flex flex-col">
          <span class="text-xs font-semibold text-slate-500">My Produce</span>
          <span class="text-xl font-extrabold text-slate-900 mt-0.5 font-mono"><?= number_format($totalVolume) ?> <span class="text-sm font-bold text-slate-600">kg</span></span>
          <span class="text-[11px] font-bold text-emerald-600 mt-0.5">Available</span>
        </div>
      </a>

      <!-- KPI 2: Est. Earnings (HD Dollar Icon) -->
      <a href="/farmer.php?tab=payments" class="vn-kpi-card text-decoration-none">
        <div class="vn-kpi-icon-circle icon-gold">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><path d="M12 6v12M15 9.5a2.5 2.5 0 0 0-5 0c0 3 5 2 5 5a2.5 2.5 0 0 1-5 0"/>
          </svg>
        </div>
        <div class="flex flex-col">
          <span class="text-xs font-semibold text-slate-500">Est. Earnings</span>
          <span class="text-xl font-extrabold text-slate-900 mt-0.5 font-mono">$<?= number_format($totalEarnings, 2) ?></span>
          <span class="text-[11px] font-medium text-slate-500 mt-0.5">This week</span>
        </div>
      </a>

      <!-- KPI 3: Active Buyers (HD Users Icon) -->
      <a href="/farmer.php?tab=buyers" class="vn-kpi-card text-decoration-none">
        <div class="vn-kpi-icon-circle icon-teal">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
        <div class="flex flex-col">
          <span class="text-xs font-semibold text-slate-500">Active Buyers</span>
          <span class="text-xl font-extrabold text-slate-900 mt-0.5 font-mono">12</span>
          <span class="text-[11px] font-medium text-slate-500 mt-0.5">Interested</span>
        </div>
      </a>

      <!-- KPI 4: Transport (HD Truck Icon) -->
      <a href="/farmer.php?tab=transport" class="vn-kpi-card text-decoration-none">
        <div class="vn-kpi-icon-circle icon-orange">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
          </svg>
        </div>
        <div class="flex flex-col">
          <span class="text-xs font-semibold text-slate-500">Transport</span>
          <span class="text-xl font-extrabold text-slate-900 mt-0.5 font-mono">2</span>
          <span class="text-[11px] font-medium text-slate-500 mt-0.5">Bookings</span>
        </div>
      </a>
    </section>

    <!-- =========================================================================
         4. DYNAMIC MULTI-PAGE TAB ROUTING
         ========================================================================= -->
    
    <?php if ($activeTab === 'dashboard' || empty($activeTab)): ?>
      <!-- ==================== VIEW 1: MAIN DASHBOARD ==================== -->
      <div class="vn-content-grid">
        <!-- COLUMN 1 (LEFT) -->
        <div class="space-y-6">
          <!-- My Produce Overview -->
          <div class="vn-card">
            <div class="vn-card-header">
              <h3 class="vn-card-title">My Produce Overview</h3>
              <a href="/farmer.php?tab=produce" class="vn-link-action">View all</a>
            </div>
            <div class="space-y-1">
              <?php foreach (array_slice($myListings, 0, 4) as $lot): ?>
                <?php
                  $cropName = $lot['crop'] ?? 'Crop';
                  $demandBadge = (stripos($cropName, 'potato') !== false) ? 'demand-high' : ((stripos($cropName, 'onion') !== false) ? 'demand-moderate' : 'demand-good');
                  $demandText = (stripos($cropName, 'potato') !== false) ? 'High Demand' : ((stripos($cropName, 'onion') !== false) ? 'Moderate Demand' : 'Good Demand');
                  $avgPrice = $lot['est_price'] ?? 0.42;
                ?>
                <a href="/farmer.php?tab=produce" class="vn-produce-row">
                  <div class="flex items-center gap-3">
                    <div class="vn-produce-img">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                      </svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-slate-900 text-xs"><?= htmlspecialchars($cropName) ?></h4>
                      <span class="text-[11px] text-slate-500 font-medium font-mono"><?= number_format($lot['quantity_kg']) ?> kg</span>
                    </div>
                  </div>
                  <div class="text-right">
                    <div class="text-[10px] text-slate-400">Avg. Price</div>
                    <div class="text-xs font-bold text-slate-800 font-mono">$<?= number_format($avgPrice, 2) ?> <span class="text-[10px] font-normal text-slate-500">/kg</span></div>
                  </div>
                  <div>
                    <span class="vn-demand-pill <?= $demandBadge ?>"><?= $demandText ?></span>
                  </div>
                  <span class="text-slate-300 text-xs font-bold">›</span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Vunotho Impact -->
          <div class="vn-card">
            <div class="vn-card-header">
              <h3 class="vn-card-title">Vunotho Impact</h3>
              <span class="text-xs text-slate-500 font-medium cursor-pointer" onclick="showToast('District impact cluster: Bulawayo agricultural basin.', 'info')">This month ⌵</span>
            </div>
            <div class="vn-impact-row">
              <div class="vn-impact-stat-item">
                <div class="vn-impact-circle" style="background-color: #16A34A; color: white;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <div class="flex flex-col">
                  <span class="text-base font-extrabold text-slate-900 leading-tight font-mono">56</span>
                  <span class="text-[10px] text-slate-500 leading-tight">Farmers Connected</span>
                </div>
              </div>
              <div class="vn-impact-stat-item">
                <div class="vn-impact-circle" style="background-color: #FBBF24; color: white;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M15 9.5a2.5 2.5 0 0 0-5 0"/></svg>
                </div>
                <div class="flex flex-col">
                  <span class="text-base font-extrabold text-slate-900 leading-tight font-mono">$2,840</span>
                  <span class="text-[10px] text-slate-500 leading-tight">Total Earnings Generated</span>
                </div>
              </div>
              <div class="vn-impact-stat-item">
                <div class="vn-impact-circle" style="background-color: #0D9488; color: white;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="flex flex-col">
                  <span class="text-base font-extrabold text-slate-900 leading-tight font-mono">18</span>
                  <span class="text-[10px] text-slate-500 leading-tight">Deliveries Completed</span>
                </div>
              </div>
              <div class="vn-impact-stat-item">
                <div class="vn-impact-circle" style="background-color: #15803D; color: white;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5"/></svg>
                </div>
                <div class="flex flex-col">
                  <span class="text-base font-extrabold text-slate-900 leading-tight font-mono">1.2 ton</span>
                  <span class="text-[10px] text-slate-500 leading-tight">Food Saved from Waste</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- COLUMN 2 (CENTER) -->
        <div class="space-y-6">
          <!-- Recent Orders -->
          <div class="vn-card">
            <div class="vn-card-header">
              <h3 class="vn-card-title">Recent Orders</h3>
              <a href="/farmer.php?tab=orders" class="vn-link-action">View all</a>
            </div>
            <div class="space-y-1">
              <?php foreach (array_slice($demands, 0, 4) as $dem): ?>
                <?php
                  $totalPayout = $dem['target_quantity_kg'] * $dem['offered_price_per_kg'];
                  $statusDot = ($dem['status'] === 'Confirmed') ? 'text-emerald-600' : (($dem['status'] === 'Processing') ? 'text-amber-500' : 'text-cyan-600');
                ?>
                <div class="vn-order-row">
                  <div class="flex items-center gap-3">
                    <div class="vn-company-avatar text-slate-700">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-slate-900 text-xs"><?= htmlspecialchars($dem['buyer_name']) ?></h4>
                      <span class="text-[11px] text-slate-500"><?= htmlspecialchars($dem['crop']) ?> • <?= number_format($dem['target_quantity_kg']) ?> kg</span>
                    </div>
                  </div>
                  <div class="text-right">
                    <div class="text-xs font-black text-slate-900 font-mono">$<?= number_format($totalPayout, 2) ?></div>
                    <span class="text-[10.5px] font-bold <?= $statusDot ?>">● <?= htmlspecialchars($dem['status']) ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- COLUMN 3 (RIGHT) -->
        <div class="vn-col-3 space-y-6">
          <!-- Alerts & Notifications -->
          <div class="vn-card">
            <div class="vn-card-header">
              <h3 class="vn-card-title flex items-center gap-1.5">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <span>Alerts & Notifications</span>
              </h3>
              <a href="/farmer.php?tab=messages" class="vn-link-action">View all</a>
            </div>
            <div class="space-y-1">
              <div class="vn-alert-row">
                <div class="vn-alert-icon bg-emerald-100 text-emerald-700">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="font-bold text-slate-900 text-xs">Price update!</h4>
                  <p class="text-[11px] text-slate-500">Tomatoes price increased to $0.42/kg</p>
                </div>
                <span class="text-[10px] text-slate-400">2h ago</span>
              </div>
              <div class="vn-alert-row">
                <div class="vn-alert-icon bg-teal-100 text-teal-700">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="font-bold text-slate-900 text-xs">New buyer match</h4>
                  <p class="text-[11px] text-slate-500">Urban Chefs is interested in your potatoes</p>
                </div>
                <span class="text-[10px] text-slate-400">4h ago</span>
              </div>
              <div class="vn-alert-row">
                <div class="vn-alert-icon bg-orange-100 text-orange-700">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 8"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="font-bold text-slate-900 text-xs">Transport update</h4>
                  <p class="text-[11px] text-slate-500">Your transport to Bulawayo market is confirmed</p>
                </div>
                <span class="text-[10px] text-slate-400">6h ago</span>
              </div>
            </div>
          </div>

          <!-- Market Prices Today -->
          <div class="vn-card">
            <div class="vn-card-header">
              <h3 class="vn-card-title">Market Prices Today</h3>
              <a href="/farmer.php?tab=prices" class="vn-link-action">View all</a>
            </div>
            <div class="space-y-1">
              <div class="vn-market-price-item">
                <div class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                  <span class="font-bold text-slate-800 text-xs">Tomatoes</span>
                </div>
                <div class="flex items-center gap-3">
                  <span class="font-bold text-slate-800 text-xs font-mono">$0.42 <span class="text-[10px] text-slate-400 font-normal">/kg</span></span>
                  <span class="vn-delta-up">↑ 12%</span>
                </div>
              </div>
              <div class="vn-market-price-item">
                <div class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                  <span class="font-bold text-slate-800 text-xs">Onions</span>
                </div>
                <div class="flex items-center gap-3">
                  <span class="font-bold text-slate-800 text-xs font-mono">$0.35 <span class="text-[10px] text-slate-400 font-normal">/kg</span></span>
                  <span class="vn-delta-up">↑ 8%</span>
                </div>
              </div>
              <div class="vn-market-price-item">
                <div class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-amber-700"></span>
                  <span class="font-bold text-slate-800 text-xs">Potatoes</span>
                </div>
                <div class="flex items-center gap-3">
                  <span class="font-bold text-slate-800 text-xs font-mono">$0.30 <span class="text-[10px] text-slate-400 font-normal">/kg</span></span>
                  <span class="vn-delta-up">↑ 5%</span>
                </div>
              </div>
              <div class="vn-market-price-item">
                <div class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                  <span class="font-bold text-slate-800 text-xs">Leafy Greens</span>
                </div>
                <div class="flex items-center gap-3">
                  <span class="font-bold text-slate-800 text-xs font-mono">$0.25 <span class="text-[10px] text-slate-400 font-normal">/kg</span></span>
                  <span class="vn-delta-down">↓ 3%</span>
                </div>
              </div>
            </div>
            <div class="mt-3 pt-2 text-[10px] text-emerald-700 font-semibold">
              <a href="/farmer.php?tab=prices" class="hover:underline">Source: Vunotho Price Intelligence →</a>
            </div>
          </div>

          <!-- Need Help? Card with Delicate Leaf Branch Artwork -->
          <div class="vn-need-help-card">
            <!-- HD SVG Botanical Leaf Branch Illustration -->
            <svg class="vn-leaf-watermark-svg" viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20 120C40 100 70 70 120 20" stroke="#16A34A" stroke-width="4" stroke-linecap="round"/>
              <path d="M50 90C40 70 60 50 80 60C80 80 60 100 50 90Z" fill="#16A34A" fill-opacity="0.3"/>
              <path d="M80 60C70 40 90 20 110 30C110 50 90 70 80 60Z" fill="#16A34A" fill-opacity="0.3"/>
              <path d="M40 100C25 90 35 70 55 75C55 95 45 105 40 100Z" fill="#16A34A" fill-opacity="0.25"/>
            </svg>
            
            <div class="relative z-10">
              <h3 class="font-extrabold text-slate-900 text-sm">Need Help?</h3>
              <p class="text-[11px] text-slate-600 mt-1 mb-3 leading-snug">
                Contact our support team we're here to help you grow.
              </p>
              <a href="https://wa.me/263779634613" target="_blank" class="vn-chat-btn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                <span>Chat with us</span>
              </a>
            </div>
          </div>
        </div>
      </div>

    <?php elseif ($activeTab === 'produce'): ?>
      <!-- ==================== VIEW 2: MY PRODUCE MANAGER ==================== -->
      <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-sm">
          <div>
            <h2 class="text-xl font-extrabold text-slate-900">Registered Harvest Inventory</h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage your crops, quality tiers, and track pickup readiness.</p>
          </div>
          <button class="vn-btn-list-produce" onclick="openProduceModal()">
            <span>+</span>
            <span>Register Harvest Lot</span>
          </button>
        </div>

        <div class="vn-card">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead>
                <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                  <th class="pb-3 font-semibold">Lot ID / Commodity</th>
                  <th class="pb-3 font-semibold">Quality Grading Tier</th>
                  <th class="pb-3 font-semibold">Volume (kg)</th>
                  <th class="pb-3 font-semibold">Est. Unit Price</th>
                  <th class="pb-3 font-semibold">Status</th>
                  <th class="pb-3 font-semibold text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <?php foreach ($myListings as $lot): ?>
                  <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3.5">
                      <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5"/></svg>
                        </span>
                        <div>
                          <strong class="text-slate-900 font-bold block"><?= htmlspecialchars($lot['crop']) ?></strong>
                          <span class="text-[10px] text-slate-400 font-mono"><?= htmlspecialchars($lot['id']) ?></span>
                        </div>
                      </div>
                    </td>
                    <td class="py-3.5 text-slate-600"><?= htmlspecialchars($lot['quality'] ?? 'Tier 1: Fresh Spec') ?></td>
                    <td class="py-3.5 font-bold font-mono text-slate-900"><?= number_format($lot['quantity_kg']) ?> kg</td>
                    <td class="py-3.5 font-bold text-emerald-700 font-mono">$<?= number_format($lot['est_price'] ?? 0.42, 2) ?>/kg</td>
                    <td class="py-3.5">
                      <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">
                        ● <?= htmlspecialchars($lot['status'] ?? 'Ready') ?>
                      </span>
                    </td>
                    <td class="py-3.5 text-right">
                      <div class="flex items-center justify-end gap-2">
                        <a href="/farmer.php?tab=transport" class="px-2.5 py-1 rounded-lg bg-orange-50 text-orange-700 hover:bg-orange-100 font-bold text-[11px]">Book Freight</a>
                        <form method="POST" action="/farmer.php?tab=produce" onsubmit="return confirm('Remove this harvest lot?');" class="inline">
                          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
                          <input type="hidden" name="action" value="delete_listing" />
                          <input type="hidden" name="lot_id" value="<?= htmlspecialchars($lot['id']) ?>" />
                          <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-[11px]">Delete</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    <?php elseif ($activeTab === 'prices'): ?>
      <!-- ==================== VIEW 3: LIVE MARKET PRICES INTELLIGENCE ==================== -->
      <div class="space-y-6">
        <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold text-slate-900">National Commodity Price Intelligence</h2>
            <p class="text-xs text-slate-500 mt-0.5">Real-time benchmark rates across primary wholesale depots in Zimbabwe.</p>
          </div>
          <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono">
            Updated 10m ago • Live
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="vn-card text-center">
            <h4 class="font-bold text-slate-900 text-sm">Roma Tomatoes</h4>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1">$0.42 <span class="text-xs font-normal text-slate-500">/kg</span></div>
            <div class="vn-delta-up mt-1">↑ 12% vs last week</div>
            <p class="text-[10px] text-slate-400 mt-2">Belmont Wholesale: $0.45 • Mbare: $0.40</p>
          </div>

          <div class="vn-card text-center">
            <h4 class="font-bold text-slate-900 text-sm">Brown Onions</h4>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1">$0.35 <span class="text-xs font-normal text-slate-500">/kg</span></div>
            <div class="vn-delta-up mt-1">↑ 8% vs last week</div>
            <p class="text-[10px] text-slate-400 mt-2">Belmont Wholesale: $0.38 • Sakubva: $0.34</p>
          </div>

          <div class="vn-card text-center">
            <h4 class="font-bold text-slate-900 text-sm">Table Potatoes</h4>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1">$0.30 <span class="text-xs font-normal text-slate-500">/kg</span></div>
            <div class="vn-delta-up mt-1">↑ 5% vs last week</div>
            <p class="text-[10px] text-slate-400 mt-2">15kg Pocket: $4.50 • High demand</p>
          </div>

          <div class="vn-card text-center">
            <h4 class="font-bold text-slate-900 text-sm">Covo / Rape Greens</h4>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1">$0.25 <span class="text-xs font-normal text-slate-500">/kg</span></div>
            <div class="vn-delta-down mt-1">↓ 3% vs last week</div>
            <p class="text-[10px] text-slate-400 mt-2">Fresh daily bundles • Steady supply</p>
          </div>
        </div>
      </div>

    <?php elseif ($activeTab === 'buyers'): ?>
      <!-- ==================== VIEW 4: VERIFIED COMMERCIAL BUYERS ==================== -->
      <div class="space-y-6">
        <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-sm">
          <h2 class="text-xl font-extrabold text-slate-900">Verified Off-Taker Directory</h2>
          <p class="text-xs text-slate-500 mt-0.5">Direct commercial contracts with guaranteed EcoCash mobile money payouts.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <?php foreach ($demands as $dem): ?>
            <div class="vn-card flex flex-col justify-between">
              <div>
                <div class="flex justify-between items-start mb-3">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($dem['buyer_name']) ?></h4>
                      <span class="text-[11px] text-slate-500">📍 <?= htmlspecialchars($dem['delivery_hub']) ?></span>
                    </div>
                  </div>
                  <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs font-mono">KYC Verified</span>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex justify-between items-center text-xs">
                  <span>Seeking: <strong><?= htmlspecialchars($dem['crop']) ?></strong> (<?= number_format($dem['target_quantity_kg']) ?> kg)</span>
                  <span class="font-black text-slate-900 font-mono">$<?= number_format($dem['offered_price_per_kg'], 2) ?>/kg</span>
                </div>
              </div>
              <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center">
                <span class="text-[11px] text-slate-500">Settlement on collection</span>
                <form method="POST" action="/farmer.php?tab=orders">
                  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
                  <input type="hidden" name="action" value="accept_order" />
                  <input type="hidden" name="demand_id" value="<?= htmlspecialchars($dem['id']) ?>" />
                  <input type="hidden" name="crop" value="<?= htmlspecialchars($dem['crop']) ?>" />
                  <input type="hidden" name="buyer_name" value="<?= htmlspecialchars($dem['buyer_name']) ?>" />
                  <input type="hidden" name="quantity_kg" value="<?= htmlspecialchars($dem['target_quantity_kg']) ?>" />
                  <input type="hidden" name="price_per_kg" value="<?= htmlspecialchars($dem['offered_price_per_kg']) ?>" />
                  <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm">
                    Match & Contract Lot →
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    <?php elseif ($activeTab === 'transport'): ?>
      <!-- ==================== VIEW 5: RURAL FREIGHT & 2.5T POOLING ==================== -->
      <div class="space-y-6">
        <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold text-slate-900">2.5-Tonne Rural Truck Logistics Pooling</h2>
            <p class="text-xs text-slate-500 mt-0.5">Clustering smallholder harvests along primary corridors to save 35% on transport.</p>
          </div>
          <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-800 text-xs font-bold font-mono">
            35% Pooled Savings
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="vn-card space-y-4">
            <h3 class="font-bold text-slate-900 text-sm">Active Corridor: Gwanda ➔ Bulawayo</h3>
            <p class="text-xs text-slate-500">2.5T Canter scheduled for tomorrow 08:30 AM pickup.</p>
            <div>
              <div class="flex justify-between text-xs font-bold mb-1">
                <span>Truck Capacity Loaded</span>
                <span class="text-emerald-700 font-mono">1,800 / 2,500 kg (72%)</span>
              </div>
              <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-orange-500 to-amber-500 rounded-full" style="width: 72%;"></div>
              </div>
            </div>
            <form method="POST" action="/farmer.php?tab=transport" class="pt-2">
              <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
              <input type="hidden" name="action" value="book_transport" />
              <input type="hidden" name="route" value="Gwanda - Bulawayo" />
              <input type="hidden" name="crop" value="Tomatoes" />
              <input type="hidden" name="weight_kg" value="180" />
              <button type="submit" class="w-full py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold text-xs shadow-md">
                Book 180kg Tomatoes onto this Truck →
              </button>
            </form>
          </div>

          <div class="vn-card space-y-4">
            <h3 class="font-bold text-slate-900 text-sm">Active Corridor: Nyanga ➔ Harare</h3>
            <p class="text-xs text-slate-500">Scheduled for Thursday morning departure to Mbare Musika Depot.</p>
            <div>
              <div class="flex justify-between text-xs font-bold mb-1">
                <span>Truck Capacity Loaded</span>
                <span class="text-emerald-700 font-mono">2,150 / 2,500 kg (86%)</span>
              </div>
              <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full" style="width: 86%;"></div>
              </div>
            </div>
            <button class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md" onclick="showToast('Truck booked for Nyanga route.', 'success')">
              Book Surplus Potatoes onto this Truck →
            </button>
          </div>
        </div>
      </div>

    <?php elseif ($activeTab === 'orders'): ?>
      <!-- ==================== VIEW 6: ORDERS PIPELINE ==================== -->
      <div class="space-y-6">
        <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-sm">
          <h2 class="text-xl font-extrabold text-slate-900">Purchase Orders & Deliveries</h2>
          <p class="text-xs text-slate-500 mt-0.5">Track confirmed orders from commercial off-takers through to settlement.</p>
        </div>

        <div class="vn-card space-y-3">
          <?php foreach ($demands as $dem): ?>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
              <div class="space-y-0.5">
                <div class="flex items-center gap-2">
                  <strong class="text-slate-900 font-bold text-sm"><?= htmlspecialchars($dem['buyer_name']) ?></strong>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold"><?= htmlspecialchars($dem['status'] ?? 'Confirmed') ?></span>
                </div>
                <p class="text-xs text-slate-500">
                  Item: <strong><?= htmlspecialchars($dem['crop']) ?></strong> • Volume: <strong><?= number_format($dem['target_quantity_kg']) ?> kg</strong> @ $<?= number_format($dem['offered_price_per_kg'], 2) ?>/kg
                </p>
                <div class="text-[11px] text-slate-400">Destination: <?= htmlspecialchars($dem['delivery_hub']) ?></div>
              </div>
              <div class="text-right flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-2">
                <div class="text-base font-black text-slate-900 font-mono">$<?= number_format($dem['target_quantity_kg'] * $dem['offered_price_per_kg'], 2) ?></div>
                <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white font-bold text-xs" onclick="showToast('Digital consignment note generated.', 'success')">
                  Dispatch Note 📄
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    <?php elseif ($activeTab === 'payments'): ?>
      <!-- ==================== VIEW 7: PAYMENTS & ECOCASH LEDGER ==================== -->
      <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="vn-card bg-gradient-to-br from-emerald-600 to-emerald-800 text-white border-0">
            <span class="text-xs uppercase font-bold text-emerald-200">Available Wallet Balance</span>
            <div class="text-3xl font-black font-mono mt-1">$<?= number_format($totalEarnings, 2) ?></div>
            <div class="text-xs text-emerald-100 font-medium mt-2">Ready for EcoCash withdrawal</div>
          </div>
          <div class="vn-card">
            <span class="text-xs uppercase font-bold text-slate-400">EcoCash Authorized Phone</span>
            <div class="text-xl font-black text-slate-900 font-mono mt-1"><?= htmlspecialchars($user['email_or_phone'] ?? '0787146103') ?></div>
            <div class="text-xs text-emerald-600 font-bold mt-2">● PIN Authorization Active</div>
          </div>
          <div class="vn-card">
            <span class="text-xs uppercase font-bold text-slate-400">Lifetime Farmgate Payout</span>
            <div class="text-xl font-black text-slate-900 font-mono mt-1">$2,840.00</div>
            <div class="text-xs text-slate-500 font-medium mt-2">18 settled transactions</div>
          </div>
        </div>

        <div class="vn-card">
          <h3 class="vn-card-title mb-4">Itemized Transaction Ledger</h3>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead>
                <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                  <th class="pb-3">Reference / Date</th>
                  <th class="pb-3">Buyer & Lot</th>
                  <th class="pb-3">Gross Value</th>
                  <th class="pb-3">Freight</th>
                  <th class="pb-3">Fee (4%)</th>
                  <th class="pb-3">Net Take-Home</th>
                  <th class="pb-3 text-right">Receipt</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <?php foreach ($transactions as $tx): ?>
                  <tr>
                    <td class="py-3">
                      <strong class="text-slate-900 font-mono"><?= htmlspecialchars($tx['reference']) ?></strong>
                      <div class="text-[10px] text-slate-400"><?= htmlspecialchars($tx['created_at']) ?></div>
                    </td>
                    <td class="py-3">
                      <strong class="text-slate-800"><?= htmlspecialchars($tx['buyer_name']) ?></strong>
                      <div class="text-[10px] text-slate-500"><?= htmlspecialchars($tx['crop']) ?> (<?= number_format($tx['quantity_kg']) ?> kg)</div>
                    </td>
                    <td class="py-3 font-mono font-bold">$<?= number_format($tx['gross_total'], 2) ?></td>
                    <td class="py-3 font-mono text-orange-600">-$<?= number_format($tx['transport_deduction'], 2) ?></td>
                    <td class="py-3 font-mono text-amber-600">-$<?= number_format($tx['platform_fee'], 2) ?></td>
                    <td class="py-3 font-mono font-black text-emerald-700 text-sm">+$<?= number_format($tx['net_payout'], 2) ?></td>
                    <td class="py-3 text-right">
                      <button class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px]" onclick="showToast('Receipt #<?= htmlspecialchars($tx['reference']) ?> downloaded.', 'success')">
                        Receipt 🧾
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    <?php elseif ($activeTab === 'messages'): ?>
      <!-- ==================== VIEW 8: BUYER & HAULIER MESSAGES ==================== -->
      <div class="vn-card space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-sm">FreshMart Bulawayo Procurement Desk</h3>
              <span class="text-[11px] text-emerald-600 font-bold">● Active Now</span>
            </div>
          </div>
          <a href="tel:+263779634613" class="px-3.5 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <span>Call Extension Desk</span>
          </a>
        </div>

        <div class="space-y-3 py-4 max-h-[360px] overflow-y-auto" id="chat-stream">
          <div class="flex items-start gap-2.5">
            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs">🏢</div>
            <div class="bg-slate-100 p-3.5 rounded-2xl rounded-tl-none max-w-md text-xs text-slate-800 leading-relaxed">
              Hi Mako, we saw your 180kg Roma Tomatoes lot. Can you confirm if these are Grade A spec for Thursday delivery to Belmont?
            </div>
          </div>

          <div class="flex items-start gap-2.5 justify-end">
            <div class="bg-emerald-600 text-white p-3.5 rounded-2xl rounded-tr-none max-w-md text-xs leading-relaxed">
              Yes, they are freshly harvested Grade A supermarket spec Roma tomatoes, ready for collection.
            </div>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
          <input type="text" id="chat-input" placeholder="Type message to buyer or transporter..." class="flex-1 px-4 py-2.5 rounded-full bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:bg-white" onkeydown="if(event.key==='Enter') sendChatMessage();" />
          <button class="px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs" onclick="sendChatMessage()">
            Send ↵
          </button>
        </div>
      </div>

    <?php elseif ($activeTab === 'reports'): ?>
      <!-- ==================== VIEW 9: REPORTS & CIRCULAR VALUE ==================== -->
      <div class="space-y-6">
        <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-sm flex justify-between items-center">
          <div>
            <h2 class="text-xl font-extrabold text-slate-900">Farm Yield & Circular Impact Statement</h2>
            <p class="text-xs text-slate-500 mt-0.5">Enactus post-harvest value recovery verification report.</p>
          </div>
          <button class="px-4 py-2 rounded-full bg-slate-900 text-white font-bold text-xs" onclick="showToast('Exporting PDF audit statement...', 'info')">
            Export PDF Audit 🖨️
          </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="vn-card space-y-3">
            <h3 class="font-bold text-slate-900 text-sm">Produce Utilization Breakdown</h3>
            <div class="space-y-2 text-xs">
              <div class="flex justify-between"><span>Fresh Supermarket Spec (Tier 1)</span><strong class="font-mono">82%</strong></div>
              <div class="w-full h-2 bg-slate-100 rounded-full"><div class="h-full bg-emerald-500 rounded-full" style="width: 82%;"></div></div>

              <div class="flex justify-between"><span>Agro-Processing Crisps/Paste (Tier 2)</span><strong class="font-mono">12%</strong></div>
              <div class="w-full h-2 bg-slate-100 rounded-full"><div class="h-full bg-amber-500 rounded-full" style="width: 12%;"></div></div>

              <div class="flex justify-between"><span>Livestock Feed (Tier 3)</span><strong class="font-mono">4%</strong></div>
              <div class="w-full h-2 bg-slate-100 rounded-full"><div class="h-full bg-orange-500 rounded-full" style="width: 4%;"></div></div>

              <div class="flex justify-between"><span>Bio-Compost Biomass (Tier 4)</span><strong class="font-mono">2%</strong></div>
              <div class="w-full h-2 bg-slate-100 rounded-full"><div class="h-full bg-slate-600 rounded-full" style="width: 2%;"></div></div>
            </div>
          </div>

          <div class="vn-card space-y-3">
            <h3 class="font-bold text-slate-900 text-sm">Economic Value Transformation</h3>
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-2 text-xs text-emerald-950">
              <div class="flex justify-between"><span>Net Return Lift vs Predatory Middlemen:</span><strong class="font-bold font-mono text-emerald-700">+28.4%</strong></div>
              <div class="flex justify-between"><span>Freight Savings via 2.5T Load Pooling:</span><strong class="font-bold font-mono text-emerald-700">35.0%</strong></div>
              <div class="flex justify-between"><span>Post-Harvest Crop Loss Rate:</span><strong class="font-bold font-mono text-emerald-700">0.0% (Zero Waste)</strong></div>
            </div>
          </div>
        </div>
      </div>

    <?php elseif ($activeTab === 'learning'): ?>
      <!-- ==================== VIEW 10: AGRONOMY KNOWLEDGE HUB ==================== -->
      <div class="space-y-6">
        <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-sm">
          <h2 class="text-xl font-extrabold text-slate-900">Smallholder Agronomy Knowledge Hub</h2>
          <p class="text-xs text-slate-500 mt-0.5">Practical post-harvest handling, storage, and market timing guides.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="vn-card">
            <h4 class="font-bold text-slate-900 text-sm">Tomato Post-Harvest Curing & Packaging</h4>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">How to pack Roma Sandak crates to prevent transit bruising and maintain Supermarket Grade A spec.</p>
            <button class="mt-3 text-xs font-bold text-emerald-700 hover:underline" onclick="showToast('Guide: Pack using dry wooden slats with ventilation.', 'info')">Read Guide →</button>
          </div>

          <div class="vn-card">
            <h4 class="font-bold text-slate-900 text-sm">Potato Curing in Rural Shade Sheds</h4>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Simple techniques to thicken skin and extend potato shelf-life by 6 weeks without cold storage.</p>
            <button class="mt-3 text-xs font-bold text-emerald-700 hover:underline" onclick="showToast('Guide: Maintain dark ambient ventilation.', 'info')">Read Guide →</button>
          </div>

          <div class="vn-card">
            <h4 class="font-bold text-slate-900 text-sm">2.5T Truck Pooling Protocol</h4>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">How multi-farmer aggregation schedules work at your local rural collection point.</p>
            <button class="mt-3 text-xs font-bold text-emerald-700 hover:underline" onclick="showToast('Guide: Tag crates with Lot ID before loading.', 'info')">Read Guide →</button>
          </div>

          <div class="vn-card">
            <h4 class="font-bold text-slate-900 text-sm">EcoCash Settlement Protocol</h4>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Understanding automatic escrow release when the transporter signs off produce handover.</p>
            <button class="mt-3 text-xs font-bold text-emerald-700 hover:underline" onclick="showToast('Guide: Escrow releases on digital signature sign-off.', 'info')">Read Guide →</button>
          </div>
        </div>
      </div>

    <?php elseif ($activeTab === 'settings'): ?>
      <!-- ==================== VIEW 11: SETTINGS & FARM PROFILE ==================== -->
      <div class="max-w-2xl mx-auto space-y-6">
        <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-sm flex justify-between items-center">
          <div>
            <h2 class="text-xl font-extrabold text-slate-900">Farm & Account Settings</h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage your farmer identity, payment phone number, and location.</p>
          </div>
          <a href="/logout.php" class="px-3.5 py-1.5 rounded-full bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs flex items-center gap-1">
            <span>Sign Out 🚪</span>
          </a>
        </div>

        <div class="vn-card">
          <form method="POST" action="/farmer.php?tab=settings" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
            <input type="hidden" name="action" value="update_profile" />

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Farmer Full Name</label>
              <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? 'Makomborero Gufe') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:border-emerald-500" required />
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">EcoCash Phone Number</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['email_or_phone'] ?? '0787146103') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-mono font-bold focus:outline-none focus:border-emerald-500" required />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">District Location</label>
                <input type="text" name="district" value="<?= htmlspecialchars($user['district'] ?? 'Bulawayo') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:border-emerald-500" required />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Primary Farm Produce</label>
              <input type="text" name="main_produce" value="Tomatoes, Table Potatoes, Onions, Leafy Greens" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:border-emerald-500" />
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
              <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md">
                Save Changes ✓
              </button>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>

    <!-- ==================== 5. BOTTOM ACTION GROWTH BANNER ==================== -->
    <div class="vn-growth-banner mt-6">
      <div class="flex items-center gap-5">
        <!-- Crisp Smartphone Mockup Graphic with Official Vunotho "V" Logo -->
        <div class="vn-growth-phone-mockup">
          <div class="w-full h-full bg-[#071726] rounded-xl flex flex-col items-center justify-center p-1 border border-emerald-500/40 shadow-inner">
            <img src="/images/vunotho_logo.png" alt="Official Vunotho Logo" class="w-12 h-12 rounded-xl object-cover shadow-md border border-emerald-400/40" />
            <span class="text-[9px] font-black tracking-wider text-emerald-300 mt-1">VUNOTHO</span>
          </div>
        </div>

        <div>
          <h2 class="vn-growth-title">Grow More. Earn More. Waste Less.</h2>
          <p class="vn-growth-desc">
            List your produce, connect with buyers, get the best prices and grow your impact with Vunotho.
          </p>
        </div>
      </div>

      <!-- Action Button -->
      <button class="vn-btn-list-produce" onclick="openProduceModal()">
        <span>+</span>
        <span>List New Produce</span>
      </button>
    </div>

    <!-- ==================== 6. BOTTOM FOOTER BAR ==================== -->
    <footer class="vn-footer-bar">
      <div>
        © 2025 Vunotho. All rights reserved.
      </div>
      <div class="flex items-center gap-1.5">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg>
        <span>Offline data will sync when you're back online.</span>
      </div>
    </footer>

  </main>
</div>

<!-- ==================== 7. INTERACTIVE PRODUCE MODAL ==================== -->
<div id="new-produce-modal" class="vunotho-modal-backdrop">
  <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-warm-xl max-w-lg w-full relative max-h-[92vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl overflow-hidden shadow-sm border border-emerald-500/40 flex-shrink-0 bg-[#071726] p-0.5">
          <img src="/images/vunotho_logo.png" alt="Vunotho Official Logo" class="w-full h-full object-cover rounded-lg" />
        </div>
        <div>
          <h3 class="text-lg font-black text-slate-900 leading-tight">List New Produce</h3>
          <p class="text-[11px] text-slate-500 font-medium">Instant transparent Net-Return calculation</p>
        </div>
      </div>
      <button class="text-slate-400 hover:text-slate-700 font-bold p-1" onclick="closeProduceModal()" aria-label="Close">✕</button>
    </div>

    <form method="POST" action="/farmer.php?tab=produce" class="space-y-4">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />
      <input type="hidden" name="action" value="create_listing" />
      <input type="hidden" name="district" value="<?= htmlspecialchars($user['district'] ?? 'Bulawayo') ?>" />

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Agricultural Commodity</label>
        <select id="modal-crop-select" name="crop" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none" required>
          <option value="Tomatoes" selected>Tomatoes (Roma / Round)</option>
          <option value="Onions">Onions (10kg Pocket)</option>
          <option value="Table Potatoes">Table Potatoes (15kg Mesh)</option>
          <option value="Leafy Greens">Leafy Greens (Covo / Rape)</option>
          <option value="Butternut Squash">Butternut Squash</option>
          <option value="Green Peppers">Green Peppers</option>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Harvest Volume (kg)</label>
          <input type="number" id="modal-qty-input" name="quantity_kg" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none" placeholder="e.g. 180" value="180" min="10" step="5" required />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Est. Unit Price</label>
          <div id="modal-unit-price-display" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-800 text-sm font-mono font-black">
            $0.42/kg
          </div>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Market Quality Tier</label>
        <select id="modal-grade-select" name="quality" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none" required>
          <option value="Tier 1: Fresh Market Supermarket Spec" selected>Tier 1: Fresh Market Supermarket Spec</option>
          <option value="Tier 2: Agro-Processing (Canners & Crisps)">Tier 2: Agro-Processing (Value Addition)</option>
          <option value="Tier 3: Livestock Feed">Tier 3: Livestock Feed</option>
          <option value="Tier 4: Bio-Compost Organic Biomass">Tier 4: Bio-Compost Organic Biomass</option>
        </select>
      </div>

      <!-- Live Net Return Breakdown Card -->
      <div class="p-4 rounded-2xl bg-gradient-to-br from-[#071726] to-[#0B2032] text-white border border-slate-800 space-y-2 mt-2">
        <div class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider">
          Live Net-Return Breakdown
        </div>
        <div class="flex justify-between text-xs text-slate-300">
          <span>Gross Value:</span>
          <span id="modal-calc-gross" class="font-mono font-bold text-white">$75.60</span>
        </div>
        <div class="flex justify-between text-xs text-slate-400">
          <span>Pooled Transport:</span>
          <span id="modal-calc-freight" class="font-mono text-orange-400">-$6.14</span>
        </div>
        <div class="flex justify-between text-xs text-slate-400">
          <span>Vunotho Fee (4%):</span>
          <span id="modal-calc-fee" class="font-mono text-amber-400">-$3.02</span>
        </div>
        <div class="pt-2 border-t border-slate-700 flex justify-between items-center">
          <span class="text-xs font-black text-white">Net Take-Home Pay:</span>
          <span id="modal-calc-net" class="text-base font-black text-emerald-400 font-mono">$66.44</span>
        </div>
      </div>

      <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-4">
        <button type="button" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-100" onclick="closeProduceModal()">
          Cancel
        </button>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-md transition-all">
          Register Produce Lot
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none [&>*]:pointer-events-auto"></div>

<!-- Scripts -->
<script src="/js/farmer_dashboard.js?v=4.0"></script>
<script>
  function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    const colors = type === 'success' ? 'bg-emerald-700 text-white' : (type === 'warning' ? 'bg-amber-600 text-white' : (type === 'error' ? 'bg-rose-600 text-white' : 'bg-slate-900 text-white'));
    toast.className = `toast-item px-4 py-3 rounded-xl shadow-lg text-xs font-bold flex items-center justify-between gap-3 ${colors}`;
    toast.innerHTML = `<span>${message}</span><button class="opacity-70 hover:opacity-100 font-bold" onclick="this.parentElement.remove()">✕</button>`;
    container.appendChild(toast);
    setTimeout(() => { if (toast.parentElement) { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s ease'; setTimeout(() => toast.remove(), 300); } }, 4000);
  }

  function sendChatMessage() {
    const input = document.getElementById('chat-input');
    const stream = document.getElementById('chat-stream');
    if (!input || !stream || !input.value.trim()) return;

    const val = input.value.trim();
    const bubble = document.createElement('div');
    bubble.className = 'flex items-start gap-2.5 justify-end';
    bubble.innerHTML = `<div class="bg-emerald-600 text-white p-3.5 rounded-2xl rounded-tr-none max-w-md text-xs leading-relaxed">${val}</div>`;
    stream.appendChild(bubble);
    input.value = '';
    stream.scrollTop = stream.scrollHeight;

    setTimeout(() => {
      const reply = document.createElement('div');
      reply.className = 'flex items-start gap-2.5';
      reply.innerHTML = `<div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs">🏢</div><div class="bg-slate-100 p-3.5 rounded-2xl rounded-tl-none max-w-md text-xs text-slate-800 leading-relaxed">Thank you Mako. Purchase order confirmed. Transporter will collect at scheduled time.</div>`;
      stream.appendChild(reply);
      stream.scrollTop = stream.scrollHeight;
    }, 1200);
  }
</script>

</body>
</html>
