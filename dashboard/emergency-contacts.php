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

$pageTitle = 'Emergency Contacts';

$stmt = $pdo->prepare("
    SELECT
        id,
        contact_name,
        contact_mobile,
        relationship
    FROM emergency_contacts
    WHERE user_id = ?
    ORDER BY id ASC
");

$stmt->execute([
    $_SESSION['user_id']
]);

$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Emergency Contacts</h2>

        <button
            class="btn btn-primary"
            id="addContact">
            Add Contact
        </button>

    </div>

    <div class="card shadow-sm">

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Relationship</th>
                        <th width="150">Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($contacts)): ?>

                    <tr>
                        <td colspan="5" class="text-center">
                            No emergency contacts found.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($contacts as $index => $contact): ?>

                    <tr>

                        <td><?= $index + 1 ?></td>

                        <td><?= htmlspecialchars($contact['contact_name']) ?></td>

                        <td><?= htmlspecialchars($contact['contact_mobile']) ?></td>

                        <td><?= htmlspecialchars($contact['relationship']) ?></td>

                        <td>

                            <button
                                class="btn btn-sm btn-warning">
                                Edit
                            </button>

                            <button
                                class="btn btn-sm btn-danger">
                                Delete
                            </button>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
