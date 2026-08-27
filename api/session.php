<?php
/**
 * VUNOTHO SERVER-SIDE SESSION & ROLE-BASED ACCESS CONTROL (RBAC)
 * Maximum Security PHP Session Management, CSRF Protection & Server-side Route Guards
 */

require_once __DIR__ . '/security.php';

// Initialize Secure Session
if (session_status() === PHP_SESSION_NONE) {
    $cookieParams = [
        'lifetime' => 86400 * 7, // 7 days
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ];
    session_set_cookie_params($cookieParams);
    session_start();
}

/**
 * Generate or retrieve CSRF token
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token in POST requests
 */
function validate_csrf_token($token) {
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Get active user session profile
 */
function get_current_user_profile() {
    return $_SESSION['user'] ?? null;
}

/**
 * Enforce that user must be authenticated
 */
function require_auth() {
    $user = get_current_user_profile();
    if (!$user) {
        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '/');
        header("Location: /login.php?redirect={$redirect}");
        exit;
    }
    return $user;
}

/**
 * Enforce strict role-based access control on the server
 */
function require_role($allowedRoles) {
    if (!is_array($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }
    
    $user = require_auth();
    
    if (!in_array(strtolower($user['role']), array_map('strtolower', $allowedRoles))) {
        http_response_code(403);
        render_403_forbidden_page($user, $allowedRoles);
        exit;
    }
    
    return $user;
}

/**
 * Server-rendered 403 Forbidden Access Gate (Zero Client Bypass)
 */
function render_403_forbidden_page($user, array $allowedRoles) {
    $userName = htmlspecialchars($user['name'] ?? 'User', ENT_QUOTES, 'UTF-8');
    $userRole = htmlspecialchars(ucfirst($user['role'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8');
    $requiredRole = htmlspecialchars(ucfirst(implode(' / ', $allowedRoles)), ENT_QUOTES, 'UTF-8');
    $userId = htmlspecialchars($user['id'] ?? 'USR-UNKNOWN', ENT_QUOTES, 'UTF-8');

    $roleLinks = [
        'farmer' => '/farmer.php',
        'buyer' => '/buyer.php',
        'transporter' => '/transporter.php',
        'admin' => '/admin.php'
    ];
    $myPortalLink = $roleLinks[strtolower($user['role'])] ?? '/';
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>403 Forbidden — Vunotho Security Gate</title>
      <link rel="icon" type="image/jpeg" href="/images/favicon.jpg" />
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
      <link rel="stylesheet" href="/css/tailwind.css" />
    </head>
    <body class="bg-[#F4F7F6] text-slate-900 min-h-screen flex flex-col items-center justify-center p-4">
      <div class="max-w-2xl w-full bg-white/90 backdrop-blur-md p-8 md:p-12 rounded-3xl border border-slate-200 shadow-warm-xl text-center border-l-8 border-l-rose-600">
        <img src="/images/vunotho_logo.jpg" alt="Vunotho Logo" class="w-16 h-16 rounded-2xl object-cover mx-auto mb-4 shadow-sm border border-slate-200" />

        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-800 text-xs font-extrabold border border-rose-200 mb-3 font-mono">
          HTTP 403 Forbidden • Server Security Gate
        </div>

        <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-3">
          Access Restricted by Server Policy
        </h1>

        <p class="text-sm text-slate-600 mb-6 leading-relaxed">
          You are authenticated as <strong class="text-slate-900"><?= $userName ?></strong> with active role <strong class="text-emerald-700"><?= $userRole ?></strong>.
          <br />
          This portal requires <strong class="text-rose-700"><?= $requiredRole ?></strong> privileges. Server-side security prevents cross-role access.
        </p>

        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 mb-8 max-w-md mx-auto text-left space-y-1">
          <div>• User ID: <code class="font-mono text-slate-900 font-bold"><?= $userId ?></code></div>
          <div>• Required Role: <strong class="text-rose-700 font-bold"><?= $requiredRole ?></strong></div>
          <div>• Enforcement: PHP Native Session & RBAC Gate</div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
          <a href="<?= $myPortalLink ?>" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all">
            Return to My <?= $userRole ?> Portal →
          </a>
          <a href="/logout.php" class="px-5 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs border border-slate-300 transition-all">
            Sign Out & Switch Account
          </a>
        </div>
      </div>
    </body>
    </html>
    <?php
}
