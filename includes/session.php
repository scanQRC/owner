<?php

declare(strict_types=1);

/**
 * --------------------------------------------------------
 * SCANME QR
 * Session Management
 * --------------------------------------------------------
 */

if (session_status() === PHP_SESSION_NONE) {

    require_once dirname(__DIR__) . '/config/config.php';

    session_name(SESSION_NAME);

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

/**
 * Regenerate Session ID
 */
function regenerateSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

/**
 * Check Login
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Require Login
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Logout
 */
function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
