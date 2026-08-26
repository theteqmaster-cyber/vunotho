<?php
/**
 * VUNOTHO TRANSPORTER MANIFESTS REST API
 * GET: Fetch aggregated manifests | POST: Update or create route manifest
 */

require_once __DIR__ . '/db.php';

$pdo = get_db_connection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM manifests ORDER BY created_at DESC");
        $manifests = $stmt->fetchAll();
        send_json_response($manifests);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} elseif ($method === 'POST') {
    try {
        $data = get_request_body();
        $id = $data['id'] ?? ('MAN-' . time() . '-' . strtoupper(substr(uniqid(), -4)));
        $cluster_id = $data['cluster_id'] ?? 'CLUST-01';
        $transporter_id = $data['transporter_id'] ?? 'TRANSPORTER-01';
        $crop = $data['crop'] ?? 'Potatoes';
        $district = $data['district'] ?? 'Nyanga';
        $total_weight_kg = floatval($data['total_weight_kg'] ?? 0);
        $stops_count = intval($data['stops_count'] ?? 1);
        $est_payout = floatval($data['est_payout'] ?? 0);
        $status = $data['status'] ?? 'Pending Dispatch';
        $created_at = $data['created_at'] ?? date('c');

        $checkStmt = $pdo->prepare("SELECT id FROM manifests WHERE id = ?");
        $checkStmt->execute([$id]);
        if ($checkStmt->fetch()) {
            $stmt = $pdo->prepare("
                UPDATE manifests SET cluster_id=?, transporter_id=?, crop=?, district=?, total_weight_kg=?, stops_count=?, est_payout=?, status=? WHERE id=?
            ");
            $stmt->execute([$cluster_id, $transporter_id, $crop, $district, $total_weight_kg, $stops_count, $est_payout, $status, $id]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO manifests (id, cluster_id, transporter_id, crop, district, total_weight_kg, stops_count, est_payout, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id, $cluster_id, $transporter_id, $crop, $district, $total_weight_kg, $stops_count, $est_payout, $status, $created_at]);
        }

        send_json_response([
            'success' => true,
            'id' => $id,
            'message' => 'Manifest saved successfully'
        ], 201);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} else {
    send_json_response(['error' => true, 'message' => 'Method not allowed'], 405);
}
