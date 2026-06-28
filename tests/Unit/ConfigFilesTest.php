<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ConfigFilesTest extends TestCase
{
    public function testAppConfigReturnsArray(): void
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $this->assertIsArray($config);
    }

    public function testDatabaseConfigReturnsArray(): void
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $this->assertIsArray($config);
    }

    public function testSessionConfigReturnsArray(): void
    {
        $config = require dirname(__DIR__, 2) . '/config/session.php';
        $this->assertIsArray($config);
    }
}
