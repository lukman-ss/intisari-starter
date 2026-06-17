<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class DatabaseConfigTest extends TestCase
{
    public function testDatabaseConfigReturnsArray(): void
    {
        $config = require dirname(__DIR__) . '/config/database.php';

        $this->assertIsArray($config);
    }

    public function testSqliteConnectionExists(): void
    {
        $config = require dirname(__DIR__) . '/config/database.php';

        $this->assertArrayHasKey('sqlite', $config['connections']);
    }

    public function testMysqlConnectionExists(): void
    {
        $config = require dirname(__DIR__) . '/config/database.php';

        $this->assertArrayHasKey('mysql', $config['connections']);
    }

    public function testPgsqlConnectionExists(): void
    {
        $config = require dirname(__DIR__) . '/config/database.php';

        $this->assertArrayHasKey('pgsql', $config['connections']);
    }

    public function testDatabaseFolderExists(): void
    {
        $this->assertDirectoryExists(dirname(__DIR__) . '/database');
    }
}
