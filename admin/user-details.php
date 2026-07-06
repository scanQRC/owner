<?php

declare(strict_types=1);

session_start();

require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

/* ---------------- AUTH CHECK ---------------- */
if (empty($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}

/* ---------------- ADMIN CHECK (SIMPLIFIED) ---------------- */
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);

if ($stmt->fetchColumn() !== 'admin') {
    http_response_code(403);
    exit('Access denied.');
}

/* ---------------- USER ID VALIDATION ---------------- */
$userId = (int)($_GET['id'] ?? 0);

if ($userId <= 0) {
    header('Location: users.php');
    exit;
}

/* ---------------- FETCH USER ---------------- */
$stmt = $pdo->prepare("
    SELECT id, full_name, email, mobile, role, status, created_at, last_login
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

        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">User Details</h3>

            <a href="users.php" class="btn btn-sm btn-secondary">
                Back
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered align-middle">

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
                    <td>
                        <span class="badge bg-primary">
                            <?= htmlspecialchars($user['role']); ?>
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary'; ?>">
                            <?= htmlspecialchars($user['status']); ?>
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td><?= htmlspecialchars($user['created_at']); ?></td>
                </tr>

                <tr>
                    <th>Last Login</th>
                    <td>
                        <?= $user['last_login']
                            ? htmlspecialchars($user['last_login'])
                            : '<span class="text-muted">Never logged in</span>'; ?>
                    </td>
                </tr>

            </table>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
