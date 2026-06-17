<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\StatusController;
use Intisari\Application;
use Lukman\Router\Router;

assert($app instanceof Application);
assert($router instanceof Router);

$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);

