<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SCANME CSRF Protection
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
| Hidden Field
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
        empty($token) ||
        !isset($_SESSION[CSRF_TOKEN_NAME])
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION[CSRF_TOKEN_NAME],
        $token
    );
}

/*
|--------------------------------------------------------------------------
| Current Request Token
|--------------------------------------------------------------------------
*/

function csrf_request_token(): ?string
{
    $header = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if ($header !== '') {
        return $header;
    }

    if (!empty($_POST['_token'])) {
        return $_POST['_token'];
    }

    $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');

    if (str_contains($contentType, 'application/json')) {

        $json = json_decode(file_get_contents('php://input'), true);

        if (is_array($json)) {
            return $json['_token'] ?? null;
        }
    }

    return null;
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
            'message' => 'Invalid CSRF Token.'
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