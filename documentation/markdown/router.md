## Router Documentation

A lightweight PHP router that handles both views and API endpoints with URL parameter support.

## Features

**Request Object**
The router provides a `$request` array with the following properties:
- `query`: GET parameters from URL query string
- `form`: POST data
- `files`: Uploaded files
- `method`: HTTP request method
- `params`: URL parameters extracted from route patterns
- `uri`: Current request URI

## Route Patterns

The router supports dynamic URL parameters using the `/:param` syntax:
```php
$routes = [
    '/' => 'home.php',
    '/user/:id' => 'user.php',
    '/api/posts/:slug' => 'posts.php'
];
```

## Usage Example

```php
// Define routes
$routes = [
    '/' => 'home.php',
    '/about' => 'about.php',
    '/api/users/:id' => 'users.php'
];

// Initialize router
router($routes);
```

## File Structure
```
project/
├── views/
│   ├── home.php
│   └── about.php
└── api/
    └── users.php
```

## Parameter Access

In your view or API files, access URL parameters:
```php
// For route /user/:id
echo $request['params']['id'];

// Query string parameters
echo $request['query']['sort'];

// POST data
echo $request['form']['username'];
```

The router automatically matches routes and includes the appropriate file, making the `$request` array available in the included file's scope.
