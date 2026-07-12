<?php

declare(strict_types=1);

session_start();

header('Content-Type: application/json');

require_once '../../config/config.php';
require_once '../../config/db.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;
}

if (empty($_SESSION['logged_in']) || empty($_SESSION['user_id'])) {

    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access.'
    ]);

    exit;
}

try {

    $stmt = $pdo->prepare("
        SELECT
            id,
            vehicle_number,
            vehicle_type,
            brand,
            model,
            color,
            created_at
        FROM vehicles
        WHERE user_id = ?
        ORDER BY id DESC
    ");

    $stmt->execute([
        $_SESSION['user_id']
    ]);

    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'count' => count($vehicles),
        'data' => $vehicles
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
