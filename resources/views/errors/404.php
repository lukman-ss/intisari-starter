<?php

declare(strict_types=1);

$extend('layouts.app');

$start('title');
echo $e('Page Not Found');
$end();

$start('content');
?>
<main>
    <h1><?= $e('Page Not Found') ?></h1>
</main>
<?php
$end();
