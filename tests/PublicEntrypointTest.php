<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class PublicEntrypointTest extends TestCase
{
    public function testPublicIndexExists(): void
    {
        $this->assertFileExists(dirname(__DIR__) . '/public/index.php');
    }

    public function testPublicIndexRequiresBootstrap(): void
    {
        $content = file_get_contents(dirname(__DIR__) . '/public/index.php');

        $this->assertIsString($content);
        $this->assertStringContainsString("require dirname(__DIR__) . '/bootstrap/app.php'", $content);
        $this->assertStringContainsString('$app->loadRoutes($app->routesPath(\'web.php\'));', $content);
        $this->assertStringContainsString('$app->run();', $content);
    }

    public function testHtaccessRewritesToIndex(): void
    {
        $path = dirname(__DIR__) . '/public/.htaccess';
        $content = file_get_contents($path);

        $this->assertFileExists($path);
        $this->assertIsString($content);
        $this->assertStringContainsString('RewriteRule ^ index.php [L]', $content);
    }
}
