## Database Functions Documentation

This documentation provides an overview of the database functions implemented in a function-based PHP framework. These functions offer a simplified interface for common database operations using PDO.

### connect

Establishes a connection to a MySQL database.

```php
function connect($host, $db, $user, $pass)
```

**Parameters:**
- `$host`: The database host
- `$db`: The database name
- `$user`: The database username
- `$pass`: The database password

**Usage:**
```php
$connection = connect('localhost', 'mydatabase', 'username', 'password');
if ($connection['status']) {
    $pdo = $connection['data'];
    // Use $pdo for further database operations
} else {
    echo $connection['message'];
}
```

**Errors:**
- Returns a response with status `false` if the connection fails, including the error message.

### select

Retrieves records from a specified table.

```php
function select($pdo, $table, $conditions = [], $fields = '*', $order_by = [])
```

**Parameters:**
- `$pdo`: PDO connection object
- `$table`: Name of the table to select from
- `$conditions`: (Optional) Array of conditions for WHERE clause
- `$fields`: (Optional) String of fields to select, default is '*'
- `$order_by`: (Optional) Array of field => direction pairs for ORDER BY clause

**Usage:**
```php
$result = select($pdo, 'users', ['status' => 'active'], 'id, name', ['created_at' => 'DESC']);
if ($result['status']) {
    $users = $result['data'];
    // Process $users
} else {
    echo $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the query fails, including the error message.

### insert

Inserts a new record into a specified table.

```php
function insert($pdo, $table, $data, $unique = [])
```

**Parameters:**
- `$pdo`: PDO connection object
- `$table`: Name of the table to insert into
- `$data`: Associative array of column => value pairs to insert
- `$unique`: (Optional) Array of field names to check for uniqueness before inserting

**Usage:**
```php
$result = insert($pdo, 'users', ['name' => 'John Doe', 'email' => 'john@example.com'], ['email']);
if ($result['status']) {
    $newUserId = $result['data'];
    echo "New user inserted with ID: $newUserId";
} else {
    echo $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the insert fails or if a unique constraint is violated.

### update

Updates existing records in a specified table.

```php
function update($pdo, $table, $data, $conditions)
```

**Parameters:**
- `$pdo`: PDO connection object
- `$table`: Name of the table to update
- `$data`: Associative array of column => value pairs to update
- `$conditions`: Array of conditions for WHERE clause

**Usage:**
```php
$result = update($pdo, 'users', ['status' => 'inactive'], ['id' => 5]);
if ($result['status']) {
    echo $result['message'];
} else {
    echo "Update failed: " . $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the update fails, including the error message.

### delete

Deletes records from a specified table.

```php
function delete($pdo, $table, $conditions)
```

**Parameters:**
- `$pdo`: PDO connection object
- `$table`: Name of the table to delete from
- `$conditions`: Array of conditions for WHERE clause

**Usage:**
```php
$result = delete($pdo, 'users', ['id' => 10]);
if ($result['status']) {
    echo $result['message'];
} else {
    echo "Delete failed: " . $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the delete operation fails, including the error message.

### paginate

Retrieves paginated records from a specified table.

```php
function paginate($pdo, $table, $page = 1, $per_page = 10, $conditions = [], $fields = '*', $order_by = ['id' => 'DESC'])
```

**Parameters:**
- `$pdo`: PDO connection object
- `$table`: Name of the table to select from
- `$page`: (Optional) Current page number, default is 1
- `$per_page`: (Optional) Number of records per page, default is 10
- `$conditions`: (Optional) Array of conditions for WHERE clause
- `$fields`: (Optional) String of fields to select, default is '*'
- `$order_by`: (Optional) Array of field => direction pairs for ORDER BY clause

**Usage:**
```php
$result = paginate($pdo, 'posts', 2, 15, ['status' => 'published']);
if ($result['status']) {
    $pagination = $result['data'];
    // Process $pagination['data'] for current page records
    // Use other pagination information as needed
} else {
    echo $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the query fails, including the error message.

### aggregate

Performs an aggregate operation on a specified table.

```php
function aggregate($pdo, $table, $operation, $field = '*', $conditions = [])
```

**Parameters:**
- `$pdo`: PDO connection object
- `$table`: Name of the table to perform the operation on
- `$operation`: The SQL aggregate function (e.g., 'SUM', 'COUNT', 'AVG')
- `$field`: (Optional) The field to apply the operation to, default is '*'
- `$conditions`: (Optional) Array of conditions for WHERE clause

**Usage:**
```php
$result = aggregate($pdo, 'orders', 'SUM', 'total_amount', ['status' => 'completed']);
if ($result['status']) {
    $sum = $result['data'];
    echo "Total sum of completed orders: $sum";
} else {
    echo $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the operation fails, including the error message.

### sum_records

Calculates the sum of a specified field in a table.

```php
function sum_records($pdo, $table, $field, $conditions = [])
```

**Parameters:**
- `$pdo`: PDO connection object
- `$table`: Name of the table
- `$field`: The field to sum
- `$conditions`: (Optional) Array of conditions for WHERE clause

**Usage:**
```php
$result = sum_records($pdo, 'products', 'stock_quantity', ['category' => 'electronics']);
if ($result['status']) {
    $totalStock = $result['data'];
    echo "Total stock of electronics: $totalStock";
} else {
    echo $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the operation fails, including the error message.

### count_records

Counts the number of records in a table.

```php
function count_records($pdo, $table, $conditions = [])
```

**Parameters:**
- `$pdo`: PDO connection object
- `$table`: Name of the table
- `$conditions`: (Optional) Array of conditions for WHERE clause

**Usage:**
```php
$result = count_records($pdo, 'users', ['status' => 'active']);
if ($result['status']) {
    $activeUsers = $result['data'];
    echo "Number of active users: $activeUsers";
} else {
    echo $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the operation fails, including the error message.

### distinct_records

Retrieves distinct values from a specified field in a table.

```php
function distinct_records($pdo, $table, $field, $conditions = [])
```

**Parameters:**
- `$pdo`: PDO connection object
- `$table`: Name of the table
- `$field`: The field to retrieve distinct values from
- `$conditions`: (Optional) Array of conditions for WHERE clause

**Usage:**
```php
$result = distinct_records($pdo, 'orders', 'status');
if ($result['status']) {
    $statuses = $result['data'];
    echo "Distinct order statuses: " . implode(', ', $statuses);
} else {
    echo $result['message'];
}
```

**Errors:**
- Returns a response with status `false` if the operation fails, including the error message.

## Helper Functions

These functions are used internally by the main database functions:

- `buildWhereClause`: Constructs the WHERE clause for SQL queries.
- `buildOrderByClause`: Constructs the ORDER BY clause for SQL queries.
- `executeQuery`: Executes a prepared SQL statement and handles errors.

These helper functions are not intended to be used directly in your application code.