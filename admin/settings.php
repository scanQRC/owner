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

$pageTitle = 'Admin Settings';

$stmt = $pdo->query("
    SELECT
        setting_key,
        setting_value
    FROM settings
    ORDER BY setting_key ASC
");

$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>System Settings</h3>
        </div>

        <div class="card-body">

            <?php if (empty($settings)): ?>

                <div class="alert alert-warning">
                    No settings found.
                </div>

            <?php else: ?>

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>
                            <th width="300">Setting</th>
                            <th>Value</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($settings as $setting): ?>

                        <tr>

                            <td><?= htmlspecialchars($setting['setting_key']); ?></td>

                            <td><?= htmlspecialchars($setting['setting_value']); ?></td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
