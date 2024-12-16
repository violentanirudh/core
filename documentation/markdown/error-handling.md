## Error Handling Functions Documentation

This documentation covers functions for comprehensive error handling, logging, and error display in PHP applications.

### initErrorHandler

Initializes custom error and exception handlers.

```php
function initErrorHandler()
```

**Features:**
- Creates a 'logs' directory if it doesn't exist
- Sets up custom error and exception handlers
- Ensures proper logging infrastructure

**Usage:**
```php
// Initialize at application startup
initErrorHandler();
```

### logError

Custom error handler that logs PHP errors and displays an error page.

```php
function logError($errno, $errstr, $errfile, $errline)
```

**Parameters:**
- `$errno`: Error level/type (E_ERROR, E_WARNING, etc.)
- `$errstr`: Error message
- `$errfile`: File where error occurred
- `$errline`: Line number where error occurred

**Supported Error Types:**
```php
$errorTypes = [
    E_ERROR => 'ERROR',
    E_WARNING => 'WARNING',
    E_PARSE => 'PARSE',
    E_NOTICE => 'NOTICE',
    E_USER_ERROR => 'USER_ERROR',
    E_USER_WARNING => 'USER_WARNING',
    E_USER_NOTICE => 'USER_NOTICE'
];
```

**Log Format:**
```
[2024-12-16 14:30:00] ERROR:
  Message: Division by zero
  File: /var/www/app/index.php
  Line: 123
```

### logException

Custom exception handler that logs uncaught exceptions.

```php
function logException($exception)
```

**Parameters:**
- `$exception`: The Exception object to handle

**Log Format:**
```
[2024-12-16 14:30:00] EXCEPTION:
  Message: Invalid argument supplied
  File: /var/www/app/functions.php
  Line: 45
  Stack Trace:
    #0 /var/www/app/index.php(23): processData()
    #1 {main}
```

### displayErrorPage

Displays a user-friendly error page with error details.

```php
function displayErrorPage($type, $message, $file, $line, $trace = '')
```

**Parameters:**
- `$type`: Error type (ERROR, WARNING, EXCEPTION, etc.)
- `$message`: Error message
- `$file`: File where error occurred
- `$line`: Line number where error occurred
- `$trace`: (Optional) Stack trace for exceptions

**HTML Template Features:**
- Clean, responsive design
- Syntax-highlighted error details
- Stack trace display for exceptions
- Mobile-friendly layout

## Implementation Example

```php
// Initialize error handling
initErrorHandler();

// Example of triggering errors
try {
    throw new Exception("Something went wrong");
} catch (Exception $e) {
    // Will be caught by custom exception handler
    throw $e;
}

// Example of triggering PHP error
$result = 10 / 0; // Will be caught by custom error handler
```

## Error Page Styling

The error page includes CSS styling for better presentation:

```css
body {
    background: #f0f0f0;
    font-family: sans-serif;
    margin: 0;
    padding: 20px;
    line-height: 1.6;
}
.container {
    max-width: 1200px;
    margin: auto;
}
/* ... additional styles ... */
```

## Best Practices

1. **Log File Management:**
   - Implement log rotation
   - Set appropriate file permissions
   - Monitor log file size

2. **Security Considerations:**
   - Sanitize error output in production
   - Limit stack trace information in production
   - Secure log file access

3. **Error Handling:**
   ```php
   // Production vs Development
   if (PRODUCTION) {
       ini_set('display_errors', 0);
       ini_set('log_errors', 1);
   }
   ```

## Requirements

- PHP 7.0+
- Write permissions for logs directory
- Proper error reporting configuration
- Adequate disk space for logs

## Common Issues and Solutions

| Issue | Solution |
|-------|----------|
| Permission denied | Check directory/file permissions |
| Log file growth | Implement log rotation |
| Missing errors | Verify error reporting settings |
| Memory issues | Monitor and limit log file size |

## Error Logging Structure

```
/your-app
  /logs
    error.log    # Main error log file
    access.log   # Optional access log
    debug.log    # Optional debug log
```

## Notes

- Always test error handling in development
- Implement log rotation for production
- Consider different logging levels
- Monitor log files regularly
- Implement proper backup strategies for logs