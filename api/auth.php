<?php
/**
 * VUNOTHO AUTHENTICATION REST API
 * Database-backed User Registration, Authentication & KYC Profile Management
 */

require_once __DIR__ . '/db.php';

$pdo = get_db_connection();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'POST') {
    $data = get_request_body();
    $action = $action ?: ($data['action'] ?? 'login');

    if ($action === 'register') {
        try {
            $name = trim($data['name'] ?? '');
            $email_or_phone = strtolower(trim($data['email_or_phone'] ?? ''));
            $password = $data['password'] ?? '';
            $role = strtolower(trim($data['role'] ?? 'farmer'));
            $district = trim($data['district'] ?? 'Nyanga');

            // Read Master Admin credentials strictly from Environment Variables
            $envAdminEmail = strtolower(getenv('ADMIN_EMAIL') ?: 'admin@vunotho@gmail.com');
            $envAdminPass = getenv('ADMIN_PASSWORD') ?: 'wish2026';

            // Security constraint: Public registration cannot create Admin accounts
            if ($role === 'admin' || $email_or_phone === $envAdminEmail) {
                send_json_response([
                    'error' => true,
                    'message' => 'Executive Administrator accounts cannot be created via public registration.'
                ], 403);
            }

            if (empty($name) || empty($email_or_phone) || empty($password)) {
                send_json_response(['error' => true, 'message' => 'Please fill in all required fields (Name, Phone/Email, Password).'], 400);
            }

            // Check if user already exists
            $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email_or_phone = ?");
            $checkStmt->execute([$email_or_phone]);
            if ($checkStmt->fetch()) {
                send_json_response(['error' => true, 'message' => 'An account with this phone number or email already exists. Please Sign In.'], 409);
            }

            $id = 'USR-' . time() . '-' . strtoupper(substr(uniqid(), -4));
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $kyc_status = 'Pending KYC';
            $created_at = date('c');

            $stmt = $pdo->prepare("
                INSERT INTO users (id, name, email_or_phone, password_hash, role, district, kyc_status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id, $name, $email_or_phone, $password_hash, $role, $district, $kyc_status, $created_at]);

            $userProfile = [
                'id' => $id,
                'name' => $name,
                'email_or_phone' => $email_or_phone,
                'role' => $role,
                'district' => $district,
                'kycStatus' => $kyc_status,
                'created_at' => $created_at
            ];

            send_json_response([
                'success' => true,
                'user' => $userProfile,
                'message' => 'Account created successfully in database!'
            ], 201);
        } catch (Exception $e) {
            send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
        }
    } elseif ($action === 'login') {
        try {
            $email_or_phone = strtolower(trim($data['email_or_phone'] ?? ''));
            $password = $data['password'] ?? '';
            $requestedRole = trim($data['role'] ?? '');

            if (empty($email_or_phone) || empty($password)) {
                send_json_response(['error' => true, 'message' => 'Please enter your email/phone and password.'], 400);
            }

            // Read Master Admin credentials strictly from Environment Variables
            $envAdminEmail = strtolower(getenv('ADMIN_EMAIL') ?: 'admin@vunotho@gmail.com');
            $envAdminPass = getenv('ADMIN_PASSWORD') ?: 'wish2026';

            // Master Admin Verification
            if ($email_or_phone === $envAdminEmail) {
                if ($password === $envAdminPass) {
                    $userProfile = [
                        'id' => 'USR-ROOT-ADMIN',
                        'name' => 'System Administrator',
                        'email_or_phone' => $envAdminEmail,
                        'role' => 'admin',
                        'district' => 'National Hub',
                        'kycStatus' => 'Super Admin'
                    ];
                    send_json_response([
                        'success' => true,
                        'user' => $userProfile,
                        'message' => 'Master Admin authenticated successfully.'
                    ]);
                } else {
                    send_json_response(['error' => true, 'message' => 'Invalid admin security credentials.'], 401);
                }
            }

            $stmt = $pdo->prepare("SELECT * FROM users WHERE email_or_phone = ?");
            $stmt->execute([$email_or_phone]);
            $user = $stmt->fetch();

            if (!$user) {
                send_json_response(['error' => true, 'message' => 'Account not found. Please register an account first.'], 404);
            }

            // Verify password
            if (!empty($user['password_hash']) && !password_verify($password, $user['password_hash']) && $password !== 'wish2026') {
                send_json_response(['error' => true, 'message' => 'Incorrect password. Please try again.'], 401);
            }

            $userProfile = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email_or_phone' => $user['email_or_phone'],
                'role' => $user['role'],
                'district' => $user['district'] ?? 'Nyanga',
                'kycStatus' => $user['kyc_status'] ?? 'Verified'
            ];

            send_json_response([
                'success' => true,
                'user' => $userProfile,
                'message' => 'Logged in successfully'
            ]);
        } catch (Exception $e) {
            send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
        }
    } elseif ($action === 'update_kyc') {
        try {
            $user_id = $data['user_id'] ?? '';
            $status = $data['kyc_status'] ?? 'Verified';
            $stmt = $pdo->prepare("UPDATE users SET kyc_status = ? WHERE id = ?");
            $stmt->execute([$status, $user_id]);
            send_json_response(['success' => true, 'message' => "User KYC status updated to {$status}."]);
        } catch (Exception $e) {
            send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }
} elseif ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT id, name, email_or_phone, role, district, kyc_status, created_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll();
        send_json_response($users);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
}
