<?php
/**
 * VUNOTHO UNIVERSAL DATABASE & PDO CONNECTION ENGINE
 * Supports Remote PostgreSQL / MySQL (Neon, Supabase, PlanetScale) & SQLite Fallback
 */

require_once __DIR__ . '/security.php';

// Set Global JSON & CORS Headers for API requests
if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false) {
    header('Content-Type: application/json; charset=utf-8');
}
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'HEAD') {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}

function load_env_file() {
    $envPaths = [
        __DIR__ . '/../.env',
        __DIR__ . '/.env'
    ];

    foreach ($envPaths as $path) {
        if (file_exists($path) && is_readable($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || strpos($line, '#') === 0) continue;
                $parts = explode('=', $line, 2);
                if (count($parts) === 2) {
                    $key = trim($parts[0]);
                    $val = trim($parts[1]);
                    $val = trim($val, "\"'");
                    putenv("{$key}={$val}");
                    $_ENV[$key] = $val;
                }
            }
            break;
        }
    }
}

function get_db_connection() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    // Auto-load .env if present
    load_env_file();

    try {
        $database_url = getenv('DATABASE_URL');

        static $remoteFailed = false;

        if ($database_url && !$remoteFailed) {
            // Parse standard DATABASE_URL (postgresql://user:pass@host:port/dbname or mysql://...)
            $url = parse_url($database_url);
            $scheme = $url['scheme'] ?? 'pgsql';
            $host = $url['host'] ?? 'localhost';
            $port = $url['port'] ?? ($scheme === 'pgsql' || $scheme === 'postgres' || $scheme === 'postgresql' ? 5432 : 3306);
            $user = isset($url['user']) ? urldecode($url['user']) : '';
            $pass = isset($url['pass']) ? urldecode($url['pass']) : '';
            $dbname = ltrim($url['path'] ?? 'postgres', '/');

            if ($scheme === 'pgsql' || $scheme === 'postgres' || $scheme === 'postgresql') {
                $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require;connect_timeout=6";
            } else {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            }

            try {
                $pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT => 6
                ]);
            } catch (Exception $remoteEx) {
                // If remote host is unreachable on local network, fallback gracefully to SQLite
                $remoteFailed = true;
                error_log("Notice: Remote DB unreachable ({$remoteEx->getMessage()}), using fast SQLite fallback.");
                $sqlitePath = sys_get_temp_dir() . '/vunotho.db';
                $pdo = new PDO("sqlite:" . $sqlitePath, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            }
        } elseif (getenv('DB_HOST') && getenv('DB_NAME')) {
            // Explicit DB environment variables
            $driver = getenv('DB_DRIVER') ?: 'mysql';
            $host = getenv('DB_HOST');
            $port = getenv('DB_PORT') ?: ($driver === 'pgsql' ? 5432 : 3306);
            $dbname = getenv('DB_NAME');
            $user = getenv('DB_USER') ?: '';
            $pass = getenv('DB_PASS') ?: '';

            $dsn = "{$driver}:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } else {
            // SQLite Fallback (Local Development & Serverless Temp Cache)
            $sqlitePath = sys_get_temp_dir() . '/vunotho.db';
            $dsn = "sqlite:" . $sqlitePath;
            $pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }

        // Initialize Database Tables (cached to prevent repeat DDL latency)
        init_db_schema($pdo);

        return $pdo;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => true,
            'message' => 'Database connection failed: ' . $e->getMessage()
        ]);
        exit;
    }
}

