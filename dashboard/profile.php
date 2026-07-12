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

$pageTitle = 'My Profile';

$stmt = $pdo->prepare("
    SELECT
        full_name,
        email,
        mobile,
        created_at,
        last_login
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([
    $_SESSION['user_id']
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>My Profile</h3>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="220">Full Name</th>
                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                </tr>

                <tr>
                    <th>Mobile</th>
                    <td><?= htmlspecialchars($user['mobile']) ?></td>
                </tr>

                <tr>
                    <th>Member Since</th>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                </tr>

                <tr>
                    <th>Last Login</th>
                    <td><?= htmlspecialchars($user['last_login']) ?></td>
                </tr>

            </table>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
