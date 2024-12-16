<?php

function signup($pdo, $user_data, $verification = true) {
    if (!isset($user_data['email']) || !isset($user_data['password'])) {
        return response(false, 'Email and password are required');
    }

    $existing_user = select($pdo, 'users', ['email' => $user_data['email']]);
    if ($existing_user['status'] && !empty($existing_user['data'])) {
        return response(false, 'Email already exists');
    }

    $system_fields = [
        'userid' => generate_random_string(16, 'alphanumeric'),
        'verification_token' => $verification ? generate_random_string(64) : null,
        'role' => $user_data['role'] ?? 'user',
        'verified' => $verification ? 0 : 1,
        'failed_attempts' => 0,
        'created_at' => date('Y-m-d H:i:s')
    ];

    $final_data = array_merge($user_data, $system_fields);
    $final_data['password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);
    unset($final_data['confirm_password']);

    $user_id = insert($pdo, 'users', $final_data);
    if (!$user_id['status']) {
        return response(false, 'Signup failed');
    }

    if ($verification) {
        if (send_verification_mail($final_data['email'], $final_data['verification_token'])) {
            return response(true, 'Please verify your email', ['userid' => $final_data['userid']]);
        }
        return response(false, 'Signup successful, but failed to send verification email');
    }

    return response(true, 'Signup successful', ['userid' => $final_data['userid']]);
}


function signin($pdo, $credentials, $additional_checks = []) {
    if (!isset($credentials['email']) || !isset($credentials['password'])) {
        return response(false, 'Email and password are required');
    }

    $user = select($pdo, 'users', ['email' => $credentials['email']]);
    if (!$user['status'] || empty($user['data'])) {
        return response(false, 'Invalid credentials');
    }

    $user = $user['data'][0];

    if ($user['failed_attempts'] >= 5 && strtotime($user['last_failed_attempt']) > (time() - 900)) {
        return response(false, 'Account locked. Please try again after 15 minutes');
    }

    if (!password_verify($credentials['password'], $user['password'])) {
        update_failed_attempts($pdo, $user['userid']);
        return response(false, 'Invalid credentials');
    }

    if (!$user['verified']) {
        return response(false, 'Please verify your email first');
    }

    reset_failed_attempts($pdo, $user['userid']);

    foreach ($additional_checks as $check => $value) {
        if (!isset($user[$check]) || $user[$check] != $value) {
            return response(false, "Account $check verification failed");
        }
    }

    $sensitive_fields = ['password', 'verification_token', 'reset_token', 'failed_attempts', 'last_failed_attempt'];
    $payload = array_diff_key($user, array_flip($sensitive_fields));
    $payload['exp'] = time() + (7 * 24 * 60 * 60);
    $jwt = create_jwt($payload, JWT_SECRET_KEY);
    set_cookie('auth_token', $jwt, 7);
    return response(true, 'Login successful', $payload);
}

function verify($pdo, $token) {
    $user = select($pdo, 'users', ['verification_token' => $token]);
    if (!$user['status'] || empty($user['data'])) {
        return response(false, 'Invalid verification token');
    }

    $update = update($pdo, 'users', 
        ['verified' => 1, 'verification_token' => null],
        ['userid' => $user['data'][0]['userid']]
    );
    
    if (!$update['status']) {
        return response(false, 'Verification failed');
    }

    return response(true, 'Email verified successfully');
}

function user_logged_in($required_role = null) {
    $token = get_cookie('auth_token');
    if (!$token) {
        return false;
    }

    $payload = verify_jwt($token, JWT_SECRET_KEY);
    if (!$payload) {
        return false;
    }

    if ($required_role !== null) {
        if (is_array($required_role)) {
            return in_array($payload['role'], $required_role) ? $payload : false;
        }
        return $payload['role'] === $required_role ? $payload : false;
    }

    return $payload;
}

function logout() {
    delete_cookie('auth_token');
    return response(true, 'Successfully logged out');
}

function reset_password_request($pdo, $email) {
    $user = select($pdo, 'users', ['email' => $email]);
    if (!$user['status'] || empty($user['data'])) {
        return response(false, 'Email not found');
    }

    $reset_token = generate_random_string(64);
    $update = update($pdo, 'users',
        ['reset_token' => $reset_token],
        ['email' => $email]
    );

    if (!$update['status']) {
        return response(false, 'Reset password request failed');
    }

    if (send_reset_password_mail($email, $reset_token)) {
        return response(true, 'Reset password instructions sent');
    }
    return response(false, 'Failed to send reset password email');
}

function update_user($pdo, $userid, $data, $allowed_fields = ['email', 'role', 'verified']) {
    $update_data = array_intersect_key($data, array_flip($allowed_fields));

    if (empty($update_data)) {
        return response(false, 'No valid fields to update');
    }

    $result = update($pdo, 'users', $update_data, ['userid' => $userid]);
    if ($result['status']) {
        return response(true, 'User updated successfully');
    }
    return response(false, 'Failed to update user');
}

function update_password($pdo, $userid, $current_password, $new_password) {
    $user = select($pdo, 'users', ['userid' => $userid]);
    if (!$user['status'] || empty($user['data'])) {
        return response(false, 'User not found');
    }

    if (!password_verify($current_password, $user['data'][0]['password'])) {
        return response(false, 'Current password is incorrect');
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $result = update($pdo, 'users', ['password' => $hashed_password], ['userid' => $userid]);

    if ($result['status']) {
        return response(true, 'Password updated successfully');
    }
    return response(false, 'Failed to update password');
}

function update_failed_attempts($pdo, $userid) {
    $user = select($pdo, 'users', ['userid' => $userid]);
    if (!$user['status'] || empty($user['data'])) {
        return false;
    }

    $failed_attempts = $user['data'][0]['failed_attempts'] + 1;
    $last_failed_attempt = date('Y-m-d H:i:s');

    return update($pdo, 'users', 
        ['failed_attempts' => $failed_attempts, 'last_failed_attempt' => $last_failed_attempt],
        ['userid' => $userid]
    );
}

function reset_failed_attempts($pdo, $userid) {
    return update($pdo, 'users', 
        ['failed_attempts' => 0, 'last_failed_attempt' => null],
        ['userid' => $userid]
    );
}
