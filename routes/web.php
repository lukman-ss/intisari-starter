<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\StatusController;

$app->get('/', [HomeController::class, 'index']);

$app->get('/health', static fn () => 'OK');

$app->get('/status', [StatusController::class, 'index']);
