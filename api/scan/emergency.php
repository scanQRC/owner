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
            u.full_name,
            ec.contact_name,
            ec.contact_mobile,
            ec.relationship
        FROM vehicles v
        INNER JOIN users u
            ON u.id = v.user_id
        LEFT JOIN emergency_contacts ec
            ON ec.user_id = u.id
        WHERE v.qr_token = ?
        ORDER BY ec.id ASC
    ");

    $stmt->execute([$token]);

    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$contacts) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'No emergency contacts found.'
        ]);

        exit;
    }

    echo json_encode([
        'success' => true,
        'vehicle_number' => $contacts[0]['vehicle_number'],
        'owner_name' => $contacts[0]['full_name'],
        'contacts' => $contacts
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
