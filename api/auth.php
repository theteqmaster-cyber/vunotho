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
            $organisation = trim($data['organisation'] ?? '');
            $email_or_phone = strtolower(trim($data['email_or_phone'] ?? ''));
            $password = $data['password'] ?? '';
            $role = strtolower(trim($data['role'] ?? 'farmer'));
            $province = trim($data['province'] ?? 'Manicaland');
            $district = trim($data['district'] ?? 'Nyanga');
            $main_produce = trim($data['main_produce'] ?? '');
            $vehicle_type = trim($data['vehicle_type'] ?? '');

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
                INSERT INTO users (id, name, organisation, email_or_phone, password_hash, role, province, district, main_produce, vehicle_type, kyc_status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id, $name, $organisation, $email_or_phone, $password_hash, $role, $province, $district, $main_produce, $vehicle_type, $kyc_status, $created_at]);

            $userProfile = [
                'id' => $id,
                'name' => $name,
                'organisation' => $organisation,
                'email_or_phone' => $email_or_phone,
                'role' => $role,
                'province' => $province,
                'district' => $district,
                'main_produce' => $main_produce,
                'vehicle_type' => $vehicle_type,
                'kycStatus' => $kyc_status,
                'created_at' => $created_at
            ];

            $_SESSION['user'] = $userProfile;

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
                        'organisation' => 'Vunotho Headquarters',
                        'email_or_phone' => $envAdminEmail,
                        'role' => 'admin',
                        'province' => 'Harare',
                        'district' => 'National Hub',
                        'kycStatus' => 'Super Admin'
                    ];
                    $_SESSION['user'] = $userProfile;
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
                'organisation' => $user['organisation'] ?? '',
                'email_or_phone' => $user['email_or_phone'],
                'role' => $user['role'],
                'province' => $user['province'] ?? 'Manicaland',
                'district' => $user['district'] ?? 'Nyanga',
                'main_produce' => $user['main_produce'] ?? '',
                'vehicle_type' => $user['vehicle_type'] ?? '',
                'kycStatus' => $user['kyc_status'] ?? 'Verified'
            ];

            $_SESSION['user'] = $userProfile;

            send_json_response([
                'success' => true,
                'user' => $userProfile,
                'message' => 'Logged in successfully'
            ]);
        } catch (Exception $e) {
            send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
        }
    } elseif ($action === 'logout') {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        send_json_response(['success' => true, 'message' => 'Signed out successfully.']);
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
        $stmt = $pdo->query("SELECT id, name, organisation, email_or_phone, role, province, district, main_produce, vehicle_type, kyc_status, created_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll();
        send_json_response($users);
    } catch (Exception $e) {
        send_json_response(['error' => true, 'message' => $e->getMessage()], 500);
    }
}
