<?php

// Initialize session securely if not already started
function init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', PRODUCTION);
        ini_set('session.cookie_samesite', 'Strict');
        session_start();
    }
}

// Session Functions with type validation
function set_session($key, $value) {
    init_session();
    if (!is_string($key)) {
        return false;
    }
    $_SESSION[$key] = $value;
    return true;
}

function get_session($key, $default = null) {
    init_session();
    return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
}

function delete_session($key) {
    init_session();
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
        return true;
    }
    return false;
}

function clear_session() {
    init_session();
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        delete_cookie(session_name());
    }
    session_destroy();
}

// Cookie Functions with validation and encryption
function set_cookie($key, $value, $days = 7) {
    if (!is_string($key)) {
        return false;
    }
    
    $days = min(max(1, $days), 30); // Ensure days is between 1 and 30
    $expiry = time() + ($days * 86400);
    
    // Optional: Encrypt value for sensitive data
    // $value = encrypt($value);
    
    return setcookie(
        $key,
        $value,
        [
            'expires' => $expiry,
            'path' => '/',
            'domain' => '',
            'secure' => PRODUCTION,
            'httponly' => PRODUCTION,
            'samesite' => 'Strict'
        ]
    );
}

function set_cookie_minutes($key, $value, $minutes = 60) {
    if (!is_string($key)) {
        return false;
    }
    
    $minutes = max(1, $minutes); // Ensure minimum 1 minute
    $expiry = time() + ($minutes * 60);
    
    return setcookie(
        $key,
        $value,
        [
            'expires' => $expiry,
            'path' => '/',
            'domain' => '',
            'secure' => PRODUCTION,
            'httponly' => PRODUCTION,
            'samesite' => 'Strict'
        ]
    );
}

function get_cookie($key, $default = null) {
    if (!is_string($key)) {
        return $default;
    }
    // Optional: Decrypt value if encrypted
    // return isset($_COOKIE[$key]) ? decrypt($_COOKIE[$key]) : $default;
    return $_COOKIE[$key] ?? $default;
}

function delete_cookie($key) {
    if (!is_string($key) || !isset($_COOKIE[$key])) {
        return false;
    }
    
    return setcookie(
        $key,
        '',
        [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => PRODUCTION,
            'httponly' => PRODUCTION,
            'samesite' => 'Strict'
        ]
    );
}

function has_state($key) {
    if (!is_string($key)) {
        return false;
    }
    return isset($_SESSION[$key]) || isset($_COOKIE[$key]);
}
