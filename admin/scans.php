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

$pageTitle = 'Scan Logs';

$stmt = $pdo->query("
    SELECT
        sl.id,
        sl.scanned_at,
        sl.ip_address,
        v.vehicle_number,
        u.full_name
    FROM scan_logs sl
    INNER JOIN vehicles v
        ON v.id = sl.vehicle_id
    INNER JOIN users u
        ON u.id = v.user_id
    ORDER BY sl.scanned_at DESC
");

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>Scan Logs</h3>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Vehicle</th>
                        <th>Owner</th>
                        <th>Scanned At</th>
                        <th>IP Address</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($logs)): ?>

                    <tr>
                        <td colspan="5" class="text-center">
                            No scan logs found.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($logs as $log): ?>

                    <tr>

                        <td><?= (int)$log['id']; ?></td>

                        <td><?= htmlspecialchars($log['vehicle_number']); ?></td>

                        <td><?= htmlspecialchars($log['full_name']); ?></td>

                        <td><?= htmlspecialchars($log['scanned_at']); ?></td>

                        <td><?= htmlspecialchars($log['ip_address']); ?></td>

                    </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
