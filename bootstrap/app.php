<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use Intisari\Application;

$app = new Application(dirname(__DIR__));

if (method_exists($app, 'setAsGlobal')) {
    $app->setAsGlobal();
}

if (is_file($app->basePath('.env'))) {
    $app->loadEnvironment($app->basePath('.env'));
}

$app->loadConfiguration($app->configPath());
$app->register(AppServiceProvider::class);

return $app;
