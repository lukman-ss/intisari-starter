<?php

declare(strict_types=1);

$extend('layouts.app');

$start('title');
echo $e('Server Error');
$end();

$start('content');
?>
<main>
    <h1><?= $e('Server Error') ?></h1>
</main>
<?php
$end();
