<?php

// CSRF Protection
function generate_csrf_token() {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function get_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        return generate_csrf_token();
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token) {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_field() {
    $token = get_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function process_form() {
    $submitted_token = $_POST['csrf_token'] ?? '';
    
    if (!validate_csrf_token($submitted_token)) {
        set_flash('error', 'Invalid security token. Please try again.');
        return false;
    }
    
    generate_csrf_token();
    return true;
}

// Security Headers
function set_security_headers($config = []) {
    $default_config = [
        'csp' => "default-src 'self'; img-src 'self' data:; script-src 'self'; style-src 'self';",
        'frame_options' => 'DENY',
        'referrer_policy' => 'strict-origin-when-cross-origin'
    ];

    $config = array_merge($default_config, $config);

    // Essential Security Headers
    header("X-XSS-Protection: 1; mode=block");
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: {$config['frame_options']}");
    header("Content-Security-Policy: {$config['csp']}");
    header("Referrer-Policy: {$config['referrer_policy']}");
}

// Initialize Security
function init_security($config = []) {
    set_security_headers($config);
    if (!isset($_SESSION['csrf_token'])) {
        generate_csrf_token();
    }
}
