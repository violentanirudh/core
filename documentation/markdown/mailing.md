## Email Functions Documentation

This documentation covers functions for handling various email operations including verification emails, password reset emails, and support requests.

### send_mail

Generic function to send HTML emails.

```php
function send_mail($to, $from, $subject, $body, $type = 'default')
```

**Parameters:**
- `$to`: Recipient email address
- `$from`: Sender email address
- `$subject`: Email subject line
- `$body`: HTML content of the email
- `$type`: (Optional) Type of email for categorization

**Returns:**
- `true` if email was sent successfully
- `false` if sending failed

**Usage:**
```php
$result = send_mail(
    'user@example.com',
    'noreply@yourapp.com',
    'Welcome!',
    '<h1>Welcome to our app!</h1>'
);
```

### send_verification_mail

Sends an email verification link to a user.

```php
function send_verification_mail($to, $from, $verification_token)
```

**Parameters:**
- `$to`: User's email address
- `$from`: Application's email address
- `$verification_token`: Unique token for email verification

**Returns:**
- `true` if verification email was sent
- `false` if sending failed

**Usage:**
```php
$result = send_verification_mail(
    'newuser@example.com',
    'verify@yourapp.com',
    'abc123token'
);
```

**Email Template:**
```html
Click the link below to verify your email:
<a href='https://yourapp.com/verify/abc123token'>Verify Email</a>
```

### send_reset_password_mail

Sends a password reset link to a user.

```php
function send_reset_password_mail($to, $from, $reset_token)
```

**Parameters:**
- `$to`: User's email address
- `$from`: Application's email address
- `$reset_token`: Unique token for password reset

**Returns:**
- `true` if reset email was sent
- `false` if sending failed

**Usage:**
```php
$result = send_reset_password_mail(
    'user@example.com',
    'reset@yourapp.com',
    'xyz789token'
);
```

**Email Template:**
```html
Click the link below to reset your password:
<a href='https://yourapp.com/reset-password/xyz789token'>Reset Password</a>
```

### send_contact_support_mail

Sends a support request email.

```php
function send_contact_support_mail($to, $from, $message)
```

**Parameters:**
- `$to`: Support team email address
- `$from`: User's email address
- `$message`: Support request message

**Returns:**
- `true` if support email was sent
- `false` if sending failed

**Usage:**
```php
$result = send_contact_support_mail(
    'support@yourapp.com',
    'user@example.com',
    'I need help with my account'
);
```

## Best Practices

1. **Email Configuration:**
   ```php
   // Configure PHP mail settings in php.ini or runtime
   ini_set('SMTP', 'smtp.yourserver.com');
   ini_set('smtp_port', '587');
   ```

2. **Error Handling:**
   ```php
   if (!send_verification_mail($to, $from, $token)) {
       log_error("Failed to send verification email to: $to");
       // Handle error appropriately
   }
   ```

3. **Security Considerations:**
   - Use HTTPS for all email links
   - Set appropriate token expiration times
   - Validate email addresses
   - Implement rate limiting for email sending

## Common Issues and Solutions

| Issue | Solution |
|-------|----------|
| Emails in spam | Configure SPF, DKIM, and DMARC records |
| Failed delivery | Check server mail logs and SMTP settings |
| Invalid tokens | Implement token expiration and validation |
| Missing emails | Verify spam filters and email configuration |

## Requirements

- PHP with mail function enabled
- Properly configured mail server or SMTP settings
- Valid SSL certificate for HTTPS URLs
- Proper DNS records for email delivery

## Error Messages

```php
// Example error handling
try {
    if (!send_mail($to, $from, $subject, $body)) {
        throw new Exception("Failed to send email to: $to");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    return false;
}
```

## Notes

- Always validate email addresses before sending
- Consider using a third-party email service for better deliverability
- Implement email queuing for large volumes
- Monitor email sending rates and success rates
- Keep email templates maintainable and responsive