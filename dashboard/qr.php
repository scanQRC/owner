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

$vehicleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($vehicleId <= 0) {
    header('Location: vehicles.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        id,
        vehicle_number,
        vehicle_type,
        brand,
        model,
        color,
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
    header('Location: vehicles.php');
    exit;
}

$pageTitle = 'Vehicle QR';

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>Vehicle QR Code</h3>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="220">Vehicle Number</th>
                    <td><?= htmlspecialchars($vehicle['vehicle_number']) ?></td>
                </tr>

                <tr>
                    <th>Type</th>
                    <td><?= htmlspecialchars($vehicle['vehicle_type']) ?></td>
                </tr>

                <tr>
                    <th>Brand</th>
                    <td><?= htmlspecialchars($vehicle['brand']) ?></td>
                </tr>

                <tr>
                    <th>Model</th>
                    <td><?= htmlspecialchars($vehicle['model']) ?></td>
                </tr>

                <tr>
                    <th>Color</th>
                    <td><?= htmlspecialchars($vehicle['color']) ?></td>
                </tr>

                <tr>
                    <th>QR Token</th>
                    <td>
                        <?= $vehicle['qr_token'] ?: '<span class="text-danger">Not Generated</span>' ?>
                    </td>
                </tr>

            </table>

            <div class="mt-4">

                <button
                    id="generateQr"
                    class="btn btn-success">
                    Generate QR
                </button>

                <button
                    id="regenerateQr"
                    class="btn btn-warning">
                    Regenerate QR
                </button>

                <button
                    id="downloadQr"
                    class="btn btn-primary">
                    Download QR
                </button>

            </div>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
