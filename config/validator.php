<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Validation Helpers
|--------------------------------------------------------------------------
*/

function required(mixed $value): bool
{
    return !is_null($value) && trim((string)$value) !== '';
}

function min_length(string $value, int $length): bool
{
    return mb_strlen(trim($value)) >= $length;
}

function max_length(string $value, int $length): bool
{
    return mb_strlen(trim($value)) <= $length;
}

function valid_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function valid_mobile(string $mobile): bool
{
    return (bool)preg_match('/^[6-9][0-9]{9}$/', $mobile);
}

function valid_vehicle_number(string $number): bool
{
    return (bool)preg_match('/^[A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{1,4}$/', strtoupper(str_replace(' ', '', $number)));
}

function valid_password(string $password): bool
{
    return strlen($password) >= 8;
}

function sanitize_string(?string $value): string
{
    return trim(strip_tags($value ?? ''));
}

function sanitize_email(?string $email): string
{
    return filter_var(trim($email ?? ''), FILTER_SANITIZE_EMAIL);
}

function old(string $key, mixed $default = ''): mixed
{
    return $_POST[$key] ?? $default;
}
