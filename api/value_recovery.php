<?php
/**
 * VUNOTHO CIRCULAR VALUE-RECOVERY REST API
 * GET: Fetch waste diversion records | POST: Record a new value recovery event
 */

require_once __DIR__ . '/db.php';

$pdo = get_db_connection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM value_recovery ORDER BY timestamp DESC");
        $records = $stmt->fetchAll();
        send_json_response($records);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} elseif ($method === 'POST') {
    try {
        $data = get_request_body();
        if (empty($data['kg_diverted']) || empty($data['pathway'])) {
            send_json_response(['error' => true, 'message' => 'Missing kg_diverted or pathway'], 400);
        }

        $id = $data['id'] ?? ('VR-' . time() . '-' . strtoupper(substr(uniqid(), -4)));
        $listing_id = $data['listing_id'] ?? 'direct';
        $crop = $data['crop'] ?? 'Potatoes';
        $farmer_id = $data['farmer_id'] ?? 'unassigned';
        $farmer_name = $data['farmer_name'] ?? 'Smallholder Cohort';
        $pathway = $data['pathway'];
        $kg_diverted = floatval($data['kg_diverted']);
        $recovered_value_usd = floatval($data['recovered_value_usd'] ?? ($kg_diverted * 0.65));
        $facility = $data['facility'] ?? 'Vunotho Nyanga Processing Centre';
        $timestamp = $data['timestamp'] ?? date('c');

        $checkStmt = $pdo->prepare("SELECT id FROM value_recovery WHERE id = ?");
        $checkStmt->execute([$id]);
        if ($checkStmt->fetch()) {
            $stmt = $pdo->prepare("
                UPDATE value_recovery SET listing_id=?, crop=?, farmer_id=?, farmer_name=?, pathway=?, kg_diverted=?, recovered_value_usd=?, facility=?, timestamp=? WHERE id=?
            ");
            $stmt->execute([$listing_id, $crop, $farmer_id, $farmer_name, $pathway, $kg_diverted, $recovered_value_usd, $facility, $timestamp, $id]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO value_recovery (id, listing_id, crop, farmer_id, farmer_name, pathway, kg_diverted, recovered_value_usd, facility, timestamp)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id, $listing_id, $crop, $farmer_id, $farmer_name, $pathway, $kg_diverted, $recovered_value_usd, $facility, $timestamp]);
        }

        send_json_response([
            'success' => true,
            'id' => $id,
            'message' => 'Waste diversion logged successfully'
        ], 201);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} else {
    send_json_response(['error' => true, 'message' => 'Method not allowed'], 405);
}
