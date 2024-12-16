## Security Functions Documentation

This documentation covers functions for implementing CSRF protection and setting security headers in PHP applications.

### CSRF Protection Functions

#### generate_csrf_token

Generates a new CSRF token and stores it in the session.

```php
function generate_csrf_token()
```

**Returns:**
- A 64-character hexadecimal string

**Usage:**
```php
$token = generate_csrf_token();
```

#### get_csrf_token

Retrieves the current CSRF token or generates a new one if it doesn't exist.

```php
function get_csrf_token()
```

**Returns:**
- The current CSRF token

**Usage:**
```php
$token = get_csrf_token();
```

#### validate_csrf_token

Validates a submitted CSRF token against the stored token.

```php
function validate_csrf_token($token)
```

**Parameters:**
- `$token`: The submitted token to validate

**Returns:**
- `true` if the token is valid
- `false` if the token is invalid or missing

**Usage:**
```php
if (validate_csrf_token($_POST['csrf_token'])) {
    // Process form
} else {
    // Handle invalid token
}
```

#### csrf_field

Generates an HTML input field containing the CSRF token.

```php
function csrf_field()
```

**Returns:**
- An HTML string with a hidden input field

**Usage:**
```php
echo csrf_field();
```

#### process_form

Validates the CSRF token from a form submission and generates a new token.

```php
function process_form()
```

**Returns:**
- `true` if the token is valid
- `false` if the token is invalid

**Usage:**
```php
if (process_form()) {
    // Process form data
} else {
    // Handle invalid token
}
```

### Security Headers Functions

#### set_security_headers

Sets various security headers with configurable options.

```php
function set_security_headers($config = [])
```

**Parameters:**
- `$config`: An associative array of header configurations

**Default Configuration:**
```php
$default_config = [
    'csp' => "default-src 'self'; img-src 'self' data:; script-src 'self'; style-src 'self';",
    'frame_options' => 'DENY',
    'referrer_policy' => 'strict-origin-when-cross-origin'
];
```

**Usage:**
```php
set_security_headers([
    'csp' => "default-src 'self' https://trusted-cdn.com;",
    'frame_options' => 'SAMEORIGIN'
]);
```

#### init_security

Initializes security settings by setting headers and ensuring a CSRF token exists.

```php
function init_security($config = [])
```

**Parameters:**
- `$config`: An associative array of security configurations (same as `set_security_headers`)

**Usage:**
```php
init_security();
```

## Security Considerations

1. **CSRF Protection:**
   - Always validate CSRF tokens on state-changing requests (POST, PUT, DELETE).
   - Generate new tokens after successful form submissions.
   - Use `hash_equals` for timing-safe comparisons.

2. **Security Headers:**
   - Content Security Policy (CSP) helps prevent XSS and other injection attacks.
   - X-Frame-Options prevents clickjacking attacks.
   - X-XSS-Protection and X-Content-Type-Options provide additional browser protections.

3. **Session Security:**
   - Ensure sessions are initialized securely before using CSRF functions.
   - Regenerate session IDs after login to prevent session fixation.

## Error Handling

- CSRF validation failures should be logged and result in request termination.
- Invalid configurations in `set_security_headers` will use default values.

## Best Practices

1. Call `init_security()` at the beginning of each request.
2. Include `csrf_field()` in all forms that modify state.
3. Use `process_form()` to validate submissions and regenerate tokens.
4. Customize CSP and other headers based on your application's needs.

## Requirements

- Session support enabled in PHP
- Output buffering should be disabled or flushed before setting headers