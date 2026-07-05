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
        color
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

$pageTitle = 'Edit Vehicle';

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-sm">

                <div class="card-header">
                    <h3>Edit Vehicle</h3>
                </div>

                <div class="card-body">

                    <form id="vehicleEditForm">

                        <input
                            type="hidden"
                            name="vehicle_id"
                            value="<?= $vehicle['id']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Vehicle Number</label>

                            <input
                                type="text"
                                name="vehicle_number"
                                class="form-control"
                                value="<?= htmlspecialchars($vehicle['vehicle_number']); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vehicle Type</label>

                            <input
                                type="text"
                                name="vehicle_type"
                                class="form-control"
                                value="<?= htmlspecialchars($vehicle['vehicle_type']); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>

                            <input
                                type="text"
                                name="brand"
                                class="form-control"
                                value="<?= htmlspecialchars($vehicle['brand']); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Model</label>

                            <input
                                type="text"
                                name="model"
                                class="form-control"
                                value="<?= htmlspecialchars($vehicle['model']); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Color</label>

                            <input
                                type="text"
                                name="color"
                                class="form-control"
                                value="<?= htmlspecialchars($vehicle['color']); ?>"
                                required>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary">
                            Update Vehicle
                        </button>

                        <a
                            href="vehicles.php"
                            class="btn btn-secondary">
                            Cancel
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
