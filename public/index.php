<?php

declare(strict_types=1);

use Intisari\Application;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require dirname(__DIR__) . '/bootstrap/app.php';

assert($app instanceof Application);

$app->loadRoutes($app->routesPath('web.php'));
$app->run();
