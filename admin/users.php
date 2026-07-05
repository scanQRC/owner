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

$pageTitle = 'Manage Users';

$users = $pdo->query("
    SELECT
        id,
        full_name,
        email,
        mobile,
        status,
        created_at
    FROM users
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>Manage Users</h3>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="120">Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($users as $user): ?>

                    <tr>

                        <td><?= (int)$user['id']; ?></td>

                        <td><?= htmlspecialchars($user['full_name']); ?></td>

                        <td><?= htmlspecialchars($user['email']); ?></td>

                        <td><?= htmlspecialchars($user['mobile']); ?></td>

                        <td><?= htmlspecialchars($user['status']); ?></td>

                        <td><?= htmlspecialchars($user['created_at']); ?></td>

                        <td>

                            <a
                                href="user-details.php?id=<?= $user['id']; ?>"
                                class="btn btn-sm btn-primary">
                                View
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
