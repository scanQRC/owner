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

    admin_logout();

    echo json_encode([
        'success'  => true,
        'message'  => 'Logout successful.',
        'redirect' => APP_URL . '/admin/login.php'
    ]);

} catch (Throwable $e) {

    if (function_exists('log_error')) {
        log_error('Logout Error: ' . $e->getMessage());
    }

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => APP_DEBUG
            ? $e->getMessage()
            : 'Something went wrong.'
    ]);
}
