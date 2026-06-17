<?php

declare(strict_types=1);

namespace Tests;

use Intisari\Application;
use PHPUnit\Framework\TestCase;

final class ConsoleEntrypointRootTest extends TestCase
{
    public function testRootIntisariExists(): void
    {
        $this->assertFileExists(dirname(__DIR__) . '/intisari');
    }

    public function testRootIntisariHasShebang(): void
    {
        $content = file_get_contents(dirname(__DIR__) . '/intisari');
        $this->assertStringStartsWith('#!/usr/bin/env php', $content);
    }

    public function testRootIntisariRequiresVendorAutoload(): void
    {
        $content = file_get_contents(dirname(__DIR__) . '/intisari');
        $this->assertStringContainsString('vendor/autoload.php', $content);
    }

    public function testRootIntisariLoadsBootstrapApp(): void
    {
        $content = file_get_contents(dirname(__DIR__) . '/intisari');
        $this->assertStringContainsString('bootstrap/app.php', $content);
    }

    public function testComposerScriptsAreUpdated(): void
    {
        $composerJsonPath = dirname(__DIR__) . '/composer.json';
        $this->assertFileExists($composerJsonPath);
        
        $data = json_decode(file_get_contents($composerJsonPath), true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('scripts', $data);
        
        $scripts = $data['scripts'];
        $this->assertArrayHasKey('console', $scripts);
        $this->assertSame('php intisari', $scripts['console']);
        
        $this->assertArrayHasKey('serve', $scripts);
        $this->assertSame('php intisari serve', $scripts['serve']);
    }

    public function testConsoleRoutesCanBeIncludedAfterBootstrap(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $this->assertInstanceOf(Application::class, $app);

        // Include routes/console.php
        require dirname(__DIR__) . '/routes/console.php';

        $this->assertTrue($app->console()->registry()->has('hello'));
        $this->assertTrue($app->console()->registry()->has('serve'));
    }

    public function testHelloCommandExecutes(): void
    {
        $output = [];
        $exitCode = -1;
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $command = sprintf('%s %s hello', escapeshellarg($php), escapeshellarg(dirname(__DIR__) . '/intisari'));
        
        // Ensure APP_ENV is testing
        putenv('APP_ENV=testing');
        exec($command, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Hello Intisari', $outputStr);
    }

    public function testServeCommandDefault(): void
    {
        $output = [];
        $exitCode = -1;
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $command = sprintf('%s %s serve', escapeshellarg($php), escapeshellarg(dirname(__DIR__) . '/intisari'));
        
        putenv('APP_ENV=testing');
        exec($command, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Intisari development server started', $outputStr);
        $this->assertStringContainsString('http://127.0.0.1:8000', $outputStr);
        $this->assertStringContainsString('Command preview: php -S 127.0.0.1:8000 -t public', $outputStr);
    }

    public function testServeCommandWithOptions(): void
    {
        $output = [];
        $exitCode = -1;
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $command = sprintf('%s %s serve --host=0.0.0.0 --port=8080', escapeshellarg($php), escapeshellarg(dirname(__DIR__) . '/intisari'));
        
        putenv('APP_ENV=testing');
        exec($command, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Intisari development server started', $outputStr);
        $this->assertStringContainsString('http://0.0.0.0:8080', $outputStr);
        $this->assertStringContainsString('Command preview: php -S 0.0.0.0:8080 -t public', $outputStr);
    }

    public function testRouteListCommandExecutes(): void
    {
        $output = [];
        $exitCode = -1;
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $command = sprintf('%s %s route:list', escapeshellarg($php), escapeshellarg(dirname(__DIR__) . '/intisari'));
        
        exec($command, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Method', $outputStr);
        $this->assertStringContainsString('URI', $outputStr);
        $this->assertStringContainsString('Name', $outputStr);
        $this->assertStringContainsString('Handler', $outputStr);
    }

    public function testRouteListCommandContainsRoutes(): void
    {
        $output = [];
        $exitCode = -1;
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $command = sprintf('%s %s route:list', escapeshellarg($php), escapeshellarg(dirname(__DIR__) . '/intisari'));
        
        exec($command, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        // /health or /status or / should be visible in URI column
        $this->assertStringContainsString('/health', $outputStr);
        $this->assertStringContainsString('/status', $outputStr);
    }

    public function testRouteListCommandDoesNotCrashWhenEmpty(): void
    {
        $webPath = dirname(__DIR__) . '/routes/web.php';
        $bakPath = dirname(__DIR__) . '/routes/web.php.bak';
        
        $this->assertFileExists($webPath);
        rename($webPath, $bakPath);
        
        try {
            $output = [];
            $exitCode = -1;
            $php = PHP_BINARY ? PHP_BINARY : 'php';
            $command = sprintf('%s %s route:list', escapeshellarg($php), escapeshellarg(dirname(__DIR__) . '/intisari'));
            
            exec($command, $output, $exitCode);
            
            $outputStr = implode("\n", $output);
            
            $this->assertSame(0, $exitCode);
            $this->assertStringContainsString('Method', $outputStr);
            $this->assertStringContainsString('URI', $outputStr);
            $this->assertStringContainsString('Name', $outputStr);
            $this->assertStringContainsString('Handler', $outputStr);
        } finally {
            rename($bakPath, $webPath);
        }
    }

    public function testConfigCacheAndClear(): void
    {
        $cacheFile = dirname(__DIR__) . '/storage/cache/config.php';
        
        if (is_file($cacheFile)) {
            unlink($cacheFile);
        }
        
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $entrypoint = dirname(__DIR__) . '/intisari';
        
        // 1. Run config:cache
        $output = [];
        $exitCode = -1;
        $command = sprintf('%s %s config:cache', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($command, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Configuration cached successfully.', $outputStr);
        $this->assertFileExists($cacheFile);
        
        // Test idempotency of config:cache
        $output2 = [];
        $exitCode2 = -1;
        exec($command, $output2, $exitCode2);
        
        $outputStr2 = implode("\n", $output2);
        $this->assertSame(0, $exitCode2);
        $this->assertStringContainsString('Configuration cached successfully.', $outputStr2);
        $this->assertFileExists($cacheFile);
        
        // 2. Run config:clear
        $output3 = [];
        $exitCode3 = -1;
        $commandClear = sprintf('%s %s config:clear', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($commandClear, $output3, $exitCode3);
        
        $outputStr3 = implode("\n", $output3);
        $this->assertSame(0, $exitCode3);
        $this->assertStringContainsString('Configuration cache cleared.', $outputStr3);
        $this->assertFileDoesNotExist($cacheFile);
        
        // Test idempotency of config:clear
        $output4 = [];
        $exitCode4 = -1;
        exec($commandClear, $output4, $exitCode4);
        
        $outputStr4 = implode("\n", $output4);
        $this->assertSame(0, $exitCode4);
        $this->assertStringContainsString('Configuration cache cleared.', $outputStr4);
        $this->assertFileDoesNotExist($cacheFile);
    }

    public function testConfigCacheDoesNotFatalWhenConfigDirIsEmpty(): void
    {
        $configPath = dirname(__DIR__) . '/config';
        $bakPath = dirname(__DIR__) . '/config_bak';
        
        $this->assertDirectoryExists($configPath);
        rename($configPath, $bakPath);
        mkdir($configPath);
        
        try {
            $output = [];
            $exitCode = -1;
            $php = PHP_BINARY ? PHP_BINARY : 'php';
            $command = sprintf('%s %s config:cache', escapeshellarg($php), escapeshellarg(dirname(__DIR__) . '/intisari'));
            
            exec($command, $output, $exitCode);
            
            $outputStr = implode("\n", $output);
            $this->assertSame(0, $exitCode);
            $this->assertStringContainsString('Configuration cached successfully.', $outputStr);
        } finally {
            rmdir($configPath);
            rename($bakPath, $configPath);
        }
    }
}
