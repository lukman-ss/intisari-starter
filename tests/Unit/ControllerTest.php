<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Controllers\HomeController;
use App\Controllers\StatusController;

class ControllerTest extends TestCase
{
    public function testHomeControllerIndexReturnsString(): void
    {
        $controller = new HomeController();
        $result = $controller->index();
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testStatusControllerIndexReturnsString(): void
    {
        $controller = new StatusController();
        $result = $controller->index();
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }
}
