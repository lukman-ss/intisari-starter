<?php

declare(strict_types=1);

$extend('layouts.app');

$start('title');
echo $e($title ?? 'Welcome to IntisariPHP');
$end();

$start('content');
?>
<main>
    <h1><?= $e($heading ?? 'Welcome to IntisariPHP') ?></h1>
    <p><?= $e($description ?? 'A lightweight PHP framework built from reusable packages.') ?></p>
</main>
<?php
$end();
