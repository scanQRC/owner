<?php

declare(strict_types=1);

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

/*
|--------------------------------------------------------------------------
| Authentication Constants
|--------------------------------------------------------------------------
*/

const ADMIN_SESSION_KEY = 'admin';

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

function admin_login(array $admin): void
{
    session_regenerate_id(true);

    $_SESSION[ADMIN_SESSION_KEY] = [
        'id'         => (int) $admin['id'],
        'name'       => $admin['name'],
        'username'   => $admin['username'],
        'email'      => $admin['email'],
        'role'       => $admin['role'],
        'status'     => $admin['status'],
        'login_time' => time(),
    ];
}

function admin_logout(): void
{
    unset($_SESSION[ADMIN_SESSION_KEY]);

    session_destroy_all();
}

function admin(): ?array
{
    return $_SESSION[ADMIN_SESSION_KEY] ?? null;
}

function admin_logged_in(): bool
{
    return isset($_SESSION[ADMIN_SESSION_KEY]);
}

function admin_id(): ?int
{
    return $_SESSION[ADMIN_SESSION_KEY]['id'] ?? null;
}

/*
|--------------------------------------------------------------------------
| Route Protection
|--------------------------------------------------------------------------
*/

function require_admin(): void
{
    if (!admin_logged_in()) {

        redirect(APP_URL . '/admin/login.php');
    }
}

/*
|--------------------------------------------------------------------------
| Guest Protection
|--------------------------------------------------------------------------
*/

function require_guest(): void
{
    if (admin_logged_in()) {

        redirect(APP_URL . '/dashboard/');
    }
}

/*
|--------------------------------------------------------------------------
| Permission Check
|--------------------------------------------------------------------------
*/

function admin_has_role(string $role): bool
{
    if (!admin_logged_in()) {
        return false;
    }

    return ($_SESSION[ADMIN_SESSION_KEY]['role'] ?? '') === $role;
}

/*
|--------------------------------------------------------------------------
| Current Admin
|--------------------------------------------------------------------------
*/

function current_admin(string $field = null): mixed
{
    if (!admin_logged_in()) {
        return null;
    }

    if ($field === null) {
        return $_SESSION[ADMIN_SESSION_KEY];
    }

    return $_SESSION[ADMIN_SESSION_KEY][$field] ?? null;
}
