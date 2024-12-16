# Core : Function-Based PHP Framework
# Getting Started

A lightweight, function-based PHP framework designed for simplicity and ease of use. This framework provides essential features for building web applications without the complexity of class-based OOP architecture.

## Key Features

- **Function-Based Architecture**: Simple and straightforward approach without classes
- **Database Abstraction**: Easy-to-use database operations with PDO
- **Routing System**: Clean URL routing for both views and API endpoints
- **Security Features**: CSRF protection, JWT authentication, and secure sessions
- **State Management**: Session and cookie handling with security best practices
- **Error Handling**: Comprehensive error logging and display system
- **Flash Messages**: User feedback system
- **File Operations**: Simple file handling utilities
- **HTTP Client**: Built-in fetch functionality for API requests
- **Email Support**: Basic email sending capabilities

## Quick Start

### 1. Installation

```bash
# Clone the repository
git clone https://github.com/violentanirudh/core-php.git

# Set up your web server (Apache or Nginx)
# Copy the appropriate configuration file (.htaccess or nginx.conf)
```

### 2. Configuration

```php
// config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

define('JWT_SECRET_KEY', 'your_secret_key');
define('PRODUCTION', false);
```

### 3. Basic Usage

```php
// index.php
require_once 'config.php';

init_session();
initErrorHandler();

$routes = [
    '/' => 'home',
    '/about' => 'about',
    '/api/users' => 'users'
];

router($routes);
```

## Common Use Cases

### 1. Basic CRUD Application
Perfect for:
- User management systems
- Content management systems
- Inventory management
- Simple blog platforms

### 2. API Development
Ideal for:
- RESTful APIs
- Mobile app backends
- Microservices
- Data integration services

### 3. Simple Web Applications
Suitable for:
- Landing pages
- Portfolio websites
- Small business websites
- Educational platforms

## Core Functions Overview

### Database Operations
```php
// Create a record
$result = insert($pdo, 'users', [
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// Read records
$users = select($pdo, 'users', ['status' => 'active']);

// Update a record
$result = update($pdo, 'users', 
    ['status' => 'inactive'], 
    ['id' => 5]
);

// Delete a record
$result = delete($pdo, 'users', ['id' => 5]);
```

### Authentication
```php
// User signup
$result = signup($pdo, [
    'email' => 'user@example.com',
    'password' => 'secure_password'
]);

// User signin
$result = signin($pdo, [
    'email' => 'user@example.com',
    'password' => 'secure_password'
]);
```

### Session Management
```php
// Set session data
set_session('user_id', 123);

// Get session data
$user_id = get_session('user_id');

// Clear session
clear_session();
```

## Project Structure

```
/your-app
├── /api                 # API endpoints
├── /core               # Framework core files
├── /views              # View files
├── /logs               # Error logs
├── config.php          # Configuration
├── index.php           # Application entry point
└── .htaccess           # Server configuration
```

## Security Features

1. **CSRF Protection**
   - Automatic token generation
   - Form validation
   - Token verification

2. **Session Security**
   - Secure session initialization
   - HTTP-only cookies
   - Same-site cookie policy

3. **Input Validation**
   - Database query sanitization
   - XSS prevention
   - SQL injection protection

## Best Practices

1. **Configuration**
   - Use environment-specific configurations
   - Secure credential management
   - Proper error reporting settings

2. **Error Handling**
   - Implement comprehensive error logging
   - User-friendly error messages
   - Detailed development logs

3. **Security**
   - Enable HTTPS
   - Implement rate limiting
   - Regular security audits

## Example Applications

1. **User Management System**
   - User registration and authentication
   - Profile management
   - Role-based access control

2. **Content Management System**
   - Article creation and editing
   - Media management
   - Category organization

3. **API Service**
   - RESTful endpoints
   - Data validation
   - Response formatting

## Requirements

- PHP 7.4+
- MySQL/MariaDB
- Apache/Nginx web server
- PDO PHP extension
- JSON PHP extension
- OpenSSL PHP extension

## Contributing

We welcome contributions! Please follow these steps:
1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## Support

For support and questions:
- Create an issue in the repository
- Join our community forum
- Check the documentation

This framework is ideal for developers who prefer a straightforward, functional approach to PHP development without the overhead of complex OOP patterns. It's particularly well-suited for small to medium-sized projects where simplicity and rapid development are priorities.