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

define('DB_HOST', 'localhost');
define('DB_NAME', 'scanmeqr');
define('DB_USER', 'YOUR_DATABASE_USERNAME');
define('DB_PASS', 'YOUR_DATABASE_PASSWORD');

define('PASSWORD_COST', 12);

define('QR_TOKEN_LENGTH', 64);

define('SESSION_NAME', 'SCANME_SESSION');

define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');
