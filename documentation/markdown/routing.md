Based on the provided router function and the request for a simple index.php, here's a basic implementation:

```php
<?php

require_once 'config.php';

init_session();
initErrorHandler();

$routes = [
    '/' => 'home',
    '/about' => 'about',
    '/contact' => 'contact',
    '/api/data' => 'data'
];

router($routes);
```

This simple index.php file does the following:

1. Includes the configuration file.
2. Initializes the session and error handler.
3. Defines a basic route array similar to the one in the search results.
4. Calls the router function with the defined routes.

The router will handle the incoming requests based on the defined routes, including API routes and view routes. If no matching route is found, it will load the 404 view.