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

$pageTitle = 'My Vehicles';

$stmt = $pdo->prepare("
    SELECT
        id,
        vehicle_number,
        vehicle_type,
        brand,
        model,
        color,
        created_at
    FROM vehicles
    WHERE user_id = ?
    ORDER BY id DESC
");

$stmt->execute([
    $_SESSION['user_id']
]);

$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>My Vehicles</h2>

        <a href="vehicle-add.php" class="btn btn-primary">
            Add Vehicle
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Vehicle No.</th>
                        <th>Type</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Color</th>
                        <th>Added</th>
                        <th width="180">Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($vehicles)): ?>

                    <tr>
                        <td colspan="8" class="text-center">
                            No vehicles found.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($vehicles as $index => $vehicle): ?>

                    <tr>

                        <td><?= $index + 1 ?></td>

                        <td><?= htmlspecialchars($vehicle['vehicle_number']) ?></td>

                        <td><?= htmlspecialchars($vehicle['vehicle_type']) ?></td>

                        <td><?= htmlspecialchars($vehicle['brand']) ?></td>

                        <td><?= htmlspecialchars($vehicle['model']) ?></td>

                        <td><?= htmlspecialchars($vehicle['color']) ?></td>

                        <td><?= htmlspecialchars($vehicle['created_at']) ?></td>

                        <td>

                            <a
                                href="vehicle-edit.php?id=<?= $vehicle['id'] ?>"
                                class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <a
                                href="qr.php?id=<?= $vehicle['id'] ?>"
                                class="btn btn-sm btn-success">
                                QR
                            </a>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
