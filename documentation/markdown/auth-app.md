# User Authentication System

This example demonstrates how to implement a user authentication system using the function-based PHP framework. We'll create a simple login, registration, and logout system with protected routes.

## File Structure

```
/your-app
  /api
    auth.php
  /views
    home.php
    login.php
    register.php
    dashboard.php
  /core
    (all core files)
  config.php
  index.php
  .htaccess
```

## Configuration (config.php)

```php
<?php

require_once 'core/import.php';

define('DB_HOST', 'localhost');
define('DB_NAME', 'auth_example');
define('DB_USER', 'root');
define('DB_PASS', '');

define('JWT_SECRET_KEY', 'your_secret_key_here');
define('PRODUCTION', false);

$db = connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
```

Based on the signup function provided, here's the SQL table structure required:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    userid VARCHAR(16) UNIQUE NOT NULL,
    verification_token VARCHAR(64),
    role VARCHAR(50) DEFAULT 'user',
    verified TINYINT(1) DEFAULT 0,
    failed_attempts INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

This table structure supports all the fields used in the signup function:
- `email` and `password` for basic authentication
- `userid` for public user identification
- `verification_token` for email verification
- `role` for user access levels
- `verified` to track email verification status
- `failed_attempts` for login security
- `created_at` for user creation timestamp

The UNIQUE constraints on `email` and `userid` ensure no duplicates can be created.

## Main Application File (index.php)

```php
<?php

require_once 'config.php';

init_session();
initErrorHandler();

$routes = [
    '/' => 'home',
    '/login' => 'login',
    '/register' => 'register',
    '/dashboard' => 'dashboard',
    '/api/auth/login' => 'auth',
    '/api/auth/register' => 'auth',
    '/api/auth/logout' => 'auth'
];

router($routes);
```

## API Endpoints (api/auth.php)

```php
<?php

switch ($request['method']) {
    case 'POST':
        if (strpos($request['uri'], '/api/auth/login') !== false) {
            $result = signin($db, $request['form']);
            echo json_encode($result);
        } elseif (strpos($request['uri'], '/api/auth/register') !== false) {
            $result = signup($db, $request['form']);
            echo json_encode($result);
        }
        break;

    case 'GET':
        if (strpos($request['uri'], '/api/auth/logout') !== false) {
            $result = logout();
            echo json_encode($result);
        }
        break;

    default:
        echo json_encode(response(false, 'Invalid method'));
}
```

## Views

### Home Page (views/home.php)

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Auth Example</title>
</head>
<body>
    <h1>Welcome to Auth Example</h1>
    <nav>
        <a href="/login">Login</a>
        <a href="/register">Register</a>
    </nav>
    <?php show_flash(); ?>
</body>
</html>
```

### Login Page (views/login.php)

```php
<?php
if (user_logged_in()) {
    redirect('/dashboard');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Auth Example</title>
</head>
<body>
    <h1>Login</h1>
    <?php show_flash(); ?>
    <form action="/api/auth/login" method="POST">
        <?= csrf_field() ?>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <a href="/register">Don't have an account? Register</a>
</body>
</html>
```

### Register Page (views/register.php)

```php
<?php
if (user_logged_in()) {
    redirect('/dashboard');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Auth Example</title>
</head>
<body>
    <h1>Register</h1>
    <?php show_flash(); ?>
    <form action="/api/auth/register" method="POST">
        <?= csrf_field() ?>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Register</button>
    </form>
    <a href="/login">Already have an account? Login</a>
</body>
</html>
```

### Dashboard Page (views/dashboard.php)

```php
<?php
$user = user_logged_in();
if (!$user) {
    redirect('/login', 'Please login to access the dashboard', 'warning');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Auth Example</title>
</head>
<body>
    <h1>Welcome to your Dashboard, <?= $user['name'] ?></h1>
    <?php show_flash(); ?>
    <p>Your email: <?= $user['email'] ?></p>
    <a href="/api/auth/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
    </a>
    <form id="logout-form" action="/api/auth/logout" method="POST" style="display: none;">
        <?= csrf_field() ?>
    </form>
</body>
</html>
```

## Usage

1. Set up your database and create a `users` table as described in the config file comment.
2. Configure your web server to use the provided `.htaccess` file.
3. Update the database credentials in `config.php`.
4. Access the application through your web browser.

This example demonstrates:

- User registration with email and password
- User login with JWT token generation
- Protected dashboard route
- Logout functionality
- CSRF protection for forms
- Flash messages for user feedback
- Redirect for unauthorized access attempts

Remember to implement additional security measures such as:

- Email verification
- Password reset functionality
- Input validation and sanitization
- Rate limiting for login attempts
- Secure password hashing (already implemented in the framework's `signup` function)

This basic authentication system provides a foundation that you can expand upon for more complex applications requiring user accounts and protected routes.