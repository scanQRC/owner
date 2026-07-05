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

    $vehicleId     = (int)($data['vehicle_id'] ?? 0);
    $vehicleNumber = strtoupper(trim($data['vehicle_number'] ?? ''));
    $vehicleType   = trim($data['vehicle_type'] ?? '');
    $brand         = trim($data['brand'] ?? '');
    $model         = trim($data['model'] ?? '');
    $color         = trim($data['color'] ?? '');

    if (
        $vehicleId <= 0 ||
        $vehicleNumber === '' ||
        $vehicleType === '' ||
        $brand === '' ||
        $model === '' ||
        $color === ''
    ) {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'All fields are required.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id
        FROM vehicles
        WHERE id = ?
          AND user_id = ?
        LIMIT 1
    ");

    $stmt->execute([
        $vehicleId,
        $_SESSION['user_id']
    ]);

    if (!$stmt->fetch()) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Vehicle not found.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id
        FROM vehicles
        WHERE vehicle_number = ?
          AND id <> ?
        LIMIT 1
    ");

    $stmt->execute([
        $vehicleNumber,
        $vehicleId
    ]);

    if ($stmt->fetch()) {

        http_response_code(409);

        echo json_encode([
            'success' => false,
            'message' => 'Vehicle number already exists.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE vehicles
        SET
            vehicle_number = ?,
            vehicle_type = ?,
            brand = ?,
            model = ?,
            color = ?
        WHERE id = ?
          AND user_id = ?
    ");

    $stmt->execute([
        $vehicleNumber,
        $vehicleType,
        $brand,
        $model,
        $color,
        $vehicleId,
        $_SESSION['user_id']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Vehicle updated successfully.'
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
