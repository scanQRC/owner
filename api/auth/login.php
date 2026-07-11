<?php

declare(strict_types=1);

header('Content-Type: application/json');

require_once '../../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;
}

try {

    $data = json_decode(file_get_contents('php://input'), true);

    if (!is_array($data)) {
        throw new Exception('Invalid request payload.');
    }

    $login = trim((string)($data['login'] ?? ''));
    $password = (string)($data['password'] ?? '');

    if ($login === '' || $password === '') {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'Mobile/Email and Password are required.'
        ]);

        exit;
    }

    $user = db_fetch(
        "SELECT *
         FROM users
         WHERE mobile = :login
            OR email = :login
         LIMIT 1",
        [
            ':login' => $login
        ]
    );

    if (!$user || !password_verify($password, $user['password_hash'])) {

        http_response_code(401);

        echo json_encode([
            'success' => false,
            'message' => 'Invalid login credentials.'
        ]);

        exit;
    }

    if (($user['status'] ?? '') !== 'active') {

        http_response_code(403);

        echo json_encode([
            'success' => false,
            'message' => 'Account is inactive.'
        ]);

        exit;
    }

    admin_login([
        'id'       => (int)$user['id'],
        'name'     => $user['full_name'],
        'username' => $user['mobile'],
        'email'    => $user['email'],
        'role'     => $user['role'] ?? 'user',
        'status'   => $user['status']
    ]);

    db_execute(
        "UPDATE users
            SET last_login = NOW()
          WHERE id = :id",
        [
            ':id' => $user['id']
        ]
    );

    echo json_encode([
        'success'  => true,
        'message'  => 'Login successful.',
        'redirect' => APP_URL . '/dashboard/'
    ]);

} catch (Throwable $e) {

    if (function_exists('log_error')) {
        log_error('Login Error: ' . $e->getMessage());
    }

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => APP_DEBUG
            ? $e->getMessage()
            : 'Something went wrong.'
    ]);
}
