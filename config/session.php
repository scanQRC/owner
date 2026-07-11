<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/*
|--------------------------------------------------------------------------
| Session Configuration
|--------------------------------------------------------------------------
*/

const SESSION_TIMEOUT = 7200;
const SESSION_REGENERATE_INTERVAL = 1800;

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_trans_sid', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', (string) SESSION_TIMEOUT);

$secure = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    ((int)($_SERVER['SERVER_PORT'] ?? 80) === 443)
);

ini_set('session.cookie_secure', $secure ? '1' : '0');

session_name(SESSION_NAME);

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Session Fixation Protection
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['_created'])) {

    session_regenerate_id(true);

    $_SESSION['_created'] = time();
}

if ((time() - (int) $_SESSION['_created']) >= SESSION_REGENERATE_INTERVAL) {

    session_regenerate_id(true);

    $_SESSION['_created'] = time();
}

/*
|--------------------------------------------------------------------------
| Session Helpers
|--------------------------------------------------------------------------
*/

function session_set(string $key, mixed $value): void
{
    $_SESSION[$key] = $value;
}

function session_get(string $key, mixed $default = null): mixed
{
    return $_SESSION[$key] ?? $default;
}

function session_has(string $key): bool
{
    return array_key_exists($key, $_SESSION);
}

function session_remove(string $key): void
{
    unset($_SESSION[$key]);
}

function session_destroy_all(): void
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
            (bool) $params['secure'],
            (bool) $params['httponly']
        );
    }

    session_destroy();
}<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/*
|--------------------------------------------------------------------------
| Secure Session Configuration
|--------------------------------------------------------------------------
*/

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_trans_sid', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', '7200');

$secure = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
);

if ($secure) {
    ini_set('session.cookie_secure', '1');
}

session_name(SESSION_NAME);

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Session Fixation Protection
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['_created'])) {

    session_regenerate_id(true);

    $_SESSION['_created'] = time();
}

if ((time() - (int)$_SESSION['_created']) > 1800) {

    session_regenerate_id(true);

    $_SESSION['_created'] = time();
}

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function session_set(string $key, mixed $value): void
{
    $_SESSION[$key] = $value;
}

function session_get(string $key, mixed $default = null): mixed
{
    return $_SESSION[$key] ?? $default;
}

function session_has(string $key): bool
{
    return array_key_exists($key, $_SESSION);
}

function session_remove(string $key): void
{
    unset($_SESSION[$key]);
}

function session_destroy_all(): void
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
