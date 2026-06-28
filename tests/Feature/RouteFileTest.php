<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

final class RouteFileTest extends TestCase
{
    private string $routesPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routesPath = __DIR__ . '/../../routes/web.php';
    }

    public function testRoutesFileExists(): void
    {
        $this->assertFileExists($this->routesPath);
    }

    public function testRoutesFileStartsWithPhpTag(): void
    {
        $content = file_get_contents($this->routesPath);
        $this->assertStringStartsWith('<?php', ltrim($content));
    }

    public function testRoutesFileContainsHomeController(): void
    {
        $content = file_get_contents($this->routesPath);
        $this->assertStringContainsString('HomeController', $content);
    }

    public function testRoutesFileContainsStatusController(): void
    {
        $content = file_get_contents($this->routesPath);
        $this->assertStringContainsString('StatusController', $content);
    }
}
