<?php

function base64url_encode($data) {
    return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
}

function base64url_decode($data) {
    $pad = strlen($data) % 4;
    if ($pad) {
        $data .= str_repeat('=', 4 - $pad);
    }
    return base64_decode(strtr($data, '-_', '+/'));
}

function create_jwt($payload, $secret_key) {
    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];
    
    $encoded_header = base64url_encode(json_encode($header));
    $encoded_payload = base64url_encode(json_encode($payload));
    
    $signature_input = "$encoded_header.$encoded_payload";
    $signature = base64url_encode(
        hash_hmac('SHA256', $signature_input, $secret_key, true)
    );
    
    return "$encoded_header.$encoded_payload.$signature";
}

function verify_jwt($jwt, $secret_key) {
    $parts = explode('.', $jwt);
    
    if (count($parts) !== 3) {
        return false;
    }
    
    list($header_b64, $payload_b64, $signature_b64) = $parts;
    
    $signature = base64url_decode($signature_b64);
    $verify_signature = hash_hmac(
        'SHA256', 
        "$header_b64.$payload_b64", 
        $secret_key, 
        true
    );
    
    if (!hash_equals($signature, $verify_signature)) {
        return false;
    }
    
    return json_decode(base64url_decode($payload_b64), true);
}
