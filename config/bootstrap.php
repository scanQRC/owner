<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SCANME Bootstrap
|--------------------------------------------------------------------------
| Single Entry Point
| Load all core components once.
|--------------------------------------------------------------------------
*/

define('APP_START', microtime(true));

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/validator.php';
require_once __DIR__ . '/logger.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/upload.php';
