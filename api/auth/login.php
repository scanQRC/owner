<?php

declare(strict_types=1);

session_start();

header('Content-Type: application/json');

require_once '../../config/config.php';
require_once '../../config/db.php';
require_once '../../includes/functions.php';

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
        throw new Exception('Invalid request.');
    }

    $login = trim($data['login'] ?? '');
    $password = $data['password'] ?? '';

    if ($login === '' || $password === '') {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'Login and password are required.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE mobile = ?
           OR email = ?
        LIMIT 1
    ");

    $stmt->execute([
        $login,
        $login
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {

        http_response_code(401);

        echo json_encode([
            'success' => false,
            'message' => 'Invalid login credentials.'
        ]);

        exit;
    }

    if (!password_verify($password, $user['password_hash'])) {

        http_response_code(401);

        echo json_encode([
            'success' => false,
            'message' => 'Invalid login credentials.'
        ]);

        exit;
    }

    if (isset($user['status']) && $user['status'] !== 'active') {

        http_response_code(403);

        echo json_encode([
            'success' => false,
            'message' => 'Your account is inactive.'
        ]);

        exit;
    }

    session_regenerate_id(true);

    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['user_email'] = $user['email'];

    $stmt = $pdo->prepare("
        UPDATE users
        SET last_login = NOW()
        WHERE id = ?
    ");

    $stmt->execute([
        $user['id']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Login successful.',
        'redirect' => '/dashboard/'
    ]);

    exit;

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Something went wrong.'
    ]);

    exit;
}
