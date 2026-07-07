<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/*
|--------------------------------------------------------------------------
| Output Helpers
|--------------------------------------------------------------------------
*/

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/*
|--------------------------------------------------------------------------
| JSON Response
|--------------------------------------------------------------------------
*/

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);

    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| Request Helpers
|--------------------------------------------------------------------------
*/

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function is_get(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET';
}

/*
|--------------------------------------------------------------------------
| Input Helpers
|--------------------------------------------------------------------------
*/

function post(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $default;
}

function get(string $key, mixed $default = null): mixed
{
    return $_GET[$key] ?? $default;
}

/*
|--------------------------------------------------------------------------
| Flash Messages
|--------------------------------------------------------------------------
*/

function flash(string $key, string $message): void
{
    $_SESSION['_flash'][$key] = $message;
}

function get_flash(string $key): ?string
{
    if (!isset($_SESSION['_flash'][$key])) {
        return null;
    }

    $message = $_SESSION['_flash'][$key];

    unset($_SESSION['_flash'][$key]);

    return $message;
}

/*
|--------------------------------------------------------------------------
| Random Token
|--------------------------------------------------------------------------
*/

function random_token(int $length = QR_TOKEN_LENGTH): string
{
    return substr(
        bin2hex(random_bytes($length)),
        0,
        $length
    );
}

/*
|--------------------------------------------------------------------------
| Date & Time
|--------------------------------------------------------------------------
*/

function now(): string
{
    return date('Y-m-d H:i:s');
}

/*
|--------------------------------------------------------------------------
| File Upload
|--------------------------------------------------------------------------
*/

function upload_path(string $file = ''): string
{
    return rtrim(UPLOAD_PATH, DIRECTORY_SEPARATOR)
        . DIRECTORY_SEPARATOR
        . ltrim($file, DIRECTORY_SEPARATOR);
}
