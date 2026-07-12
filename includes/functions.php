<?php

declare(strict_types=1);

/**
 * --------------------------------------------------------
 * SCANME QR
 * Common Helper Functions
 * --------------------------------------------------------
 */

/**
 * Escape HTML Output
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Trim & Clean Input
 */
function cleanInput(?string $value): string
{
    return trim((string)$value);
}

/**
 * Validate Email
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate Mobile Number (India)
 */
function isValidMobile(string $mobile): bool
{
    return (bool) preg_match('/^[6-9][0-9]{9}$/', $mobile);
}

/**
 * Generate Secure Random Token
 */
function generateToken(int $length = 64): string
{
    return bin2hex(random_bytes($length / 2));
}

/**
 * Generate Vehicle UVID
 */
function generateUVID(): string
{
    return strtoupper(bin2hex(random_bytes(8)));
}

/**
 * Password Hash
 */
function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Password Verify
 */
function verifyPassword(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}
