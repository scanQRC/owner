<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method Not Allowed'
    ]);

    exit;
}

$fullName = cleanInput($_POST['full_name'] ?? '');
$mobile   = cleanInput($_POST['mobile'] ?? '');
$email    = cleanInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (
    $fullName === '' ||
    $mobile === '' ||
    $email === '' ||
    $password === ''
) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required.'
    ]);
    exit;
}

if (!isValidMobile($mobile)) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid mobile number.'
    ]);

    exit;
}

if (!isValidEmail($email)) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid email address.'
    ]);

    exit;
}

if (strlen($password) < 8) {

    echo json_encode([
        'success' => false,
        'message' => 'Password must contain at least 8 characters.'
    ]);

    exit;
}
