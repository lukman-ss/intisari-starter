<?php

declare(strict_types=1);

namespace Tests;

use App\Providers\AppServiceProvider;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public function testAppConfigReturnsArray(): void
    {
        $config = require dirname(__DIR__) . '/config/app.php';

        $this->assertIsArray($config);
    }

    public function testAppConfigRequiredKeysExist(): void
    {
        $config = require dirname(__DIR__) . '/config/app.php';

        foreach (['name', 'env', 'debug', 'url', 'timezone', 'locale', 'providers', 'middleware'] as $key) {
            $this->assertArrayHasKey($key, $config);
        }
    }

    public function testAppConfigProvidersContainAppServiceProvider(): void
    {
        $config = require dirname(__DIR__) . '/config/app.php';

        $this->assertContains(AppServiceProvider::class, $config['providers']);
    }

    public function testAppConfigTimezoneDefault(): void
    {
        $config = require dirname(__DIR__) . '/config/app.php';

        $this->assertSame('Asia/Jakarta', $config['timezone']);
    }
}
