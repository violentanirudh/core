<?php

function init_flash() {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
}

function set_flash($message, $type = 'info') {
    init_flash();
    $_SESSION['flash'][] = [
        'message' => $message,
        'type' => $type
    ];
}

function get_flash() {
    init_flash();
    $messages = $_SESSION['flash'];
    $_SESSION['flash'] = [];
    return $messages;
}

function show_flash() {
    $messages = get_flash();
    foreach ($messages as $flash) {
        $style = match($flash['type']) {
            'success' => 'bg-green-50 text-green-800 ring-green-600/20',
            'error'   => 'bg-red-50 text-red-800 ring-red-600/20',
            'warning' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
            default   => 'bg-blue-50 text-blue-800 ring-blue-600/20'
        };
        
        echo "<div class='rounded-md p-4 mb-4 ring-1 ring-inset {$style}'>";
        echo "<p class='text-sm leading-6 capitalize'>{$flash['message']}</p>";
        echo "</div>";
    }
}