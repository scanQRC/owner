<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

/*
|--------------------------------------------------------------------------
| Response Helpers
|--------------------------------------------------------------------------
*/

function success_response(
    string $message = 'Success.',
    array $data = [],
    int $status = 200
): never {

    http_response_code($status);

    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode([
        'success' => true,
        'message' => $message,
        'data'    => $data,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    exit;
}

function error_response(
    string $message = 'Something went wrong.',
    int $status = 400,
    array $errors = []
): never {

    http_response_code($status);

    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode([
        'success' => false,
        'message' => $message,
        'errors'  => $errors,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    exit;
}

function abort(
    int $status = 404,
    string $message = 'Page not found.'
): never {

    http_response_code($status);

    exit($message);
}
