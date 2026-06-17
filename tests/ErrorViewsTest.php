<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ErrorViewsTest extends TestCase
{
    public function test404ViewExists(): void
    {
        $this->assertFileExists(dirname(__DIR__) . '/resources/views/errors/404.php');
    }

    public function test500ViewExists(): void
    {
        $this->assertFileExists(dirname(__DIR__) . '/resources/views/errors/500.php');
    }

    public function testErrorViewsCanRender(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';

        $notFound = $app->render('errors.404');
        $serverError = $app->render('errors.500');

        $this->assertStringContainsString('Page Not Found', $notFound);
        $this->assertStringContainsString('Server Error', $serverError);
    }
}
