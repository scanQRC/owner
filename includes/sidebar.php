<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/bootstrap.php';

require_admin();

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">

    <div class="sidebar-brand">
        <a href="<?= APP_URL ?>/dashboard/">
            <strong><?= APP_NAME ?></strong>
        </a>
    </div>

    <ul class="sidebar-menu">

        <li class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/dashboard/">
                Dashboard
            </a>
        </li>

        <li class="<?= str_contains($currentPage, 'user') ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/dashboard/users.php">
                Users
            </a>
        </li>

        <li class="<?= str_contains($currentPage, 'vehicle') ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/dashboard/vehicles.php">
                Vehicles
            </a>
        </li>

        <li class="<?= str_contains($currentPage, 'document') ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/dashboard/documents.php">
                Documents
            </a>
        </li>

        <li class="<?= str_contains($currentPage, 'subscription') ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/dashboard/subscriptions.php">
                Subscriptions
            </a>
        </li>

        <li class="<?= str_contains($currentPage, 'report') ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/dashboard/reports.php">
                Reports
            </a>
        </li>

        <li class="<?= str_contains($currentPage, 'setting') ? 'active' : '' ?>">
            <a href="<?= APP_URL ?>/dashboard/settings.php">
                Settings
            </a>
        </li>

        <li>
            <a href="<?= APP_URL ?>/admin/logout.php">
                Logout
            </a>
        </li>

    </ul>

</aside>
