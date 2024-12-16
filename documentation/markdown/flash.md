## Flash Message Functions Documentation

This documentation covers the flash message system functions. Flash messages are temporary messages stored in the session to display notifications, alerts, or feedback to users across page requests.

### init_flash

Initializes the flash message array in the session if it doesn't exist.

```php
function init_flash()
```

**Usage:**
```php
init_flash();
// Session now contains an empty flash message array
```

**Note:**
- This function is typically called internally by other flash functions
- No need to call directly unless initializing the flash system manually

### set_flash

Adds a new flash message to the session.

```php
function set_flash($message, $type = 'info')
```

**Parameters:**
- `$message`: The message text to display
- `$type`: (Optional) Message type - 'success', 'error', 'warning', or 'info' (default)

**Usage:**
```php
// Add success message
set_flash('Profile updated successfully', 'success');

// Add error message
set_flash('Invalid credentials', 'error');

// Add info message (default type)
set_flash('Please check your email');
```

### get_flash

Retrieves all flash messages and clears them from the session.

```php
function get_flash()
```

**Returns:**
- Array of flash messages, each containing 'message' and 'type'

**Usage:**
```php
$messages = get_flash();
foreach ($messages as $flash) {
    echo $flash['message']; // Message text
    echo $flash['type'];    // Message type
}
```

### show_flash

Displays all flash messages with styled HTML output using Tailwind CSS classes.

```php
function show_flash()
```

**Styling:**
- Success messages: Green background with dark green text
- Error messages: Red background with dark red text
- Warning messages: Yellow background with dark yellow text
- Info messages: Blue background with dark blue text

**Usage:**
```php
// In your PHP view or template
show_flash();
```

**HTML Output Example:**
```html
<div class="rounded-md p-4 mb-4 ring-1 ring-inset bg-green-50 text-green-800 ring-green-600/20">
    <p class="text-sm leading-6 capitalize">Profile updated successfully</p>
</div>
```

**Color Scheme:**
| Type | Background | Text | Ring |
|------|------------|------|------|
| success | bg-green-50 | text-green-800 | ring-green-600/20 |
| error | bg-red-50 | text-red-800 | ring-red-600/20 |
| warning | bg-yellow-50 | text-yellow-800 | ring-yellow-600/20 |
| info | bg-blue-50 | text-blue-800 | ring-blue-600/20 |

**Complete Example:**
```php
// Set multiple flash messages
set_flash('Profile updated', 'success');
set_flash('Some fields are required', 'error');
set_flash('Please verify your email');

// In your view/template
show_flash();
```

**Requirements:**
- PHP session must be started using `session_start()`
- Tailwind CSS must be included in your project for styling