<?php

declare(strict_types=1);

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

try {

    $token = trim($_GET['token'] ?? '');

    if ($token === '') {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'QR token is required.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT
            v.id,
            v.vehicle_number,
            v.vehicle_type,
            v.brand,
            v.model,
            v.color,
            u.full_name
        FROM vehicles v
        INNER JOIN users u
            ON u.id = v.user_id
        WHERE v.qr_token = ?
        LIMIT 1
    ");

    $stmt->execute([$token]);

    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$vehicle) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Invalid QR code.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO scan_logs
        (
            vehicle_id,
            scanned_at,
            ip_address
        )
        VALUES
        (
            ?,
            NOW(),
            ?
        )
    ");

    $stmt->execute([
        $vehicle['id'],
        $_SERVER['REMOTE_ADDR'] ?? ''
    ]);

    echo json_encode([
        'success' => true,
        'vehicle' => $vehicle
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
