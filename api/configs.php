<?php
/**
 * VUNOTHO SYSTEM CONFIGURATIONS & MONITORING API
 * Allows Administrator to view, monitor, and update global economic parameters
 */

require_once __DIR__ . '/db.php';

$pdo = get_db_connection();
$method = $_SERVER['REQUEST_METHOD'];

$defaultConfigs = [
    'platform_fee_pct' => '4.0',
    'transport_per_km' => '0.05',
    'transport_per_kg' => '0.03',
    'grade_a_multiplier' => '1.0',
    'grade_b_floor_usd' => '0.55',
    'grade_c_floor_usd' => '0.25',
    'compost_floor_usd' => '0.10',
    'enactus_target_usd' => '15000.00',
    'auto_dispatch_threshold_kg' => '2000'
];

if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT config_key, config_value, updated_at FROM system_configs");
        $rows = $stmt->fetchAll();
        $configs = $defaultConfigs;

        foreach ($rows as $row) {
            $configs[$row['config_key']] = $row['config_value'];
        }

        send_json_response([
            'success' => true,
            'configs' => $configs,
            'server_time' => date('c'),
            'db_driver' => $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)
        ]);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
} elseif ($method === 'POST') {
    try {
        $data = get_request_body();
        $configs = $data['configs'] ?? $data;

        $stmt = $pdo->prepare("
            INSERT INTO system_configs (config_key, config_value, updated_at)
            VALUES (?, ?, ?)
            ON CONFLICT (config_key) DO UPDATE SET config_value = EXCLUDED.config_value, updated_at = EXCLUDED.updated_at
        ");

        // Handle MySQL / SQLite syntax fallback if needed
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'sqlite' || $driver === 'mysql') {
            $stmt = $pdo->prepare("
                INSERT OR REPLACE INTO system_configs (config_key, config_value, updated_at)
                VALUES (?, ?, ?)
            ");
        }

        foreach ($configs as $key => $val) {
            $stmt->execute([$key, (string)$val, date('c')]);
        }

        send_json_response([
            'success' => true,
            'message' => 'System configurations successfully updated and deployed.'
        ]);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
}
