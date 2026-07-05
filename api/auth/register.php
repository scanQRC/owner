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

// Duplicate mobile check
$stmt = $pdo->prepare("
    SELECT id
    FROM users
    WHERE mobile = ?
    LIMIT 1
");
$stmt->execute([$mobile]);

if ($stmt->fetch()) {
    echo json_encode([
        'success' => false,
        'message' => 'Mobile number already registered.'
    ]);
    exit;
}

// Duplicate email check
$stmt = $pdo->prepare("
    SELECT id
    FROM users
    WHERE email = ?
    LIMIT 1
");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    echo json_encode([
        'success' => false,
        'message' => 'Email already registered.'
    ]);
    exit;
}

// Password hashing
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Create account
$stmt = $pdo->prepare("
    INSERT INTO users
    (
        full_name,
        mobile,
        email,
        password_hash
    )
    VALUES
    (?, ?, ?, ?)
");

$stmt->execute([
    $fullName,
    $mobile,
    $email,
    $passwordHash
]);

$userId = (int)$pdo->lastInsertId();

// Login session
session_regenerate_id(true);

$_SESSION['user_id'] = $userId;
$_SESSION['user_name'] = $fullName;
$_SESSION['user_email'] = $email;
$_SESSION['logged_in'] = true;

// Success response
echo json_encode([
    'success' => true,
    'message' => 'Registration successful.',
    'redirect' => '/dashboard/'
]);

exit;
