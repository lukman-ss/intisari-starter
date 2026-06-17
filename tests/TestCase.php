<?php

declare(strict_types=1);

namespace Tests;

use Intisari\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function createApplication(): Application
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $app->loadRoutes($app->routesPath('web.php'));

        return $app;
    }
}
