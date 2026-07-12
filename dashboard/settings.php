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

$pageTitle = 'Settings';

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h3>Account Settings</h3>
        </div>

        <div class="card-body">

            <form id="settingsForm">

                <div class="mb-3">
                    <label class="form-label">Full Name</label>

                    <input
                        type="text"
                        class="form-control"
                        name="full_name"
                        value="<?= htmlspecialchars($_SESSION['user_name']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        class="form-control"
                        name="email"
                        value="<?= htmlspecialchars($_SESSION['user_email']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>

                    <input
                        type="password"
                        class="form-control"
                        name="new_password">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>

                    <input
                        type="password"
                        class="form-control"
                        name="confirm_password">
                </div>

                <button
                    type="submit"
                    class="btn btn-primary">
                    Save Changes
                </button>

            </form>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
