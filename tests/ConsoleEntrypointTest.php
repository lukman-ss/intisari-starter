<?php

declare(strict_types=1);

namespace Tests;

use Intisari\Application;
use Lukman\Console\Input;
use Lukman\Console\Output;
use PHPUnit\Framework\TestCase;

final class ConsoleEntrypointTest extends TestCase
{
    public function testBinFileExists(): void
    {
        $this->assertFileExists(dirname(__DIR__) . '/bin/intisari');
    }

    public function testConsoleRoutesCanBeIncluded(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';

        require dirname(__DIR__) . '/routes/console.php';

        $this->assertInstanceOf(Application::class, $app);
        $this->assertTrue($app->console()->registry()->has('hello'));
    }

    public function testHelloCommandCanRun(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        require dirname(__DIR__) . '/routes/console.php';

        $stream = fopen('php://memory', 'w+');
        $output = new Output($stream, $stream);

        $exitCode = $app->runConsole(new Input(['intisari', 'hello']), $output);

        rewind($stream);
        $content = stream_get_contents($stream);
        fclose($stream);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Hello Intisari', $content);
    }
}
