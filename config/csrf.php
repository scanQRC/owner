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

/*
|--------------------------------------------------------------------------
| Generate CSRF Token
|--------------------------------------------------------------------------
*/

function csrf_token(): string
{
    if (
        !isset($_SESSION[CSRF_TOKEN_NAME]) ||
        !isset($_SESSION['_csrf_created']) ||
        (time() - (int) $_SESSION['_csrf_created']) >= CSRF_TOKEN_TTL
    ) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        $_SESSION['_csrf_created'] = time();
    }

    return $_SESSION[CSRF_TOKEN_NAME];
}

/*
|--------------------------------------------------------------------------
| CSRF Hidden Input
|--------------------------------------------------------------------------
*/

function csrf_field(): string
{
    return sprintf(
        '<input type="hidden" name="_token" value="%s">',
        htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8')
    );
}

/*
|--------------------------------------------------------------------------
| Verify Token
|--------------------------------------------------------------------------
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
        (string)$token
    );
}

/*
|--------------------------------------------------------------------------
| Get Request Token
|--------------------------------------------------------------------------
*/

function csrf_request_token(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');

    /*
    |--------------------------------------------------------------------------
    | JSON Request
    |--------------------------------------------------------------------------
    */

    if (str_contains($contentType, 'application/json')) {

        $raw = file_get_contents('php://input');

        if ($raw === false || trim($raw) === '') {
            return null;
        }

        $data = json_decode($raw, true);

        if (!is_array($data)) {
            return null;
        }

        return $data['_token'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Form Request
    |--------------------------------------------------------------------------
    */

    return $_POST['_token'] ?? null;
}

/*
|--------------------------------------------------------------------------
| Validate Current Request
|--------------------------------------------------------------------------
*/

function csrf_validate_request(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    if (!csrf_verify(csrf_request_token())) {

        http_response_code(419);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => 'Invalid CSRF token.'
        ]);

        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Regenerate Token
|--------------------------------------------------------------------------
*/

function csrf_regenerate(): void
{
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    $_SESSION['_csrf_created'] = time();
}

/*
|--------------------------------------------------------------------------
| CSRF Header Helper
|--------------------------------------------------------------------------
*/

function csrf_header(): array
{
    return [
        'X-CSRF-TOKEN' => csrf_token()
    ];
}
