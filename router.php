<?php
/**
 * Local Development Router for PHP Built-in Server
 * Emulates Vercel route rewrites locally
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$decodedUri = urldecode($uri);

// Serve static assets directly if they exist
if ($uri !== '/' && (file_exists(__DIR__ . $uri) || file_exists(__DIR__ . $decodedUri))) {
    return false;
}

// Route /admin to index.html
if ($uri === '/admin' || $uri === '/admin/') {
    require __DIR__ . '/index.html';
    exit;
}

// Route /api/something to api/something.php
if (preg_match('#^/api/([a-zA-Z0-9_-]+)$#', $uri, $matches)) {
    $script = __DIR__ . '/api/' . $matches[1] . '.php';
    if (file_exists($script)) {
        require $script;
        exit;
    }
}

// Route /api/something.php directly
if (preg_match('#^/api/([a-zA-Z0-9_-]+\.php)$#', $uri, $matches)) {
    $script = __DIR__ . '/api/' . $matches[1];
    if (file_exists($script)) {
        require $script;
        exit;
    }
}

// Default root navigation serves index.html
require __DIR__ . '/index.html';
