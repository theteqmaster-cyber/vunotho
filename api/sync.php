<?php
/**
 * VUNOTHO BATCH OFFLINE SYNC INGESTION API
 * POST: Ingests an array of mutations created while offline and reconciles them into the database
 */

require_once __DIR__ . '/db.php';

$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_response(['error' => true, 'message' => 'Method not allowed'], 405);
}

try {
    $payload = get_request_body();
    $mutations = $payload['mutations'] ?? [];

    if (!is_array($mutations) || empty($mutations)) {
        send_json_response(['success' => true, 'synced_count' => 0, 'message' => 'No mutations provided']);
    }

    $syncedCount = 0;

    foreach ($mutations as $item) {
        $action = $item['action'] ?? '';
        $data = $item['payload'] ?? [];

        switch ($action) {
            case 'CREATE_LISTING':
                $id = $data['id'] ?? ('LIST-' . time() . '-' . rand(1000, 9999));
                $stmt = $pdo->prepare("
                    INSERT INTO listings (id, farmer_id, farmer_name, crop, quantity_kg, quality, lat, lng, district, sync_status, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Synced', ?, ?)
                ");
                $stmt->execute([
                    $id,
                    $data['farmer_id'] ?? 'FARMER-01',
                    $data['farmer_name'] ?? 'Farmer',
                    $data['crop'] ?? 'Potatoes',
                    floatval($data['quantity_kg'] ?? 0),
                    $data['quality'] ?? 'Grade A (Premium)',
                    floatval($data['lat'] ?? -18.2167),
                    floatval($data['lng'] ?? 32.7500),
                    $data['district'] ?? 'Nyanga',
                    $data['status'] ?? 'Open',
                    $data['created_at'] ?? date('c')
                ]);
                $syncedCount++;
                break;

            case 'CREATE_DEMAND':
                $id = $data['id'] ?? ('DEM-' . time() . '-' . rand(1000, 9999));
                $stmt = $pdo->prepare("
                    INSERT INTO demands (id, buyer_id, buyer_name, crop, target_quantity_kg, offered_price_per_kg, quality_required, delivery_hub, deadline, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', ?)
                ");
                $stmt->execute([
                    $id,
                    $data['buyer_id'] ?? 'BUYER-01',
                    $data['buyer_name'] ?? 'Buyer',
                    $data['crop'] ?? 'Potatoes',
                    floatval($data['target_quantity_kg'] ?? 0),
                    floatval($data['offered_price_per_kg'] ?? 0.95),
                    $data['quality_required'] ?? 'Grade A',
                    $data['delivery_hub'] ?? 'Harare',
                    $data['deadline'] ?? date('c'),
                    $data['created_at'] ?? date('c')
                ]);
                $syncedCount++;
                break;

            case 'SETTLE_TRANSACTION':
                $id = $data['id'] ?? ('TX-' . time() . '-' . rand(1000, 9999));
                $stmt = $pdo->prepare("
                    INSERT INTO transactions (id, reference, payment_method, farmer_id, farmer_name, buyer_id, buyer_name, crop, quantity_kg, gross_total, transport_deduction, platform_fee, net_payout, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $id,
                    $data['reference'] ?? ('REF-' . rand(100000, 999999)),
                    $data['payment_method'] ?? 'EcoCash',
                    $data['farmer_id'] ?? 'FARMER-01',
                    $data['farmer_name'] ?? 'Farmer',
                    $data['buyer_id'] ?? 'BUYER-01',
                    $data['buyer_name'] ?? 'Buyer',
                    $data['crop'] ?? 'Potatoes',
                    floatval($data['quantity_kg'] ?? 0),
                    floatval($data['gross_total'] ?? 0),
                    floatval($data['transport_deduction'] ?? 0),
                    floatval($data['platform_fee'] ?? 0),
                    floatval($data['net_payout'] ?? 0),
                    'Settled',
                    $data['created_at'] ?? date('c')
                ]);
                $syncedCount++;
                break;

            case 'LOG_VALUE_RECOVERY':
                $id = $data['id'] ?? ('VR-' . time() . '-' . rand(1000, 9999));
                $stmt = $pdo->prepare("
                    INSERT INTO value_recovery (id, listing_id, crop, farmer_id, farmer_name, pathway, kg_diverted, recovered_value_usd, facility, timestamp)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $id,
                    $data['listing_id'] ?? 'direct',
                    $data['crop'] ?? 'Potatoes',
                    $data['farmer_id'] ?? 'unassigned',
                    $data['farmer_name'] ?? 'Farmer',
                    $data['pathway'] ?? 'Processing',
                    floatval($data['kg_diverted'] ?? 0),
                    floatval($data['recovered_value_usd'] ?? 0),
                    $data['facility'] ?? 'Vunotho Hub',
                    $data['timestamp'] ?? date('c')
                ]);
                $syncedCount++;
                break;
        }
    }

    send_json_response([
        'success' => true,
        'synced_count' => $syncedCount,
        'message' => "Successfully synchronized {$syncedCount} mutation(s) to PHP database"
    ]);
} catch (Exception $e) {
    send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
}
