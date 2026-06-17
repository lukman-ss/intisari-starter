<?php

declare(strict_types=1);

namespace Tests;

use App\Providers\AppServiceProvider;
use Intisari\Application;
use PHPUnit\Framework\TestCase;

final class BootstrapTest extends TestCase
{
    public function testBootstrapReturnsApplicationInstance(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';

        $this->assertInstanceOf(Application::class, $app);
    }

    public function testBootstrapUsesProjectRootBasePath(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';

        $this->assertSame(dirname(__DIR__), $app->basePath());
    }

    public function testBootstrapRegistersApplicationProvider(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';

        $providers = array_map(static fn (object $provider): string => $provider::class, $app->providers());

        $this->assertContains(AppServiceProvider::class, $providers);
    }

    public function testBootstrapDoesNotSendResponseWhenIncluded(): void
    {
        ob_start();

        try {
            $app = require dirname(__DIR__) . '/bootstrap/app.php';
            $output = ob_get_contents();
        } finally {
            ob_end_clean();
        }

        $this->assertInstanceOf(Application::class, $app);
        $this->assertSame('', $output);
    }
}
