<?php

declare(strict_types=1);

session_start();

require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

/* ---------------- AUTH CHECK ---------------- */
if (empty($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}

/* ---------------- ADMIN CHECK ---------------- */
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);

if ($stmt->fetchColumn() !== 'admin') {
    http_response_code(403);
    exit('Access denied.');
}

/* ---------------- USER ID ---------------- */
$userId = (int)($_GET['id'] ?? 0);

if ($userId <= 0) {
    header('Location: users.php');
    exit;
}

/* ---------------- GET CURRENT STATUS ---------------- */
$stmt = $pdo->prepare("SELECT status FROM users WHERE id = ? LIMIT 1");
$stmt->execute([$userId]);

$currentStatus = $stmt->fetchColumn();

if (!$currentStatus) {
    header('Location: users.php');
    exit;
}

/* ---------------- TOGGLE LOGIC ---------------- */
$newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';

/* ---------------- UPDATE ---------------- */
$stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
$stmt->execute([$newStatus, $userId]);

/* ---------------- REDIRECT BACK ---------------- */
header("Location: users.php");
exit;
