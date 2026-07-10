<?php
/**
 * --------------------------------------------------------
 * SCANME QR
 * Global Configuration
 * --------------------------------------------------------
 */

declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');

define('APP_NAME', 'SCANME QR');
define('APP_URL', 'https://scanmeqr.in');

define('DB_HOST', 'YOUR_DB_HOST');
define('DB_NAME', 'YOUR_DB_NAME');
define('DB_USER', 'YOUR_DB_USER');
define('DB_PASS', 'YOUR_DB_PASSWORD');

define('PASSWORD_COST', 12);

define('QR_TOKEN_LENGTH', 64);

define('SESSION_NAME', 'SCANME_SESSION');

define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');
