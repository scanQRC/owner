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

    $vehicleId = isset($_GET['vehicle_id'])
        ? (int)$_GET['vehicle_id']
        : 0;

    if ($vehicleId <= 0) {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'Invalid vehicle.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT
            vehicle_number,
            qr_token
        FROM vehicles
        WHERE id = ?
          AND user_id = ?
        LIMIT 1
    ");

    $stmt->execute([
        $vehicleId,
        $_SESSION['user_id']
    ]);

    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$vehicle) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Vehicle not found.'
        ]);

        exit;
    }

    if (empty($vehicle['qr_token'])) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'QR code has not been generated yet.'
        ]);

        exit;
    }

    $qrUrl = APP_URL . '/scan.php?token=' . $vehicle['qr_token'];

    echo json_encode([
        'success' => true,
        'vehicle_number' => $vehicle['vehicle_number'],
        'qr_token' => $vehicle['qr_token'],
        'download_url' => $qrUrl
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
