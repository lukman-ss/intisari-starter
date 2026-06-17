<?php

declare(strict_types=1);

use Intisari\Application;

assert($app instanceof Application);

$app->command('hello', static function ($input, $output): int {
    $output->writeln('Hello Intisari');

    return 0;
}, 'Print hello message');
