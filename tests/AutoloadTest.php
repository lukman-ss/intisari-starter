<?php

declare(strict_types=1);

namespace Tests;

use App\Controllers\HomeController;
use App\Providers\AppServiceProvider;
use PHPUnit\Framework\TestCase;
use Tests\Support\AutoloadFixture;

final class AutoloadTest extends TestCase
{
    public function testApplicationClassesCanAutoload(): void
    {
        $this->assertTrue(class_exists(HomeController::class));
        $this->assertTrue(class_exists(AppServiceProvider::class));
        $this->assertTrue(class_exists(AutoloadFixture::class));
    }
}
