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

$pageTitle = 'Subscription';

$stmt = $pdo->prepare("
    SELECT
        plan_name,
        start_date,
        expiry_date,
        status
    FROM subscriptions
    WHERE user_id = ?
    ORDER BY id DESC
    LIMIT 1
");

$stmt->execute([
    $_SESSION['user_id']
]);

$subscription = $stmt->fetch(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>My Subscription</h3>
        </div>

        <div class="card-body">

            <?php if (!$subscription): ?>

                <div class="alert alert-warning">
                    No active subscription found.
                </div>

            <?php else: ?>

                <table class="table table-bordered">

                    <tr>
                        <th width="220">Plan</th>
                        <td><?= htmlspecialchars($subscription['plan_name']) ?></td>
                    </tr>

                    <tr>
                        <th>Start Date</th>
                        <td><?= htmlspecialchars($subscription['start_date']) ?></td>
                    </tr>

                    <tr>
                        <th>Expiry Date</th>
                        <td><?= htmlspecialchars($subscription['expiry_date']) ?></td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td><?= htmlspecialchars($subscription['status']) ?></td>
                    </tr>

                </table>

                <a href="#" class="btn btn-success">
                    Renew Subscription
                </a>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
