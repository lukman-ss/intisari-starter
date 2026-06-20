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

    public function testAppNameDefault(): void
    {
        $oldEnvVal = $_ENV['APP_NAME'] ?? null;
        $oldServerVal = $_SERVER['APP_NAME'] ?? null;

        unset($_ENV['APP_NAME'], $_SERVER['APP_NAME']);
        try {
            $config = require dirname(__DIR__) . '/config/app.php';
            $this->assertSame('Intisari App', $config['name']);
        } finally {
            if ($oldEnvVal !== null) {
                $_ENV['APP_NAME'] = $oldEnvVal;
            }
            if ($oldServerVal !== null) {
                $_SERVER['APP_NAME'] = $oldServerVal;
            }
        }
    }

    public function testAppEnvDefault(): void
    {
        $oldEnvVal = $_ENV['APP_ENV'] ?? null;
        $oldServerVal = $_SERVER['APP_ENV'] ?? null;

        unset($_ENV['APP_ENV'], $_SERVER['APP_ENV']);
        try {
            $config = require dirname(__DIR__) . '/config/app.php';
            $this->assertSame('local', $config['env']);
        } finally {
            if ($oldEnvVal !== null) {
                $_ENV['APP_ENV'] = $oldEnvVal;
            }
            if ($oldServerVal !== null) {
                $_SERVER['APP_ENV'] = $oldServerVal;
            }
        }
    }

    public function testConfigCacheCommand(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        require dirname(__DIR__) . '/routes/console.php';

        $cacheFile = $app->storagePath('cache/config.php');

        if (is_file($cacheFile)) {
            @unlink($cacheFile);
        }

        try {
            $stream = fopen('php://memory', 'w+');
            $output = new \Lukman\Console\Output($stream, $stream);
            $input = new \Lukman\Console\Input(['intisari', 'config:cache']);

            $exitCode = $app->runConsole($input, $output);

            rewind($stream);
            $consoleOutput = stream_get_contents($stream);
            fclose($stream);

            $this->assertSame(0, $exitCode);
            $this->assertStringContainsString('Configuration cached successfully.', $consoleOutput);
            $this->assertFileExists($cacheFile);

            $cachedConfig = require $cacheFile;
            $this->assertIsArray($cachedConfig);
            $this->assertArrayHasKey('app', $cachedConfig);
            $this->assertArrayHasKey('database', $cachedConfig);

            // Test clear
            $stream2 = fopen('php://memory', 'w+');
            $output2 = new \Lukman\Console\Output($stream2, $stream2);
            $input2 = new \Lukman\Console\Input(['intisari', 'config:clear']);

            $exitCode2 = $app->runConsole($input2, $output2);

            rewind($stream2);
            $consoleOutput2 = stream_get_contents($stream2);
            fclose($stream2);

            $this->assertSame(0, $exitCode2);
            $this->assertStringContainsString('Configuration cache cleared.', $consoleOutput2);
            $this->assertFileDoesNotExist($cacheFile);

        } finally {
            if (is_file($cacheFile)) {
                @unlink($cacheFile);
            }
        }
    }
}
