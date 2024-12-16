## Web Server Configuration Documentation

This documentation covers the configuration for Apache (.htaccess) and Nginx (nginx.conf) to secure and route requests for a PHP application.

### Apache Configuration (.htaccess)

```apache
# Prevent direct access to PHP files
<FilesMatch "\.php$">
    Deny from all
</FilesMatch>

# Allow only index.php
<Files "index.php">
    Allow from all
</Files>

# Route everything through index.php
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php/$1 [L]
```

#### Key Features:
1. **PHP File Protection**: Blocks direct access to all PHP files.
2. **Index.php Exception**: Allows access only to index.php.
3. **URL Rewriting**: Routes all requests through index.php.

#### Usage:
- Place this file in your web root directory.
- Ensure Apache's mod_rewrite module is enabled.

### Nginx Configuration (nginx.conf)

```nginx
server {
    # ... other server configurations ...

    # Prevent direct access to PHP files
    location ~ \.php$ {
        deny all;
    }

    # Allow access to index.php
    location = /index.php {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;  # Adjust to your PHP-FPM socket
        fastcgi_index index.php;
        include fastcgi_params;
    }

    # Route everything through index.php
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }
}
```

#### Key Features:
1. **PHP File Protection**: Denies access to all PHP files.
2. **Index.php Handling**: Configures processing for index.php.
3. **URL Rewriting**: Directs all requests to index.php if the URI doesn't match a file or directory.

#### Usage:
- Include this configuration in your server block in nginx.conf or site-specific configuration file.
- Adjust the `fastcgi_pass` directive to match your PHP-FPM socket or TCP address.

### Security Benefits

1. **Prevents Direct Script Access**: Blocks potential vulnerabilities in individual PHP files.
2. **Single Entry Point**: Centralizes request handling through index.php.
3. **Clean URLs**: Enables user-friendly URLs without exposing file structure.

### Implementation Steps

1. **Apache**:
   - Enable mod_rewrite: `sudo a2enmod rewrite`
   - Restart Apache: `sudo service apache2 restart`

2. **Nginx**:
   - Test configuration: `nginx -t`
   - Reload Nginx: `sudo service nginx reload`

### Common Issues and Solutions

| Issue | Solution |
|-------|----------|
| 403 Forbidden | Check file permissions |
| 404 Not Found | Verify RewriteBase in .htaccess or location block in nginx.conf |
| 500 Internal Server Error | Check PHP-FPM configuration and logs |

### Best Practices

1. **Regular Updates**: Keep web server and PHP versions up-to-date.
2. **SSL/TLS**: Implement HTTPS for all traffic.
3. **Logging**: Enable and monitor error and access logs.
4. **File Permissions**: Set appropriate permissions on web files and directories.

These configurations provide a secure foundation for routing and protecting your PHP application on both Apache and Nginx web servers.