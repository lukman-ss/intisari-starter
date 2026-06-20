<?php

declare(strict_types=1);

namespace Tests\Feature;

use Intisari\Application;
use Lukman\Console\Input;
use Lukman\Console\Output;
use Tests\TestCase;

final class GeneratorTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Define a safe temporary directory inside storage directory
        $this->tempDir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'tmp_generator_test_' . uniqid('', true);
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tempDir);
        parent::tearDown();
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }

    private function runCommand(Application $app, array $args): array
    {
        $stream = fopen('php://memory', 'w+');
        $output = new Output($stream, $stream);
        $input = new Input($args);

        $exitCode = $app->runConsole($input, $output);

        rewind($stream);
        $consoleOutput = stream_get_contents($stream);
        fclose($stream);

        return [$exitCode, $consoleOutput];
    }

    private function assertPhpSyntax(string $filePath): void
    {
        $command = sprintf('%s -l %s', escapeshellarg(PHP_BINARY), escapeshellarg($filePath));
        exec($command, $outputLines, $returnCode);
        $this->assertSame(0, $returnCode, "PHP syntax check failed for: " . $filePath . "\n" . implode("\n", $outputLines));
    }

    public function testMakeController(): void
    {
        $app = new Application($this->tempDir);
        require dirname(dirname(__DIR__)) . '/routes/console.php';

        $filePath = $this->tempDir . '/app/Controllers/TestController.php';
        $this->assertFileDoesNotExist($filePath);

        // 1. Generate Controller
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:controller', 'TestController']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Controller created successfully.', $output);
        $this->assertFileExists($filePath);

        // 2. Validate Contents & Syntax
        $content = file_get_contents($filePath);
        $this->assertStringContainsString('namespace App\Controllers;', $content);
        $this->assertStringContainsString('final class TestController', $content);
        $this->assertPhpSyntax($filePath);

        // 3. Test overwrite prevention
        file_put_contents($filePath, '// Custom changes');
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:controller', 'TestController']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Controller already exists.', $output);
        $this->assertSame('// Custom changes', file_get_contents($filePath));

        // 4. Test --force overwrite
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:controller', 'TestController', '--force']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Controller created successfully.', $output);
        $this->assertNotSame('// Custom changes', file_get_contents($filePath));
        $this->assertStringContainsString('final class TestController', file_get_contents($filePath));
        $this->assertPhpSyntax($filePath);
    }

    public function testMakeMiddleware(): void
    {
        $app = new Application($this->tempDir);
        require dirname(dirname(__DIR__)) . '/routes/console.php';

        $filePath = $this->tempDir . '/app/Middleware/TestMiddleware.php';
        $this->assertFileDoesNotExist($filePath);

        // 1. Generate Middleware
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:middleware', 'TestMiddleware']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Middleware created successfully.', $output);
        $this->assertFileExists($filePath);

        // 2. Validate Contents & Syntax
        $content = file_get_contents($filePath);
        $this->assertStringContainsString('namespace App\Middleware;', $content);
        $this->assertStringContainsString('final class TestMiddleware implements MiddlewareInterface', $content);
        $this->assertPhpSyntax($filePath);

        // 3. Test overwrite prevention
        file_put_contents($filePath, '// Custom changes');
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:middleware', 'TestMiddleware']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Middleware already exists.', $output);
        $this->assertSame('// Custom changes', file_get_contents($filePath));

        // 4. Test --force overwrite
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:middleware', 'TestMiddleware', '--force']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Middleware created successfully.', $output);
        $this->assertNotSame('// Custom changes', file_get_contents($filePath));
        $this->assertStringContainsString('final class TestMiddleware implements MiddlewareInterface', file_get_contents($filePath));
        $this->assertPhpSyntax($filePath);
    }

    public function testMakeProvider(): void
    {
        $app = new Application($this->tempDir);
        require dirname(dirname(__DIR__)) . '/routes/console.php';

        $filePath = $this->tempDir . '/app/Providers/TestServiceProvider.php';
        $this->assertFileDoesNotExist($filePath);

        // 1. Generate Provider
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:provider', 'TestServiceProvider']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Provider created successfully.', $output);
        $this->assertFileExists($filePath);

        // 2. Validate Contents & Syntax
        $content = file_get_contents($filePath);
        $this->assertStringContainsString('namespace App\Providers;', $content);
        $this->assertStringContainsString('final class TestServiceProvider extends ServiceProvider', $content);
        $this->assertPhpSyntax($filePath);

        // 3. Test overwrite prevention
        file_put_contents($filePath, '// Custom changes');
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:provider', 'TestServiceProvider']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Provider already exists.', $output);
        $this->assertSame('// Custom changes', file_get_contents($filePath));

        // 4. Test --force overwrite
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:provider', 'TestServiceProvider', '--force']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Provider created successfully.', $output);
        $this->assertNotSame('// Custom changes', file_get_contents($filePath));
        $this->assertStringContainsString('final class TestServiceProvider extends ServiceProvider', file_get_contents($filePath));
        $this->assertPhpSyntax($filePath);
    }

    public function testMakeCommand(): void
    {
        $app = new Application($this->tempDir);
        require dirname(dirname(__DIR__)) . '/routes/console.php';

        $filePath = $this->tempDir . '/app/Commands/TestCommand.php';
        $this->assertFileDoesNotExist($filePath);

        // 1. Generate Command
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:command', 'TestCommand']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Command created successfully.', $output);
        $this->assertFileExists($filePath);

        // 2. Validate Contents & Syntax
        $content = file_get_contents($filePath);
        $this->assertStringContainsString('namespace App\Commands;', $content);
        $this->assertStringContainsString('final class TestCommand extends Command', $content);
        $this->assertStringContainsString("protected string \$name = 'test';", $content);
        $this->assertPhpSyntax($filePath);

        // 3. Test overwrite prevention
        file_put_contents($filePath, '// Custom changes');
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:command', 'TestCommand']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Command already exists.', $output);
        $this->assertSame('// Custom changes', file_get_contents($filePath));

        // 4. Test --force overwrite
        [$exitCode, $output] = $this->runCommand($app, ['intisari', 'make:command', 'TestCommand', '--force']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Command created successfully.', $output);
        $this->assertNotSame('// Custom changes', file_get_contents($filePath));
        $this->assertStringContainsString('final class TestCommand extends Command', file_get_contents($filePath));
        $this->assertPhpSyntax($filePath);
    }
}
