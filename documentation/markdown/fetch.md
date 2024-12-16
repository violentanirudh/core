## HTTP Fetch Function Documentation

A simple HTTP client function that wraps cURL to make HTTP requests with a fetch-like API.

### fetch

Makes an HTTP request to a specified URL with configurable options.

```php
function fetch($url, $options = [])
```

**Parameters:**
- `$url`: The URL to send the request to
- `$options`: (Optional) Configuration array with the following properties:
  - `method`: HTTP method (default: 'GET')
  - `headers`: Array of request headers
  - `body`: Request body data
  - `timeout`: Request timeout in seconds (default: 30)

**Returns:**
Array containing:
- `ok`: Boolean indicating if status code is 2xx
- `status`: HTTP status code
- `body`: Response body
- `error`: Error message if request failed

**Basic Usage:**
```php
// Simple GET request
$response = fetch('https://api.example.com/users');
if ($response['ok']) {
    $data = json_decode($response['body'], true);
    // Process $data
}
```

**Advanced Usage Examples:**

GET Request with Headers:
```php
$response = fetch('https://api.example.com/data', [
    'headers' => [
        'Authorization' => 'Bearer token123',
        'Accept' => 'application/json'
    ]
]);
```

POST Request with JSON Body:
```php
$response = fetch('https://api.example.com/users', [
    'method' => 'POST',
    'headers' => [
        'Content-Type' => 'application/json'
    ],
    'body' => [
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ]
]);
```

PUT Request with Custom Timeout:
```php
$response = fetch('https://api.example.com/users/1', [
    'method' => 'PUT',
    'body' => ['status' => 'active'],
    'timeout' => 60
]);
```

**Error Handling:**
```php
$response = fetch('https://api.example.com/data');
if (!$response['ok']) {
    echo "Request failed with status: {$response['status']}";
    if ($response['error']) {
        echo "Error: {$response['error']}";
    }
}
```

**Response Structure:**
| Field | Type | Description |
|-------|------|-------------|
| ok | boolean | True if status code is 2xx |
| status | integer | HTTP status code |
| body | string | Response body |
| error | string | Error message if request failed |

**Common HTTP Status Codes:**
- 200: OK
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 500: Internal Server Error

**Requirements:**
- PHP with cURL extension enabled
- Allow outbound HTTP connections
- Proper error handling in your application

**Notes:**
- The function automatically converts array bodies to JSON
- Headers should be provided as associative array
- Default timeout is 30 seconds
- Response body is returned as string and may need decoding (e.g., `json_decode()`)