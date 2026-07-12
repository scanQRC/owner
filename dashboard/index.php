<?php

declare(strict_types=1);

require_once '../config/bootstrap.php';

require_admin();

$pageTitle = 'Dashboard';

$admin = current_admin();

$userId = admin_id();

$totalVehicles = db_count(
    "SELECT COUNT(*) FROM vehicles WHERE user_id = :id",
    [
        ':id' => $userId
    ]
);

$totalQrCodes = db_count(
    "SELECT COUNT(*)
     FROM vehicles
     WHERE user_id = :id
       AND qr_token IS NOT NULL",
    [
        ':id' => $userId
    ]
);

$totalScans = db_count(
    "SELECT COUNT(*)
     FROM scan_logs sl
     INNER JOIN vehicles v
        ON v.id = sl.vehicle_id
     WHERE v.user_id = :id",
    [
        ':id' => $userId
    ]
);

include_once '../includes/header.php';

?>

<div class="container py-4">

    <h2 class="mb-4">
        Welcome,
        <?= htmlspecialchars($admin['name']) ?>
    </h2>

    <div class="row g-4">

        <div class="col-md-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h5>Total Vehicles</h5>

                    <h2><?= $totalVehicles ?></h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h5>Total QR Codes</h5>

                    <h2><?= $totalQrCodes ?></h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h5>Total Scans</h5>

                    <h2><?= $totalScans ?></h2>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
