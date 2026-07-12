<?php

declare(strict_types=1);

session_start();

require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

if (empty($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT role
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([
    $_SESSION['user_id']
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    exit('Access denied.');
}

$pageTitle = 'Admin Dashboard';

include_once '../includes/header.php';
?>

<div class="container py-4">

    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6>Total Users</h6>

                    <?php
                    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
                    ?>

                    <h3><?= (int)$count; ?></h3>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6>Total Vehicles</h6>

                    <?php
                    $count = $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
                    ?>

                    <h3><?= (int)$count; ?></h3>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6>Total QR Codes</h6>

                    <?php
                    $count = $pdo->query("
                        SELECT COUNT(*)
                        FROM vehicles
                        WHERE qr_token IS NOT NULL
                    ")->fetchColumn();
                    ?>

                    <h3><?= (int)$count; ?></h3>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6>Total Scans</h6>

                    <?php
                    $count = $pdo->query("SELECT COUNT(*) FROM scan_logs")->fetchColumn();
                    ?>

                    <h3><?= (int)$count; ?></h3>

                </div>
            </div>
        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
