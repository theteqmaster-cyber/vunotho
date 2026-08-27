<?php
/**
 * VUNOTHO SERVER ROUTER (PHP 8.3)
 * Routes static assets, API endpoints, and server-side protected PHP portals
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$decodedUri = urldecode($uri);

// 1. Serve static files directly if they exist on disk (css, js, images, fonts)
if ($uri !== '/' && (file_exists(__DIR__ . $uri) || file_exists(__DIR__ . $decodedUri))) {
    return false;
}

// 2. Route API endpoints (/api/<endpoint> or /api/<endpoint>.php)
if (preg_match('#^/api/([a-zA-Z0-9_-]+)(\.php)?$#', $uri, $matches)) {
    $script = __DIR__ . '/api/' . $matches[1] . '.php';
    if (file_exists($script)) {
        require $script;
        exit;
    }
}

// 3. Route Named PHP Pages (/farmer, /buyer, /transporter, /admin, /login, /logout)
$namedPages = [
    '/farmer' => '/farmer.php',
    '/buyer' => '/buyer.php',
    '/transporter' => '/transporter.php',
    '/admin' => '/admin.php',
    '/login' => '/login.php',
    '/logout' => '/logout.php',
    '/index' => '/index.php',
    '/about' => '/about.php',
    '/why-vunotho' => '/why-vunotho.php',
    '/privacy' => '/privacy.php',
    '/data-policy' => '/data-policy.php',
    '/contact' => '/contact.php',
    '/access' => '/access.php'
];

$cleanUri = rtrim($uri, '/');
if (isset($namedPages[$cleanUri])) {
    require __DIR__ . $namedPages[$cleanUri];
    exit;
}

if (preg_match('#^/([a-zA-Z0-9_-]+)\.php$#', $uri, $matches)) {
    $page = __DIR__ . '/' . $matches[1] . '.php';
    if (file_exists($page)) {
        require $page;
        exit;
    }
}

// 4. Default root path serves index.php
require __DIR__ . '/index.php';
