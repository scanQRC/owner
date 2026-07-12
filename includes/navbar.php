<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/bootstrap.php';

require_admin();

$admin = current_admin();
?>

<header class="topbar">

    <div class="topbar-left">
        <h1><?= e($pageTitle ?? APP_NAME) ?></h1>
    </div>

    <div class="topbar-right">

        <div class="admin-info">

            <span class="admin-name">
                <?= e($admin['name'] ?? 'Administrator') ?>
            </span>

            <?php if (!empty($admin['role'])): ?>
                <small class="admin-role">
                    <?= e(ucfirst($admin['role'])) ?>
                </small>
            <?php endif; ?>

        </div>

        <div class="topbar-actions">

            <a href="<?= APP_URL ?>/dashboard/profile.php">
                Profile
            </a>

            <a href="<?= APP_URL ?>/admin/logout.php">
                Logout
            </a>

        </div>

    </div>

</header>

<main class="content">
