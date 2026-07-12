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

if (empty($_SESSION['logged_in']) || empty($_SESSION['user_id'])) {

    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access.'
    ]);

    exit;
}

try {

    $data = json_decode(file_get_contents('php://input'), true);

    if (!is_array($data)) {
        throw new Exception('Invalid request.');
    }

    $vehicleId = (int)($data['vehicle_id'] ?? 0);

    if ($vehicleId <= 0) {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'Invalid vehicle.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id, vehicle_number
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

    $qrToken = bin2hex(random_bytes(32));

    $stmt = $pdo->prepare("
        UPDATE vehicles
        SET qr_token = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $qrToken,
        $vehicleId
    ]);

    $qrUrl = APP_URL . '/scan.php?token=' . $qrToken;

    echo json_encode([
        'success' => true,
        'message' => 'QR generated successfully.',
        'token' => $qrToken,
        'qr_url' => $qrUrl
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
