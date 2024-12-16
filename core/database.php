<?php

function connect($host, $db, $user, $pass) {
    try {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);
        return response(true, 'Connection established', $pdo);
    } catch (PDOException $e) {
        return response(false, 'Connection failed: ' . $e->getMessage());
    }
}

function select($pdo, $table, $conditions = [], $fields = '*', $order_by = []) {
    $where = buildWhereClause($conditions);
    $order = buildOrderByClause($order_by);
    
    $sql = "SELECT $fields FROM $table" . $where['sql'] . $order;
    $stmt = executeQuery($pdo, $sql, $where['values'], 'Records retrieved successfully');
    
    if ($stmt['status']) {
        return response(true, $stmt['message'], $stmt['data']->fetchAll());
    }
    return $stmt;
}

function insert($pdo, $table, $data, $unique = []) {
    // Check unique constraints using select
    if (!empty($unique)) {
        $uniqueConditions = [];
        foreach ($unique as $field) {
            if (isset($data[$field])) {
                $uniqueConditions[$field] = $data[$field];
            }
        }
        
        if (!empty($uniqueConditions)) {
            $check = select($pdo, $table, $uniqueConditions);
            if ($check['status'] && !empty($check['data'])) {
                return response(false, 'Record already exists with unique constraint');
            }
        }
    }
    
    $fields = implode(', ', array_keys($data));
    $values = implode(', ', array_fill(0, count($data), '?'));
    $sql = "INSERT INTO $table ($fields) VALUES ($values)";
    
    $stmt = executeQuery($pdo, $sql, array_values($data), 'Record inserted successfully');
    
    if ($stmt['status']) {
        return response(true, $stmt['message'], $pdo->lastInsertId());
    }
    return $stmt;
}

function update($pdo, $table, $data, $conditions) {
    $set_parts = [];
    $values = [];
    
    foreach ($data as $key => $value) {
        $set_parts[] = "$key = ?";
        $values[] = $value;
    }
    
    $where = buildWhereClause($conditions);
    $sql = "UPDATE $table SET " . implode(', ', $set_parts) . $where['sql'];
    
    return executeQuery($pdo, $sql, array_merge($values, $where['values']), 'Record updated successfully');
}

function delete($pdo, $table, $conditions) {
    $where = buildWhereClause($conditions);
    $sql = "DELETE FROM $table" . $where['sql'];
    
    return executeQuery($pdo, $sql, $where['values'], 'Record deleted successfully');
}

function paginate($pdo, $table, $page = 1, $per_page = 10, $conditions = [], $fields = '*', $order_by = ['id' => 'DESC']) {
    $page = max(1, (int)$page);
    $offset = ($page - 1) * $per_page;
    
    $where = buildWhereClause($conditions);
    $order = buildOrderByClause($order_by);
    
    $count_sql = "SELECT COUNT(*) as total FROM $table" . $where['sql'];
    $count_stmt = executeQuery($pdo, $count_sql, $where['values'], 'Count completed');
    
    if (!$count_stmt['status']) {
        return $count_stmt;
    }
    
    $total = $count_stmt['data']->fetch()['total'];
    $total_pages = ceil($total / $per_page);
    
    $sql = "SELECT $fields FROM $table" . $where['sql'] . $order . " LIMIT $per_page OFFSET $offset";
    $stmt = executeQuery($pdo, $sql, $where['values'], 'Records retrieved successfully');
    
    if (!$stmt['status']) {
        return $stmt;
    }
    
    $pagination = [
        'data' => $stmt['data']->fetchAll(),
        'current_page' => $page,
        'per_page' => $per_page,
        'total' => $total,
        'total_pages' => $total_pages,
        'has_next' => $page < $total_pages,
        'has_prev' => $page > 1
    ];
    
    return response(true, 'Records retrieved successfully', $pagination);
}

function aggregate($pdo, $table, $operation, $field = '*', $conditions = []) {
    $where = buildWhereClause($conditions);
    $sql = "SELECT $operation($field) as result FROM $table" . $where['sql'];
    
    $stmt = executeQuery($pdo, $sql, $where['values'], 'Operation completed successfully');
    
    if ($stmt['status']) {
        $result = $stmt['data']->fetch();
        return response(true, $stmt['message'], $result['result'] ?? 0);
    }
    return $stmt;
}

function sum_records($pdo, $table, $field, $conditions = []) {
    return aggregate($pdo, $table, 'SUM', $field, $conditions);
}

function count_records($pdo, $table, $conditions = []) {
    return aggregate($pdo, $table, 'COUNT', '*', $conditions);
}

function distinct_records($pdo, $table, $field, $conditions = []) {
    $where = buildWhereClause($conditions);
    $sql = "SELECT DISTINCT $field FROM $table" . $where['sql'];
    
    $stmt = executeQuery($pdo, $sql, $where['values'], 'Distinct records retrieved successfully');
    
    if ($stmt['status']) {
        return response(true, $stmt['message'], $stmt['data']->fetchAll(PDO::FETCH_COLUMN));
    }
    return $stmt;
}

function buildWhereClause($conditions) {
    $sql = '';
    $values = [];
    $parts = [];
    
    if (!empty($conditions)) {
        $sql .= " WHERE ";
        foreach ($conditions as $key => $value) {
            if (is_array($value) && count($value) === 2) {
                $parts[] = "$key {$value[0]} ?";
                $values[] = $value[1];
            } else {
                $parts[] = "$key = ?";
                $values[] = $value;
            }
        }
        $sql .= implode(' AND ', $parts);
    }
    
    return ['sql' => $sql, 'values' => $values];
}

function buildOrderByClause($order_by) {
    if (empty($order_by)) return '';
    
    $order_parts = [];
    foreach ($order_by as $field => $direction) {
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'])) {
            $direction = 'DESC';
        }
        $order_parts[] = "$field $direction";
    }
    return " ORDER BY " . implode(', ', $order_parts);
}

function executeQuery($pdo, $sql, $values, $success_message) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        return response(true, $success_message, $stmt);
    } catch (PDOException $e) {
        return response(false, 'Query failed: ' . $e->getMessage());
    }
}
