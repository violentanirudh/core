## JWT (JSON Web Token) Documentation

This documentation covers functions for creating and verifying JSON Web Tokens (JWT) using HS256 algorithm.

### create_jwt

Creates a JWT token with a given payload and secret key.

```php
function create_jwt($payload, $secret_key)
```

**Parameters:**
- `$payload`: Array containing the data to be encoded in the JWT
- `$secret_key`: Secret key used for signing the token

**Usage:**
```php
$payload = [
    'user_id' => 123,
    'role' => 'admin',
    'exp' => time() + 3600 // Expires in 1 hour
];
$token = create_jwt($payload, 'your-secret-key');
```

**Structure of Generated JWT:**
```
header.payload.signature
```

**Header Contents:**
```json
{
    "typ": "JWT",
    "alg": "HS256"
}
```

### verify_jwt

Verifies and decodes a JWT token.

```php
function verify_jwt($jwt, $secret_key)
```

**Parameters:**
- `$jwt`: JWT string to verify
- `$secret_key`: Secret key used to verify the signature

**Returns:**
- Decoded payload array if verification succeeds
- `false` if verification fails

**Usage:**
```php
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."; // JWT string
$result = verify_jwt($token, 'your-secret-key');
if ($result) {
    $user_id = $result['user_id'];
    $role = $result['role'];
} else {
    echo "Invalid token";
}
```

**Complete Example:**
```php
// Create a token
$payload = [
    'user_id' => 123,
    'role' => 'admin',
    'exp' => time() + 3600
];
$secret_key = 'your-secret-key';
$token = create_jwt($payload, $secret_key);

// Later, verify the token
$decoded = verify_jwt($token, $secret_key);
if ($decoded) {
    if ($decoded['exp'] < time()) {
        echo "Token has expired";
    } else {
        echo "Valid token for user: " . $decoded['user_id'];
    }
} else {
    echo "Invalid token";
}
```

**Security Considerations:**
- Always use a strong secret key
- Include expiration time in payload
- Store secret key securely
- Never expose secret key in client-side code
- Use HTTPS for token transmission

**Common Payload Claims:**
| Claim | Description |
|-------|-------------|
| exp | Expiration time |
| iat | Issued at time |
| sub | Subject (user ID) |
| iss | Issuer |
| aud | Audience |

**Error Cases:**
- Invalid token format (not 3 parts)
- Invalid signature
- Expired token
- Malformed JSON in payload
- Invalid base64url encoding

**Requirements:**
- hash_hmac function
- hash_equals function (for timing attack protection)
- JSON functions