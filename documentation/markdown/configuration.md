## Configuration File Documentation (config.php)

This documentation covers the configuration file that sets up essential constants and database connection for the PHP framework.

### File Overview

```php
<?php
require_once 'core/import.php';

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'test');
define('DB_USER', 'root');
define('DB_PASS', '');

// Security settings
define('JWT_SECRET_KEY', 'XXXX1234XXXX');
define('PRODUCTION', false);

// Database connection
$db = connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Load dynamic settings
$settings = select($db, 'settings');

foreach ($settings as $setting) {
    define($setting['setting_name'], $setting['setting_value']);
}
```

### Components

1. **Core Import**
   - Imports all core framework components.

2. **Database Configuration**
   - Defines constants for database connection:
     - `DB_HOST`: Database host (default: 'localhost')
     - `DB_NAME`: Database name (default: 'test')
     - `DB_USER`: Database username (default: 'root')
     - `DB_PASS`: Database password (default: '')

3. **Security Settings**
   - `JWT_SECRET_KEY`: Secret key for JWT token generation and validation
   - `PRODUCTION`: Boolean flag to indicate production environment (default: false)

4. **Database Connection**
   - Establishes a connection to the database using the defined constants

5. **Dynamic Settings**
   - Retrieves settings from the 'settings' table in the database
   - Defines each setting as a constant

### Usage

1. **Customizing Database Connection:**
   ```php
   define('DB_HOST', 'your_host');
   define('DB_NAME', 'your_database');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

2. **Setting Production Mode:**
   ```php
   define('PRODUCTION', true);
   ```

3. **Accessing Dynamic Settings:**
   ```php
   echo APP_NAME; // Outputs the value of 'APP_NAME' from the settings table
   ```

### Best Practices

1. **Environment-Specific Configuration:**
   ```php
   if (file_exists('config.local.php')) {
       require_once 'config.local.php';
   } else {
       require_once 'config.php';
   }
   ```

2. **Secure Credential Management:**
   ```php
   define('DB_PASS', getenv('DB_PASSWORD'));
   ```

3. **Error Handling:**
   ```php
   $db = connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
   if (!$db['status']) {
       die('Database connection failed: ' . $db['message']);
   }
   ```

### Security Considerations

1. Never commit sensitive credentials to version control
2. Use strong, unique JWT secret keys
3. Enable production mode in live environments
4. Implement proper error handling and logging

### Requirements

- MySQL/MariaDB database
- 'settings' table in the database with columns:
  - `setting_name` (VARCHAR)
  - `setting_value` (VARCHAR)

### Common Issues and Solutions

| Issue | Solution |
|-------|----------|
| Database connection fails | Verify credentials and server status |
| Settings not loading | Check 'settings' table structure and data |
| Constant redefinition errors | Ensure unique setting names in database |

This configuration file provides a flexible foundation for your PHP application, allowing for easy customization and dynamic settings management.