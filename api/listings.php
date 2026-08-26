<?php
/**
 * VUNOTHO PRODUCE LISTINGS REST API
 * GET: Fetch listings | POST: Create a new produce listing
 */

require_once __DIR__ . '/db.php';

$pdo = get_db_connection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM listings ORDER BY created_at DESC");
        $listings = $stmt->fetchAll();
        send_json_response($listings);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} elseif ($method === 'POST') {
    try {
        $data = get_request_body();
        if (empty($data['crop']) || empty($data['quantity_kg'])) {
            send_json_response(['error' => true, 'message' => 'Missing crop or quantity_kg'], 400);
        }

        $id = $data['id'] ?? ('LIST-' . time() . '-' . strtoupper(substr(uniqid(), -4)));
        $farmer_id = $data['farmer_id'] ?? 'FARMER-01';
        $farmer_name = $data['farmer_name'] ?? 'Smallholder Farmer';
        $crop = $data['crop'];
        $quantity_kg = floatval($data['quantity_kg']);
        $quality = $data['quality'] ?? 'Grade A (Premium)';
        $lat = floatval($data['lat'] ?? -18.2167);
        $lng = floatval($data['lng'] ?? 32.7500);
        $district = $data['district'] ?? 'Nyanga';
        $sync_status = 'Synced';
        $status = $data['status'] ?? 'Open';
        $created_at = $data['created_at'] ?? date('c');

        // Portable upsert
        $checkStmt = $pdo->prepare("SELECT id FROM listings WHERE id = ?");
        $checkStmt->execute([$id]);
        if ($checkStmt->fetch()) {
            $stmt = $pdo->prepare("
                UPDATE listings SET farmer_id=?, farmer_name=?, crop=?, quantity_kg=?, quality=?, lat=?, lng=?, district=?, sync_status=?, status=? WHERE id=?
            ");
            $stmt->execute([$farmer_id, $farmer_name, $crop, $quantity_kg, $quality, $lat, $lng, $district, $sync_status, $status, $id]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO listings (id, farmer_id, farmer_name, crop, quantity_kg, quality, lat, lng, district, sync_status, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id, $farmer_id, $farmer_name, $crop, $quantity_kg, $quality, $lat, $lng, $district, $sync_status, $status, $created_at]);
        }

        send_json_response([
            'success' => true,
            'id' => $id,
            'message' => 'Produce listing created successfully'
        ], 201);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} else {
    send_json_response(['error' => true, 'message' => 'Method not allowed'], 405);
}
