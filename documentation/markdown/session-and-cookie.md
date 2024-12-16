## Session and Cookie Management Documentation

This documentation covers functions for secure session and cookie management in PHP applications.

### init_session

Initializes a secure PHP session with strict security settings.

```php
function init_session()
```

**Security Settings:**
- Strict mode enabled
- HTTP-only cookies
- Secure cookies in production
- SameSite=Strict policy

**Usage:**
```php
init_session();
// Session is now initialized with secure settings
```

## Session Functions

### set_session

Sets a value in the session.

```php
function set_session($key, $value)
```

**Parameters:**
- `$key`: String key for the session variable
- `$value`: Value to store (any type)

**Returns:**
- `true` on success
- `false` if key is not a string

**Usage:**
```php
set_session('user_id', 123);
set_session('preferences', ['theme' => 'dark']);
```

### get_session

Retrieves a value from the session.

```php
function get_session($key, $default = null)
```

**Parameters:**
- `$key`: String key to retrieve
- `$default`: Value to return if key doesn't exist

**Usage:**
```php
$user_id = get_session('user_id');
$theme = get_session('preferences', ['theme' => 'light'])['theme'];
```

### delete_session

Removes a specific key from the session.

```php
function delete_session($key)
```

**Parameters:**
- `$key`: String key to delete

**Returns:**
- `true` if key was deleted
- `false` if key didn't exist

**Usage:**
```php
delete_session('temporary_data');
```

### clear_session

Completely destroys the current session.

```php
function clear_session()
```

**Usage:**
```php
clear_session();
// All session data is now cleared
```

## Cookie Functions

### set_cookie

Sets a cookie with secure defaults.

```php
function set_cookie($key, $value, $days = 7)
```

**Parameters:**
- `$key`: String key for the cookie
- `$value`: Value to store
- `$days`: Number of days until expiration (1-30, default: 7)

**Security Features:**
- Secure flag in production
- HTTP-only in production
- SameSite=Strict
- Limited expiration range

**Usage:**
```php
set_cookie('user_preference', 'dark_mode');
set_cookie('remember_me', 'token123', 14);
```

### set_cookie_minutes

Sets a cookie with expiration in minutes.

```php
function set_cookie_minutes($key, $value, $minutes = 60)
```

**Parameters:**
- `$key`: String key for the cookie
- `$value`: Value to store
- `$minutes`: Minutes until expiration (minimum 1)

**Usage:**
```php
set_cookie_minutes('temporary_token', 'abc123', 30);
```

### get_cookie

Retrieves a cookie value.

```php
function get_cookie($key, $default = null)
```

**Parameters:**
- `$key`: String key to retrieve
- `$default`: Value to return if cookie doesn't exist

**Usage:**
```php
$theme = get_cookie('user_preference', 'light');
```

### delete_cookie

Removes a cookie.

```php
function delete_cookie($key)
```

**Parameters:**
- `$key`: String key of cookie to delete

**Returns:**
- `true` if cookie was deleted
- `false` if key is invalid or cookie didn't exist

**Usage:**
```php
delete_cookie('expired_token');
```

### has_state

Checks if a key exists in either session or cookies.

```php
function has_state($key)
```

**Parameters:**
- `$key`: String key to check

**Returns:**
- `true` if key exists in session or cookies
- `false` otherwise

**Usage:**
```php
if (has_state('user_id')) {
    // Process user data
}
```

## Security Considerations

**Session Security:**
- Strict mode prevents session fixation
- HTTP-only prevents XSS access to session cookie
- Secure flag ensures HTTPS-only transmission
- SameSite prevents CSRF attacks

**Cookie Security:**
```php
$cookie_options = [
    'expires' => time() + ($days * 86400),
    'path' => '/',
    'domain' => '',
    'secure' => PRODUCTION,
    'httponly' => PRODUCTION,
    'samesite' => 'Strict'
];
```

**Error Handling:**
```php
if (!set_session('key', $value)) {
    // Handle invalid key error
}

if (!set_cookie('key', $value)) {
    // Handle cookie setting failure
}
```

**Requirements:**
- PRODUCTION constant defined
- Session support enabled in PHP
- Proper error handling implementation