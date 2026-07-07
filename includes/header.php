<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/bootstrap.php';

if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}

$pageTitle = $pageTitle ?? APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= e($pageTitle) ?></title>

<meta name="robots" content="noindex,nofollow">

<link rel="icon" href="<?= APP_URL ?>/assets/images/favicon.png">

<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">

</head>

<body>

<div class="wrapper">
