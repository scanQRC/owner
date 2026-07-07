<?php

declare(strict_types=1);

require_once __DIR__ . '/session.php';

/*
|--------------------------------------------------------------------------
| CSRF Protection
|--------------------------------------------------------------------------
*/

const CSRF_TOKEN_NAME = '_csrf_token';
const CSRF_TOKEN_TTL  = 7200;

/**
 * Generate CSRF Token
 */
function csrf_token(): string
{
    if (
        !isset($_SESSION[CSRF_TOKEN_NAME]) ||
        !isset($_SESSION['_csrf_created']) ||
        (time() - (int) $_SESSION['_csrf_created']) > CSRF_TOKEN_TTL
    ) {

        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        $_SESSION['_csrf_created'] = time();
    }

    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Hidden Input Field
 */
function csrf_field(): string
{
    return sprintf(
        '<input type="hidden" name="_token" value="%s">',
        htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8')
    );
}

/**
 * Verify CSRF Token
 */
function csrf_verify(?string $token): bool
{
    if (
        !isset($_SESSION[CSRF_TOKEN_NAME]) ||
        empty($token)
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION[CSRF_TOKEN_NAME],
        $token
    );
}

/**
 * Verify POST Request
 */
function csrf_validate_request(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $token = $_POST['_token'] ?? '';

    if (!csrf_verify($token)) {

        http_response_code(419);

        exit('Invalid CSRF Token.');
    }
}

/**
 * Regenerate Token
 */
function csrf_regenerate(): void
{
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    $_SESSION['_csrf_created'] = time();
}
