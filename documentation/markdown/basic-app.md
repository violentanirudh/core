# Basic CRUD Application

This example demonstrates a simple CRUD (Create, Read, Update, Delete) application for managing users using the provided PHP framework. We'll create a basic user management system with API endpoints and views.

## File Structure

```
/your-app
  /api
    users.php
  /views
    home.php
    users
      list.php
      create.php
      edit.php
  /core
    (all core files)
  config.php
  index.php
  .htaccess (or nginx.conf)
```

## Configuration (config.php)

```php
<?php

require_once 'core/import.php';

define('DB_HOST', 'localhost');
define('DB_NAME', 'crud_app');
define('DB_USER', 'root');
define('DB_PASS', '');

define('JWT_SECRET_KEY', 'your_secret_key_here');
define('PRODUCTION', false);

$db = connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$settings = select($db, 'settings');

foreach ($settings['data'] as $setting) {
    define($setting['setting_name'], $setting['setting_value']);
}
```

## Main Application File (index.php)

```php
<?php

require_once 'config.php';

init_session();
initErrorHandler();

$routes = [
    '/' => 'home',
    '/users' => 'users/list',
    '/users/create' => 'users/create',
    '/users/edit/:id' => 'users/edit',
    '/api/users' => 'users',
    '/api/users/:id' => 'users'
];

router($routes);
```

## API Endpoints (api/users.php)

```php
<?php

switch ($request['method']) {
    case 'GET':
        if (isset($request['params']['id'])) {
            $user = select($db, 'users', ['id' => $request['params']['id']]);
            echo json_encode(response(true, 'User retrieved', $user['data'][0] ?? null));
        } else {
            $users = select($db, 'users');
            echo json_encode(response(true, 'Users retrieved', $users['data']));
        }
        break;

    case 'POST':
        $result = insert($db, 'users', $request['form']);
        echo json_encode($result);
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $put_vars);
        $result = update($db, 'users', $put_vars, ['id' => $request['params']['id']]);
        echo json_encode($result);
        break;

    case 'DELETE':
        $result = delete($db, 'users', ['id' => $request['params']['id']]);
        echo json_encode($result);
        break;

    default:
        echo json_encode(response(false, 'Invalid method'));
}
```

## Views

### Home Page (views/home.php)

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - CRUD App</title>
</head>
<body>
    <h1>Welcome to the CRUD App</h1>
    <nav>
        <a href="/users">Manage Users</a>
    </nav>
    <?php show_flash(); ?>
</body>
</html>
```

### User List (views/users/list.php)

```php
<?php
$users = select($db, 'users');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List - CRUD App</title>
</head>
<body>
    <h1>User List</h1>
    <a href="/users/create">Create New User</a>
    <?php show_flash(); ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users['data'] as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td>
                <a href="/users/edit/<?= $user['id'] ?>">Edit</a>
                <button onclick="deleteUser(<?= $user['id'] ?>)">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <script>
    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch('/api/users/' + id, { method: 'DELETE' })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }
    }
    </script>
</body>
</html>
```

### Create User (views/users/create.php)

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - CRUD App</title>
</head>
<body>
    <h1>Create User</h1>
    <?php show_flash(); ?>
    <form action="/api/users" method="POST">
        <?= csrf_field() ?>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Create User</button>
    </form>
    <a href="/users">Back to User List</a>
</body>
</html>
```

### Edit User (views/users/edit.php)

```php
<?php
$user = select($db, 'users', ['id' => $request['params']['id']]);
if (!$user['status'] || empty($user['data'])) {
    redirect('/users', 'User not found', 'error');
}
$user = $user['data'][0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - CRUD App</title>
</head>
<body>
    <h1>Edit User</h1>
    <?php show_flash(); ?>
    <form id="editForm">
        <?= csrf_field() ?>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $user['name'] ?>" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required>
        <button type="submit">Update User</button>
    </form>
    <a href="/users">Back to User List</a>
    <script>
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('/api/users/<?= $user['id'] ?>', {
            method: 'PUT',
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                window.location.href = '/users';
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
    </script>
</body>
</html>
```

## Usage

1. Set up your database and create a `users` table with `id`, `name`, and `email` columns.
2. Configure your web server to use the provided `.htaccess` or `nginx.conf` file.
3. Update the database credentials in `config.php`.
4. Access the application through your web browser.

This example demonstrates:

- Routing for both views and API endpoints
- CRUD operations using the database functions
- Form handling with CSRF protection
- Flash messages for user feedback
- Basic error handling
- Simple API for frontend JavaScript interaction

Remember to implement proper input validation, authentication, and authorization in a real-world application.