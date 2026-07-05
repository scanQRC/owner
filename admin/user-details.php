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

$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($userId <= 0) {
    header('Location: users.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        id,
        full_name,
        email,
        mobile,
        role,
        status,
        created_at,
        last_login
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$userId]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: users.php');
    exit;
}

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>User Details</h3>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="220">User ID</th>
                    <td><?= (int)$user['id']; ?></td>
                </tr>

                <tr>
                    <th>Full Name</th>
                    <td><?= htmlspecialchars($user['full_name']); ?></td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                </tr>

                <tr>
                    <th>Mobile</th>
                    <td><?= htmlspecialchars($user['mobile']); ?></td>
                </tr>

                <tr>
                    <th>Role</th>
                    <td><?= htmlspecialchars($user['role']); ?></td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td><?= htmlspecialchars($user['status']); ?></td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td><?= htmlspecialchars($user['created_at']); ?></td>
                </tr>

                <tr>
                    <th>Last Login</th>
                    <td><?= htmlspecialchars($user['last_login']); ?></td>
                </tr>

            </table>

            <a href="users.php" class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