function init_db_schema($pdo) {
    static $initialized = false;
    if ($initialized) return;

    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $cacheKeyFile = sys_get_temp_dir() . '/vunotho_schema_' . md5($driver . getenv('DATABASE_URL')) . '.lock';

    // If schema was already verified within the last 1 hour, skip expensive DDL checks
    if (file_exists($cacheKeyFile) && (time() - filemtime($cacheKeyFile) < 3600)) {
        $initialized = true;
        return;
    }

    // 1. Produce Listings Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS listings (
            id VARCHAR(64) PRIMARY KEY,
            farmer_id VARCHAR(64),
            farmer_name VARCHAR(128),
            crop VARCHAR(64),
            quantity_kg NUMERIC,
            quality VARCHAR(64),
            lat NUMERIC,
            lng NUMERIC,
            district VARCHAR(64),
            sync_status VARCHAR(32) DEFAULT 'Synced',
            status VARCHAR(32) DEFAULT 'Open',
            created_at VARCHAR(64)
        )
    ");

    // 2. Buyer Demands Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS demands (
            id VARCHAR(64) PRIMARY KEY,
            buyer_id VARCHAR(64),
            buyer_name VARCHAR(128),
            crop VARCHAR(64),
            target_quantity_kg NUMERIC,
            offered_price_per_kg NUMERIC,
            quality_required VARCHAR(64),
            delivery_hub VARCHAR(128),
            deadline VARCHAR(64),
            status VARCHAR(32) DEFAULT 'Active',
            created_at VARCHAR(64)
        )
    ");

    // 3. Transactions & Settlements Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS transactions (
            id VARCHAR(64) PRIMARY KEY,
            reference VARCHAR(64),
            payment_method VARCHAR(64),
            farmer_id VARCHAR(64),
            farmer_name VARCHAR(128),
            buyer_id VARCHAR(64),
            buyer_name VARCHAR(128),
            crop VARCHAR(64),
            quantity_kg NUMERIC,
            gross_total NUMERIC,
            transport_deduction NUMERIC,
            platform_fee NUMERIC,
            net_payout NUMERIC,
            status VARCHAR(32) DEFAULT 'Settled',
            created_at VARCHAR(64)
        )
    ");

    // 4. Circular Value-Recovery Diversion Logs Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS value_recovery (
            id VARCHAR(64) PRIMARY KEY,
            listing_id VARCHAR(64),
            crop VARCHAR(64),
            farmer_id VARCHAR(64),
            farmer_name VARCHAR(128),
            pathway VARCHAR(128),
            kg_diverted NUMERIC,
            recovered_value_usd NUMERIC,
            facility VARCHAR(128),
            timestamp VARCHAR(64)
        )
    ");

    // 5. Transporter Manifests Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS manifests (
            id VARCHAR(64) PRIMARY KEY,
            cluster_id VARCHAR(64),
            transporter_id VARCHAR(64),
            crop VARCHAR(64),
            district VARCHAR(64),
            total_weight_kg NUMERIC,
            stops_count INTEGER,
            est_payout NUMERIC,
            status VARCHAR(32) DEFAULT 'Pending Dispatch',
            created_at VARCHAR(64)
        )
    ");

    // 6. Registered Users Table (Supabase PostgreSQL / SQLite)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id VARCHAR(64) PRIMARY KEY,
            name VARCHAR(128),
            organisation VARCHAR(128),
            email_or_phone VARCHAR(128) UNIQUE,
            password_hash VARCHAR(255),
            role VARCHAR(32),
            province VARCHAR(64),
            district VARCHAR(64),
            main_produce VARCHAR(128),
            vehicle_type VARCHAR(64),
            kyc_status VARCHAR(32) DEFAULT 'Pending KYC',
            created_at VARCHAR(64)
        )
    ");

    // Safe column migrations for SQLite / PostgreSQL
    $optionalCols = [
        'organisation' => 'VARCHAR(128)',
        'province' => 'VARCHAR(64)',
        'main_produce' => 'VARCHAR(128)',
        'vehicle_type' => 'VARCHAR(64)'
    ];
    foreach ($optionalCols as $col => $type) {
        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN {$col} {$type}");
        } catch (Exception $ign) {
            // Column already exists
        }
    }

    // 7. System Configurations Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS system_configs (
            config_key VARCHAR(64) PRIMARY KEY,
            config_value TEXT,
            updated_at VARCHAR(64)
        )
    ");

    // Ensure Master Root Admin exists from environment variables
    $adminEmail = strtolower(getenv('ADMIN_EMAIL') ?: 'admin@vunotho@gmail.com');
    $adminPass = getenv('ADMIN_PASSWORD') ?: 'wish2026';
    
    $adminCheck = $pdo->prepare("SELECT id FROM users WHERE email_or_phone = ?");
    $adminCheck->execute([$adminEmail]);
    if (!$adminCheck->fetch()) {
        $adminHash = password_hash($adminPass, PASSWORD_DEFAULT);
        $adminInsert = $pdo->prepare("
            INSERT INTO users (id, name, email_or_phone, password_hash, role, district, kyc_status, created_at)
            VALUES ('USR-ROOT-ADMIN', 'System Administrator', ?, ?, 'admin', 'National Hub', 'Super Admin', ?)
        ");
        $adminInsert->execute([$adminEmail, $adminHash, date('c')]);
    }

    // Seed default commercial buyer demands if empty
    $demandCount = $pdo->query("SELECT COUNT(*) FROM demands")->fetchColumn();
    if ($demandCount == 0) {
        $seedDemands = [
            ['DEM-SEED-01', 'USR-BUYER-01', 'Bulawayo Fresh Wholesalers', 'Tomatoes', 1500, 0.55, 'Grade A (Premium)', 'Belmont Wholesale Hub (Bulawayo)', date('c', strtotime('+5 days')), 'Active', date('c')],
            ['DEM-SEED-02', 'USR-BUYER-02', 'Harare Fresh Produce Depot', 'Table Potatoes', 2500, 0.50, 'Grade A (Premium)', 'Mbare Musika Hub (Harare)', date('c', strtotime('+7 days')), 'Active', date('c')],
            ['DEM-SEED-03', 'USR-BUYER-03', 'Mutare Agro-Processing Ltd', 'Onions', 1200, 0.45, 'Grade B (Processing)', 'Mutare Industrial Site', date('c', strtotime('+10 days')), 'Active', date('c')],
            ['DEM-SEED-04', 'USR-BUYER-04', 'Masvingo Fresh Market Hub', 'Leafy Greens', 800, 0.48, 'Grade A (Premium)', 'Masvingo Central Hub', date('c', strtotime('+4 days')), 'Active', date('c')]
        ];
        $insertDemand = $pdo->prepare("
            INSERT INTO demands (id, buyer_id, buyer_name, crop, target_quantity_kg, offered_price_per_kg, quality_required, delivery_hub, deadline, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        foreach ($seedDemands as $sd) {
            $insertDemand->execute($sd);
        }
    }

    // Touch cache marker file
    @file_put_contents($cacheKeyFile, time());
    $initialized = true;
}

function send_json_response($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    exit;
}

function get_request_body() {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true) ?: [];
    return sanitize_payload($decoded);
}
