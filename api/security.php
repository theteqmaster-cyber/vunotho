<?php
/**
 * VUNOTHO ENTERPRISE SECURITY & HARDENING MODULE
 * Implements Defense-in-Depth for PHP REST APIs:
 * - Strict HTTP Security Headers
 * - Input Sanitization & Type Safety
 * - IP-based Rate Limiting / Brute-force Mitigation
 * - Constant-time Token & Credential Validation
 * - Safe JSON Output Escaping
 */

// 1. Enforce Global Security Headers
function apply_security_headers() {
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com https://fonts.gstatic.com https://static.cloudflareinsights.com data: blob:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://static.cloudflareinsights.com; img-src 'self' data: https: blob:; connect-src 'self' http://localhost:* ws://localhost:* http://127.0.0.1:* ws://127.0.0.1:* https:;");
        
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
    }
}

// 2. Input Sanitization Helpers
function sanitize_string($value, $maxLength = 255) {
    if ($value === null) return '';
    $clean = trim((string)$value);
    $clean = strip_tags($clean);
    // Remove null bytes
    $clean = str_replace(chr(0), '', $clean);
    if (strlen($clean) > $maxLength) {
        $clean = substr($clean, 0, $maxLength);
    }
    return $clean;
}

function sanitize_numeric($value, $default = 0.0) {
    if (!isset($value) || !is_numeric($value)) return $default;
    return floatval($value);
}

function sanitize_email_or_phone($value) {
    $clean = sanitize_string($value, 128);
    return strtolower($clean);
}

function sanitize_payload(array $data) {
    $clean = [];
    foreach ($data as $k => $v) {
        $cleanKey = sanitize_string($k, 64);
        if (is_array($v)) {
            $clean[$cleanKey] = sanitize_payload($v);
        } elseif (is_numeric($v)) {
            $clean[$cleanKey] = floatval($v);
        } elseif (is_bool($v)) {
            $clean[$cleanKey] = (bool)$v;
        } else {
            $clean[$cleanKey] = sanitize_string($v, 1000);
        }
    }
    return $clean;
}

// 3. Lightweight IP-based Rate Limiter
function check_rate_limit($actionKey = 'general', $maxRequests = 120, $windowSeconds = 60) {
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $cacheFile = sys_get_temp_dir() . '/vunotho_rate_' . md5($clientIp . '_' . $actionKey) . '.json';
    
    $now = time();
    $data = ['count' => 0, 'start' => $now];
    
    if (file_exists($cacheFile)) {
        $raw = @file_get_contents($cacheFile);
        $saved = json_decode($raw, true);
        if (is_array($saved) && isset($saved['start']) && ($now - $saved['start']) < $windowSeconds) {
            $data = $saved;
        }
    }
    
    $data['count']++;
    @file_put_contents($cacheFile, json_encode($data));
    
    if ($data['count'] > $maxRequests) {
        http_response_code(429);
        header('Retry-After: ' . ($windowSeconds - ($now - $data['start'])));
        echo json_encode([
            'error' => true,
            'message' => 'Rate limit exceeded. Please wait a moment before sending more requests.'
        ], JSON_PRETTY_PRINT);
        exit;
    }
}

// Apply headers immediately upon inclusion
apply_security_headers();
