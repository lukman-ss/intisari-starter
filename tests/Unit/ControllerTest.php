<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controllers\HomeController;
use App\Controllers\StatusController;
use PHPUnit\Framework\TestCase;

final class ControllerTest extends TestCase
{
    public function testHomeControllerInstantiatesAndReturnsString(): void
    {
        $controller = new HomeController();
        $result = $controller->index();
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    public function testStatusControllerInstantiatesAndReturnsString(): void
    {
        $controller = new StatusController();
        $result = $controller->index();
        
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }
}
