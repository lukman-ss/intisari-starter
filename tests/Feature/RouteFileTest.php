<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class RouteFileTest extends TestCase
{
    public function testRouteFileContent(): void
    {
        $path = dirname(__DIR__, 2) . '/routes/web.php';
        $this->assertFileExists($path);
        
        $content = file_get_contents($path);
        $this->assertStringStartsWith('<?php', ltrim($content));
        $this->assertStringContainsString('HomeController', $content);
        $this->assertStringContainsString('StatusController', $content);
    }
}
