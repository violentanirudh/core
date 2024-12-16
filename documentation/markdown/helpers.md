## Utility Functions Documentation

This documentation covers essential utility functions for handling responses, generating random strings, managing redirects, and URL operations.

### response

Creates a standardized response array format.

```php
function response($status, $message, $data = [])
```

**Parameters:**
- `$status`: Boolean indicating success/failure
- `$message`: String message describing the result
- `$data`: (Optional) Additional data to return

**Returns:**
Array with structure:
```php
[
    'status' => true/false,
    'message' => 'Operation result message',
    'data' => [] // Optional data
]
```

**Usage:**
```php
// Success response
$success = response(true, 'User created successfully', ['id' => 123]);

// Error response
$error = response(false, 'Invalid email address');
```

### generate_random_string

Generates a random string with specified length and character type.

```php
function generate_random_string($length = 32, $type = 'hex')
```

**Parameters:**
- `$length`: Desired length of the string (default: 32)
- `$type`: String type ('hex' or 'alphanumeric', default: 'hex')

**Types Available:**
| Type | Characters |
|------|------------|
| hex | 0-9, a-f |
| alphanumeric | 0-9, a-z, A-Z |

**Usage:**
```php
// Generate hex string
$token = generate_random_string(64, 'hex');

// Generate alphanumeric string
$userId = generate_random_string(16, 'alphanumeric');
```

**Security Features:**
- Uses cryptographically secure random_bytes()
- Uses random_int() for alphanumeric generation
- Provides unpredictable output

### redirect

Performs a page redirect with optional flash message.

```php
function redirect($path, $message = null, $type = 'error')
```

**Parameters:**
- `$path`: Target path to redirect to
- `$message`: (Optional) Flash message to display
- `$type`: (Optional) Message type ('error', 'success', etc., default: 'error')

**Usage:**
```php
// Simple redirect
redirect('dashboard');

// Redirect with success message
redirect('login', 'Please log in first', 'info');

// Redirect with error message
redirect('register', 'Email already exists', 'error');
```

### get_current_url

Gets the current base URL of the application.

```php
function get_current_url()
```

**Returns:**
- Complete base URL (e.g., 'https://example.com')

**Usage:**
```php
$baseUrl = get_current_url();
$fullUrl = get_current_url() . '/api/users';
```

## Best Practices

1. **Response Handling:**
```php
$result = someOperation();
if ($result) {
    return response(true, 'Operation successful', $result);
} else {
    return response(false, 'Operation failed');
}
```

2. **Random String Generation:**
```php
// For security tokens
$securityToken = generate_random_string(64, 'hex');

// For readable IDs
$publicId = generate_random_string(12, 'alphanumeric');
```

3. **Redirects:**
```php
// With error handling
try {
    processForm();
    redirect('success', 'Form processed', 'success');
} catch (Exception $e) {
    redirect('form', $e->getMessage(), 'error');
}
```

## Error Handling

**Response Function:**
```php
// Handle empty data
$result = response(false, 'No data found');

// Handle exceptions
try {
    // operation
} catch (Exception $e) {
    return response(false, $e->getMessage());
}
```

**Random String Generation:**
```php
try {
    $token = generate_random_string(32);
} catch (Exception $e) {
    // Handle random_bytes() failure
    log_error('Failed to generate random string: ' . $e->getMessage());
    return false;
}
```

## Security Considerations

1. **Random String Generation:**
   - Always use for security-critical tokens
   - Consider length based on use case
   - Use hex for maximum entropy

2. **Redirects:**
   - Validate redirect paths
   - Prevent open redirect vulnerabilities
   - Use absolute paths internally

3. **URL Generation:**
   - Always use HTTPS in production
   - Validate host headers
   - Handle proxy configurations

## Requirements

- PHP 7.0+ for random_bytes() and random_int()
- Session handling for flash messages
- Proper server configuration for redirects
- HTTPS support for secure operations

## Common Use Cases

```php
// API Response
function api_endpoint() {
    try {
        $data = process_request();
        return response(true, 'Success', $data);
    } catch (Exception $e) {
        return response(false, $e->getMessage());
    }
}

// Security Token
function create_verification_token() {
    return generate_random_string(64, 'hex');
}

// Protected Route
function check_auth() {
    if (!is_authenticated()) {
        redirect('login', 'Please log in', 'warning');
    }
}
```