<?php
/**
 * VUNOTHO TRANSACTIONS & SETTLEMENT REST API
 * GET: Fetch transaction ledger | POST: Record new verified settlement
 */

require_once __DIR__ . '/db.php';

$pdo = get_db_connection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC");
        $transactions = $stmt->fetchAll();
        send_json_response($transactions);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} elseif ($method === 'POST') {
    try {
        $data = get_request_body();
        if (empty($data['net_payout']) || empty($data['quantity_kg'])) {
            send_json_response(['error' => true, 'message' => 'Missing net_payout or quantity_kg'], 400);
        }

        $id = $data['id'] ?? ('TX-' . time() . '-' . strtoupper(substr(uniqid(), -4)));
        $reference = $data['reference'] ?? ('ECO-' . rand(100000, 999999));
        $payment_method = $data['payment_method'] ?? 'EcoCash Mobile Wallet';
        $farmer_id = $data['farmer_id'] ?? 'FARMER-01';
        $farmer_name = $data['farmer_name'] ?? 'Smallholder Farmer';
        $buyer_id = $data['buyer_id'] ?? 'BUYER-01';
        $buyer_name = $data['buyer_name'] ?? 'Commercial Buyer';
        $crop = $data['crop'] ?? 'Potatoes';
        $quantity_kg = floatval($data['quantity_kg']);
        $gross_total = floatval($data['gross_total'] ?? 0);
        $transport_deduction = floatval($data['transport_deduction'] ?? 0);
        $platform_fee = floatval($data['platform_fee'] ?? 0);
        $net_payout = floatval($data['net_payout']);
        $status = $data['status'] ?? 'Settled';
        $created_at = $data['created_at'] ?? date('c');

        $checkStmt = $pdo->prepare("SELECT id FROM transactions WHERE id = ?");
        $checkStmt->execute([$id]);
        if ($checkStmt->fetch()) {
            $stmt = $pdo->prepare("
                UPDATE transactions SET reference=?, payment_method=?, farmer_id=?, farmer_name=?, buyer_id=?, buyer_name=?, crop=?, quantity_kg=?, gross_total=?, transport_deduction=?, platform_fee=?, net_payout=?, status=? WHERE id=?
            ");
            $stmt->execute([$reference, $payment_method, $farmer_id, $farmer_name, $buyer_id, $buyer_name, $crop, $quantity_kg, $gross_total, $transport_deduction, $platform_fee, $net_payout, $status, $id]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO transactions (id, reference, payment_method, farmer_id, farmer_name, buyer_id, buyer_name, crop, quantity_kg, gross_total, transport_deduction, platform_fee, net_payout, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id, $reference, $payment_method, $farmer_id, $farmer_name, $buyer_id, $buyer_name, $crop, $quantity_kg, $gross_total, $transport_deduction, $platform_fee, $net_payout, $status, $created_at]);
        }

        send_json_response([
            'success' => true,
            'id' => $id,
            'reference' => $reference,
            'message' => 'Transaction settled and recorded successfully'
        ], 201);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} else {
    send_json_response(['error' => true, 'message' => 'Method not allowed'], 405);
}
