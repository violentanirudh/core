<?php
// core/fetch.php

function fetch($url, $options = []) {
    // Default options
    $defaults = [
        'method' => 'GET',
        'headers' => [],
        'body' => null,
        'timeout' => 30
    ];
    
    // Merge options
    $options = array_merge($defaults, $options);
    
    // Initialize cURL
    $ch = curl_init();
    
    // Set URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);
    
    // Set method
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $options['method']);
    
    // Set headers
    if (!empty($options['headers'])) {
        $headers = [];
        foreach ($options['headers'] as $key => $value) {
            $headers[] = "$key: $value";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    // Set body
    if ($options['body']) {
        if (is_array($options['body'])) {
            $options['body'] = json_encode($options['body']);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $options['body']);
    }
    
    // Execute request
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    // Handle response
    return [
        'ok' => $status >= 200 && $status < 300,
        'status' => $status,
        'body' => $response,
        'error' => $error
    ];
}
