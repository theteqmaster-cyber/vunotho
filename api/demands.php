<?php
/**
 * VUNOTHO BUYER DEMANDS REST API
 * GET: Fetch demand requests | POST: Create a new buyer demand
 */

require_once __DIR__ . '/db.php';

$pdo = get_db_connection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM demands ORDER BY created_at DESC");
        $demands = $stmt->fetchAll();
        send_json_response($demands);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} elseif ($method === 'POST') {
    try {
        $data = get_request_body();
        if (empty($data['crop']) || empty($data['target_quantity_kg'])) {
            send_json_response(['error' => true, 'message' => 'Missing crop or target_quantity_kg'], 400);
        }

        $id = $data['id'] ?? ('DEM-' . time() . '-' . strtoupper(substr(uniqid(), -4)));
        $buyer_id = $data['buyer_id'] ?? 'BUYER-01';
        $buyer_name = $data['buyer_name'] ?? 'Commercial Buyer';
        $crop = $data['crop'];
        $target_quantity_kg = floatval($data['target_quantity_kg']);
        $offered_price_per_kg = floatval($data['offered_price_per_kg'] ?? 0.95);
        $quality_required = $data['quality_required'] ?? 'Grade A (Premium)';
        $delivery_hub = $data['delivery_hub'] ?? 'Harare Central Market';
        $deadline = $data['deadline'] ?? date('c', strtotime('+7 days'));
        $status = $data['status'] ?? 'Active';
        $created_at = $data['created_at'] ?? date('c');

        // Portable upsert
        $checkStmt = $pdo->prepare("SELECT id FROM demands WHERE id = ?");
        $checkStmt->execute([$id]);
        if ($checkStmt->fetch()) {
            $stmt = $pdo->prepare("
                UPDATE demands SET buyer_id=?, buyer_name=?, crop=?, target_quantity_kg=?, offered_price_per_kg=?, quality_required=?, delivery_hub=?, deadline=?, status=? WHERE id=?
            ");
            $stmt->execute([$buyer_id, $buyer_name, $crop, $target_quantity_kg, $offered_price_per_kg, $quality_required, $delivery_hub, $deadline, $status, $id]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO demands (id, buyer_id, buyer_name, crop, target_quantity_kg, offered_price_per_kg, quality_required, delivery_hub, deadline, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id, $buyer_id, $buyer_name, $crop, $target_quantity_kg, $offered_price_per_kg, $quality_required, $delivery_hub, $deadline, $status, $created_at]);
        }

        send_json_response([
            'success' => true,
            'id' => $id,
            'message' => 'Buyer demand created successfully'
        ], 201);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} else {
    send_json_response(['error' => true, 'message' => 'Method not allowed'], 405);
}
