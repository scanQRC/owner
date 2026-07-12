<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'success' => true,
    'token'   => csrf_token(),
]);