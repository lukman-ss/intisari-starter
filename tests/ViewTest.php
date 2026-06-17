<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ViewTest extends TestCase
{
    public function testDefaultViewFilesExist(): void
    {
        $root = dirname(__DIR__);

        $this->assertFileExists($root . '/resources/views/home.php');
        $this->assertFileExists($root . '/resources/views/layouts/app.php');
        $this->assertFileExists($root . '/resources/views/partials/header.php');
    }

    public function testHomeViewCanRender(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';

        $output = $app->render('home');

        $this->assertStringContainsString('Welcome to IntisariPHP', $output);
        $this->assertStringContainsString('A lightweight PHP framework built from reusable packages.', $output);
    }
}
