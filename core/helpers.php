<?php

function response($status, $message, $data = []) {
    return ['status' => $status, 'message' => $message, 'data' => $data];
}

function generate_random_string($length = 32, $type = 'hex') {
    switch ($type) {
        case 'hex':
            return bin2hex(random_bytes($length / 2));
        case 'alphanumeric':
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $string = '';
            $max = strlen($characters) - 1;
            for ($i = 0; $i < $length; $i++) {
                $string .= $characters[random_int(0, $max)];
            }
            return $string;
        default:
            return bin2hex(random_bytes($length / 2));
    }
}

function redirect($path, $message = null, $type = 'error') {
    if ($message) {
        set_flash($message, $type);
    }
    header('location: /' . ltrim($path, '/'));
    exit;
}

function get_current_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . '://' . $host;
}