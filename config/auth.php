<?php

declare(strict_types=1);

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/functions.php';

/*
|--------------------------------------------------------------------------
| SCANME Authentication
|--------------------------------------------------------------------------
*/

const ADMIN_SESSION_KEY = 'admin';

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

function admin_login(array $admin): void
{
    session_regenerate_id(true);

    $_SESSION[ADMIN_SESSION_KEY] = [

        'id'            => (int) $admin['id'],
        'name'          => (string) ($admin['name'] ?? ''),
        'username'      => (string) ($admin['username'] ?? ''),
        'email'         => (string) ($admin['email'] ?? ''),
        'role'          => (string) ($admin['role'] ?? 'USER'),
        'status'        => (string) ($admin['status'] ?? 'ACTIVE'),

        'login_time'    => time(),
        'last_activity' => time(),

    ];
}

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

function admin_logout(): void
{
    unset($_SESSION[ADMIN_SESSION_KEY]);

    session_destroy_all();
}

/*
|--------------------------------------------------------------------------
| Current Admin
|--------------------------------------------------------------------------
*/

function admin(): ?array
{
    return $_SESSION[ADMIN_SESSION_KEY] ?? null;
}

/*
|--------------------------------------------------------------------------
| Logged In ?
|--------------------------------------------------------------------------
*/

function admin_logged_in(): bool
{
    if (!isset($_SESSION[ADMIN_SESSION_KEY])) {
        return false;
    }

    if (
        isset($_SESSION[ADMIN_SESSION_KEY]['last_activity']) &&
        (
            time() -
            (int) $_SESSION[ADMIN_SESSION_KEY]['last_activity']
        ) > SESSION_TIMEOUT
    ) {

        admin_logout();

        return false;
    }

    $_SESSION[ADMIN_SESSION_KEY]['last_activity'] = time();

    return true;
}

/*
|--------------------------------------------------------------------------
| Current Admin ID
|--------------------------------------------------------------------------
*/

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

        exit;
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

        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Role Check
|--------------------------------------------------------------------------
*/

function admin_has_role(string $role): bool
{
    if (!admin_logged_in()) {
        return false;
    }

    return strtoupper(
        $_SESSION[ADMIN_SESSION_KEY]['role'] ?? ''
    ) === strtoupper($role);
}

/*
|--------------------------------------------------------------------------
| Current Admin Field
|--------------------------------------------------------------------------
*/

function current_admin(?string $field = null): mixed
{
    if (!admin_logged_in()) {
        return null;
    }

    if ($field === null) {
        return $_SESSION[ADMIN_SESSION_KEY];
    }

    return $_SESSION[ADMIN_SESSION_KEY][$field] ?? null;
}