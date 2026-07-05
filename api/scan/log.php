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
            sl.id,
            sl.vehicle_id,
            sl.scanned_at,
            sl.ip_address,
            v.vehicle_number
        FROM scan_logs sl
        INNER JOIN vehicles v
            ON v.id = sl.vehicle_id
        WHERE v.user_id = ?
        ORDER BY sl.scanned_at DESC
        LIMIT 100
    ");

    $stmt->execute([
        $_SESSION['user_id']
    ]);

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'count' => count($logs),
        'data' => $logs
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
