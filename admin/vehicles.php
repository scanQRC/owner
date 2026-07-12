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

$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin || $admin['role'] !== 'admin') {
    http_response_code(403);
    exit('Access denied.');
}

$pageTitle = 'Manage Vehicles';

$stmt = $pdo->query("
    SELECT
        v.id,
        v.vehicle_number,
        v.vehicle_type,
        v.brand,
        v.model,
        v.color,
        u.full_name,
        v.created_at
    FROM vehicles v
    INNER JOIN users u
        ON u.id = v.user_id
    ORDER BY v.id DESC
");

$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>Manage Vehicles</h3>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Vehicle No.</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Color</th>
                        <th>Created</th>
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

                    <?php foreach ($vehicles as $vehicle): ?>

                    <tr>

                        <td><?= (int)$vehicle['id']; ?></td>

                        <td><?= htmlspecialchars($vehicle['vehicle_number']); ?></td>

                        <td><?= htmlspecialchars($vehicle['full_name']); ?></td>

                        <td><?= htmlspecialchars($vehicle['vehicle_type']); ?></td>

                        <td><?= htmlspecialchars($vehicle['brand']); ?></td>

                        <td><?= htmlspecialchars($vehicle['model']); ?></td>

                        <td><?= htmlspecialchars($vehicle['color']); ?></td>

                        <td><?= htmlspecialchars($vehicle['created_at']); ?></td>

                    </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
