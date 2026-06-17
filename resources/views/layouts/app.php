<?php

declare(strict_types=1);
?>
<!doctype html>
<html lang="<?= $e($locale ?? 'en') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $section('title', 'Intisari App') ?></title>
</head>
<body>
    <?= $include('partials.header') ?>
    <?= $section('content') ?>
</body>
</html>
