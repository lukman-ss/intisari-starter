<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class ConfigFilesTest extends TestCase
{
    public function testAppConfigReturnsArray(): void
    {
        $config = require __DIR__ . '/../../config/app.php';
        $this->assertIsArray($config);
    }

    public function testDatabaseConfigReturnsArray(): void
    {
        $config = require __DIR__ . '/../../config/database.php';
        $this->assertIsArray($config);
    }

    public function testSessionConfigReturnsArray(): void
    {
        $config = require __DIR__ . '/../../config/session.php';
        $this->assertIsArray($config);
    }
}
