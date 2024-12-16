## Authentication Functions Documentation

This documentation covers the authentication functions implemented in a function-based PHP framework. These functions handle user registration, login, verification, and related operations.

### signup

Registers a new user in the system.

```php
function signup($pdo, $user_data, $verification)
```

**Parameters:**
- `$pdo`: PDO connection object
- `$user_data`: Associative array containing user information (must include 'email' and 'password')
- `$verification` : Send verification mail

**Usage:**
```php
$result = signup($pdo, [
    'email' => 'user@example.com',
    'password' => 'securepassword',
    'name' => 'John Doe'
]);
if ($result['status']) {
    echo $result['message'];
} else {
    echo "Signup failed: " . $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if email/password is missing, email already exists, or signup fails.

### signin

Authenticates a user and creates a JWT token.

```php
function signin($pdo, $credentials, $additional_checks = [])
```

**Parameters:**
- `$pdo`: PDO connection object
- `$credentials`: Associative array containing 'email' and 'password'
- `$additional_checks`: (Optional) Additional conditions to check before login

**Usage:**
```php
$result = signin($pdo, [
    'email' => 'user@example.com',
    'password' => 'securepassword'
]);
if ($result['status']) {
    echo "Login successful";
} else {
    echo "Login failed: " . $result['message'];
}
```

**Errors:**
- Returns a response with status `false` for invalid credentials, unverified email, or failed additional checks.

### verify

Verifies a user's email using a verification token.

```php
function verify($pdo, $token)
```

**Parameters:**
- `$pdo`: PDO connection object
- `$token`: Verification token sent to the user's email

**Usage:**
```php
$result = verify($pdo, 'verification_token_here');
if ($result['status']) {
    echo $result['message'];
} else {
    echo "Verification failed: " . $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the token is invalid or verification fails.

### user_logged_in

Checks if a user is logged in and optionally verifies their role.

```php
function user_logged_in($required_role = null)
```

**Parameters:**
- `$required_role`: (Optional) String or array of strings representing required role(s)

**Usage:**
```php
$user = user_logged_in('admin');
if ($user) {
    echo "Welcome, admin!";
} else {
    echo "Access denied or not logged in.";
}
```

**Errors:**
- Returns `false` if the user is not logged in or doesn't have the required role.

### logout

Logs out the current user by deleting the authentication cookie.

```php
function logout()
```

**Usage:**
```php
$result = logout();
if ($result['status']) {
    echo $result['message'];
}
```

### reset_password_request

Initiates a password reset process for a user.

```php
function reset_password_request($pdo, $email)
```

**Parameters:**
- `$pdo`: PDO connection object
- `$email`: Email address of the user requesting a password reset

**Usage:**
```php
$result = reset_password_request($pdo, 'user@example.com');
if ($result['status']) {
    echo $result['message'];
} else {
    echo "Reset request failed: " . $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the email is not found or if sending the reset email fails.

### update_user

Updates user information in the database.

```php
function update_user($pdo, $userid, $data, $allowed_fields = ['email', 'role', 'verified'])
```

**Parameters:**
- `$pdo`: PDO connection object
- `$userid`: User ID of the user to update
- `$data`: Associative array of data to update
- `$allowed_fields`: (Optional) Array of fields that are allowed to be updated

**Usage:**
```php
$result = update_user($pdo, 'user123', ['email' => 'newemail@example.com']);
if ($result['status']) {
    echo $result['message'];
} else {
    echo "Update failed: " . $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if there are no valid fields to update or if the update fails.

### update_password

Updates a user's password.

```php
function update_password($pdo, $userid, $current_password, $new_password)
```

**Parameters:**
- `$pdo`: PDO connection object
- `$userid`: User ID of the user
- `$current_password`: Current password for verification
- `$new_password`: New password to set

**Usage:**
```php
$result = update_password($pdo, 'user123', 'oldpassword', 'newpassword');
if ($result['status']) {
    echo $result['message'];
} else {
    echo "Password update failed: " . $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the user is not found, current password is incorrect, or update fails.

### update_failed_attempts

Increments the failed login attempts for a user.

```php
function update_failed_attempts($pdo, $userid)
```

**Parameters:**
- `$pdo`: PDO connection object
- `$userid`: User ID of the user

**Usage:**
```php
$result = update_failed_attempts($pdo, 'user123');
if (!$result) {
    echo "Failed to update login attempts";
}
```

### reset_failed_attempts

Resets the failed login attempts for a user.

```php
function reset_failed_attempts($pdo, $userid)
```

**Parameters:**
- `$pdo`: PDO connection object
- `$userid`: User ID of the user

**Usage:**
```php
$result = reset_failed_attempts($pdo, 'user123');
if ($result['status']) {
    echo "Failed attempts reset successfully";
}
```

These functions provide a comprehensive set of tools for managing user authentication in a PHP application. They handle common tasks such as user registration, login, email verification, password resets, and account management. When implementing these functions, ensure that you have the necessary database structure and additional helper functions (like `send_verification_mail`, `create_jwt`, etc.) in place.