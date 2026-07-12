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
define('APP_URL', 'https://scanme.page.gd');

define('DB_HOST', 'sql213.infinityfree.com');
define('DB_NAME', 'if0_42344499_scanme');
define('DB_USER', 'if0_42344499');
define('DB_PASS', 'scanme555');

define('PASSWORD_COST', 12);

define('QR_TOKEN_LENGTH', 64);

define('SESSION_NAME', 'SCANME_SESSION');

define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');
