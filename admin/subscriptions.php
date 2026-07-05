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

$pageTitle = 'Manage Subscriptions';

$stmt = $pdo->query("
    SELECT
        s.id,
        s.plan_name,
        s.start_date,
        s.expiry_date,
        s.status,
        u.full_name,
        u.email
    FROM subscriptions s
    INNER JOIN users u
        ON u.id = s.user_id
    ORDER BY s.id DESC
");

$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>Manage Subscriptions</h3>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Start Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($subscriptions)): ?>

                    <tr>
                        <td colspan="7" class="text-center">
                            No subscriptions found.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($subscriptions as $subscription): ?>

                    <tr>

                        <td><?= (int)$subscription['id']; ?></td>

                        <td><?= htmlspecialchars($subscription['full_name']); ?></td>

                        <td><?= htmlspecialchars($subscription['email']); ?></td>

                        <td><?= htmlspecialchars($subscription['plan_name']); ?></td>

                        <td><?= htmlspecialchars($subscription['start_date']); ?></td>

                        <td><?= htmlspecialchars($subscription['expiry_date']); ?></td>

                        <td><?= htmlspecialchars($subscription['status']); ?></td>

                    </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
