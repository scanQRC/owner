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

/* ---------------- ADMIN CHECK (OPTIMIZED) ---------------- */
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);

if ($stmt->fetchColumn() !== 'admin') {
    http_response_code(403);
    exit('Access denied.');
}

$pageTitle = 'Manage Users';

/* ---------------- INPUTS ---------------- */
$search = trim($_GET['search'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));

$limit  = 10;
$offset = ($page - 1) * $limit;

/* ---------------- COMMON WHERE BLOCK ---------------- */
$where  = "";
$params = [];

if ($search !== '') {
    $where = "WHERE full_name LIKE ? OR email LIKE ? OR mobile LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
}

/* ---------------- TOTAL USERS COUNT ---------------- */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users $where");
$stmt->execute($params);

$totalUsers = (int)$stmt->fetchColumn();
$totalPages = (int)ceil($totalUsers / $limit);

/* ---------------- USERS DATA ---------------- */
$sql = "
    SELECT id, full_name, email, mobile, status, created_at
    FROM users
    $where
    ORDER BY id DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Manage Users</h3>

            <form method="GET" class="d-flex">
                <input type="text"
                       name="search"
                       class="form-control form-control-sm"
                       placeholder="Search name, email, mobile..."
                       value="<?= htmlspecialchars($search); ?>">

                <button class="btn btn-sm btn-primary ms-2">Search</button>
            </form>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="170">Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (!$users): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No users found
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($users as $user): ?>

                    <tr>
                        <td><?= (int)$user['id']; ?></td>

                        <td><?= htmlspecialchars($user['full_name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['mobile']); ?></td>

                        <td>
                            <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                <?= htmlspecialchars($user['status']); ?>
                            </span>
                        </td>

                        <td><?= htmlspecialchars($user['created_at']); ?></td>

                        <td>
                            <a href="user-details.php?id=<?= (int)$user['id']; ?>"
                               class="btn btn-sm btn-primary">
                                View
                            </a>

                            <a href="toggle-status.php?id=<?= (int)$user['id']; ?>"
                               class="btn btn-sm btn-<?= $user['status'] === 'active' ? 'warning' : 'success'; ?>">
                                <?= $user['status'] === 'active' ? 'Disable' : 'Enable'; ?>
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <!-- PAGINATION -->
        <div class="card-footer d-flex justify-content-between">

            <small>
                Showing <?= $totalUsers > 0 ? ($offset + 1) : 0; ?>
                to <?= min($offset + $limit, $totalUsers); ?>
                of <?= $totalUsers; ?> users
            </small>

            <nav>
                <ul class="pagination pagination-sm mb-0">

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : ''; ?>">
                            <a class="page-link"
                               href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>">
                                <?= $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                </ul>
            </nav>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
