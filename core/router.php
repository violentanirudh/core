<?php

function router($routes) {
    // Use global to access variables from index.php directly
    global $db, $request; 

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $request = [
        'query' => $_GET,
        'form' => $_POST,
        'files' => $_FILES,
        'method' => $_SERVER['REQUEST_METHOD'],
        'params' => [],
        'uri' => $uri
    ];

    foreach ($routes as $route => $path) {
        $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            // Extract named parameters
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $request['params'][$key] = $value;
                }
            }

            // Check if route is API
            if (strpos($route, '/api/') === 0) {
                header('Content-Type: application/json');
                include "api/$path.php";
                return;
            }

            // Handle view routes
            include "views/$path.php";
            return;
        }
    }

    // 404 handling
    include 'views/404.php';
}
