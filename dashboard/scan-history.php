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

$pageTitle = 'Scan History';

$stmt = $pdo->prepare("
    SELECT
        sl.id,
        sl.scanned_at,
        sl.ip_address,
        v.vehicle_number
    FROM scan_logs sl
    INNER JOIN vehicles v
        ON v.id = sl.vehicle_id
    WHERE v.user_id = ?
    ORDER BY sl.scanned_at DESC
");

$stmt->execute([
    $_SESSION['user_id']
]);

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>Scan History</h3>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Vehicle</th>
                        <th>Scan Time</th>
                        <th>IP Address</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($logs)): ?>

                    <tr>
                        <td colspan="4" class="text-center">
                            No scan history available.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($logs as $index => $log): ?>

                    <tr>

                        <td><?= $index + 1 ?></td>

                        <td><?= htmlspecialchars($log['vehicle_number']) ?></td>

                        <td><?= htmlspecialchars($log['scanned_at']) ?></td>

                        <td><?= htmlspecialchars($log['ip_address']) ?></td>

                    </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
