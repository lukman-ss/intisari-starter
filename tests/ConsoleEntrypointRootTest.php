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

    public function testMakeControllerCommand(): void
    {
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $entrypoint = dirname(__DIR__) . '/intisari';
        
        $userControllerPath = dirname(__DIR__) . '/app/Controllers/UserController.php';
        $productControllerPath = dirname(__DIR__) . '/app/Controllers/ProductController.php';
        
        if (is_file($userControllerPath)) {
            unlink($userControllerPath);
        }
        if (is_file($productControllerPath)) {
            unlink($productControllerPath);
        }
        
        // 1. Make controller by full name (UserController)
        $output = [];
        $exitCode = -1;
        $command = sprintf('%s %s make:controller UserController', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($command, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Controller created successfully.', $outputStr);
        $this->assertFileExists($userControllerPath);
        
        // Verify generated PHP has strict types
        $content = file_get_contents($userControllerPath);
        $this->assertStringContainsString('declare(strict_types=1);', $content);
        $this->assertStringContainsString('final class UserController', $content);
        $this->assertStringContainsString('public function index(): mixed', $content);
        
        // 2. Make controller auto suffix (Product -> ProductController)
        $output2 = [];
        $exitCode2 = -1;
        $command2 = sprintf('%s %s make:controller Product', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($command2, $output2, $exitCode2);
        
        $outputStr2 = implode("\n", $output2);
        $this->assertSame(0, $exitCode2);
        $this->assertStringContainsString('Controller created successfully.', $outputStr2);
        $this->assertFileExists($productControllerPath);
        
        $content2 = file_get_contents($productControllerPath);
        $this->assertStringContainsString('declare(strict_types=1);', $content2);
        $this->assertStringContainsString('final class ProductController', $content2);
        
        // 3. No overwrite by default
        file_put_contents($productControllerPath, 'MODIFIED');
        
        $output3 = [];
        $exitCode3 = -1;
        exec($command2, $output3, $exitCode3);
        
        $outputStr3 = implode("\n", $output3);
        $this->assertSame(0, $exitCode3);
        $this->assertStringContainsString('Controller already exists.', $outputStr3);
        $this->assertSame('MODIFIED', file_get_contents($productControllerPath));
        
        // 4. Overwrite with --force
        $output4 = [];
        $exitCode4 = -1;
        $command4 = sprintf('%s %s make:controller Product --force', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($command4, $output4, $exitCode4);
        
        $outputStr4 = implode("\n", $output4);
        $this->assertSame(0, $exitCode4);
        $this->assertStringContainsString('Controller created successfully.', $outputStr4);
        $this->assertNotSame('MODIFIED', file_get_contents($productControllerPath));
        $this->assertStringContainsString('final class ProductController', file_get_contents($productControllerPath));
        
        if (is_file($userControllerPath)) {
            unlink($userControllerPath);
        }
        if (is_file($productControllerPath)) {
            unlink($productControllerPath);
        }
    }

    public function testMakeMiddlewareCommand(): void
    {
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $entrypoint = dirname(__DIR__) . '/intisari';
        
        $authMiddlewarePath = dirname(__DIR__) . '/app/Middleware/AuthMiddleware.php';
        
        if (is_file($authMiddlewarePath)) {
            unlink($authMiddlewarePath);
        }
        
        // 1. Make middleware
        $output = [];
        $exitCode = -1;
        $command = sprintf('%s %s make:middleware AuthMiddleware', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($command, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Middleware created successfully.', $outputStr);
        $this->assertFileExists($authMiddlewarePath);
        
        // Verify generated contents
        $content = file_get_contents($authMiddlewarePath);
        $this->assertStringContainsString('declare(strict_types=1);', $content);
        $this->assertStringContainsString('namespace App\Middleware;', $content);
        $this->assertStringContainsString('implements MiddlewareInterface', $content);
        $this->assertStringContainsString('public function process(Request $request, RequestHandlerInterface $handler): Response', $content);
        
        // 2. No overwrite by default
        file_put_contents($authMiddlewarePath, 'MODIFIED');
        
        $output2 = [];
        $exitCode2 = -1;
        exec($command, $output2, $exitCode2);
        
        $outputStr2 = implode("\n", $output2);
        $this->assertSame(0, $exitCode2);
        $this->assertStringContainsString('Middleware already exists.', $outputStr2);
        $this->assertSame('MODIFIED', file_get_contents($authMiddlewarePath));
        
        // 3. Force overwrite
        $output3 = [];
        $exitCode3 = -1;
        $commandForce = sprintf('%s %s make:middleware AuthMiddleware --force', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($commandForce, $output3, $exitCode3);
        
        $outputStr3 = implode("\n", $output3);
        $this->assertSame(0, $exitCode3);
        $this->assertStringContainsString('Middleware created successfully.', $outputStr3);
        $this->assertNotSame('MODIFIED', file_get_contents($authMiddlewarePath));
        $this->assertStringContainsString('implements MiddlewareInterface', file_get_contents($authMiddlewarePath));
        
        if (is_file($authMiddlewarePath)) {
            unlink($authMiddlewarePath);
        }
    }

    public function testMakeProviderAndCommand(): void
    {
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $entrypoint = dirname(__DIR__) . '/intisari';
        
        $providerPath = dirname(__DIR__) . '/app/Providers/PaymentServiceProvider.php';
        $commandPath = dirname(__DIR__) . '/app/Commands/SendEmailCommand.php';
        
        if (is_file($providerPath)) {
            unlink($providerPath);
        }
        if (is_file($commandPath)) {
            unlink($commandPath);
        }
        
        // 1. Make provider
        $output = [];
        $exitCode = -1;
        $cmd = sprintf('%s %s make:provider PaymentServiceProvider', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($cmd, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Provider created successfully.', $outputStr);
        $this->assertFileExists($providerPath);
        
        $providerContent = file_get_contents($providerPath);
        $this->assertStringContainsString('declare(strict_types=1);', $providerContent);
        $this->assertStringContainsString('namespace App\Providers;', $providerContent);
        $this->assertStringContainsString('class PaymentServiceProvider extends ServiceProvider', $providerContent);
        
        // 2. Make command
        $output2 = [];
        $exitCode2 = -1;
        $cmd2 = sprintf('%s %s make:command SendEmailCommand', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($cmd2, $output2, $exitCode2);
        
        $outputStr2 = implode("\n", $output2);
        $this->assertSame(0, $exitCode2);
        $this->assertStringContainsString('Command created successfully.', $outputStr2);
        $this->assertFileExists($commandPath);
        
        $commandContent = file_get_contents($commandPath);
        $this->assertStringContainsString('declare(strict_types=1);', $commandContent);
        $this->assertStringContainsString('namespace App\Commands;', $commandContent);
        $this->assertStringContainsString('class SendEmailCommand extends Command', $commandContent);
        $this->assertStringContainsString("protected string \$name = 'send:email';", $commandContent);
        
        // 3. Force overwrite
        file_put_contents($commandPath, 'MODIFIED');
        
        $output3 = [];
        $exitCode3 = -1;
        $cmd3 = sprintf('%s %s make:command SendEmailCommand --force', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($cmd3, $output3, $exitCode3);
        
        $outputStr3 = implode("\n", $output3);
        $this->assertSame(0, $exitCode3);
        $this->assertStringContainsString('Command created successfully.', $outputStr3);
        $this->assertNotSame('MODIFIED', file_get_contents($commandPath));
        $this->assertStringContainsString('class SendEmailCommand extends Command', file_get_contents($commandPath));
        
        if (is_file($providerPath)) {
            unlink($providerPath);
        }
        if (is_file($commandPath)) {
            unlink($commandPath);
        }
    }

    public function testUtilityCommands(): void
    {
        $php = PHP_BINARY ? PHP_BINARY : 'php';
        $entrypoint = dirname(__DIR__) . '/intisari';
        
        // 1. Test about command
        $output = [];
        $exitCode = -1;
        $cmd = sprintf('%s %s about', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($cmd, $output, $exitCode);
        
        $outputStr = implode("\n", $output);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('IntisariPHP', $outputStr);
        $this->assertStringContainsString('Application Name', $outputStr);
        $this->assertStringContainsString('Environment', $outputStr);
        $this->assertStringContainsString('PHP Version', $outputStr);
        $this->assertStringContainsString('Base Path', $outputStr);
        
        // 2. Test env command
        $output2 = [];
        $exitCode2 = -1;
        $cmd2 = sprintf('%s %s env', escapeshellarg($php), escapeshellarg($entrypoint));
        exec($cmd2, $output2, $exitCode2);
        
        $outputStr2 = implode("\n", $output2);
        $this->assertSame(0, $exitCode2);
        $this->assertStringContainsString('Current application environment', $outputStr2);
        $this->assertStringNotContainsString('DB_PASSWORD', $outputStr2);
        $this->assertStringNotContainsString('APP_KEY', $outputStr2);
        
        // 3. Test test command in testing environment
        $output3 = [];
        $exitCode3 = -1;
        $cmd3 = sprintf('%s %s test', escapeshellarg($php), escapeshellarg($entrypoint));
        
        putenv('APP_ENV=testing');
        exec($cmd3, $output3, $exitCode3);
        
        $outputStr3 = implode("\n", $output3);
        $this->assertSame(0, $exitCode3);
        $this->assertStringContainsString('Command preview: composer test', $outputStr3);
    }
}
