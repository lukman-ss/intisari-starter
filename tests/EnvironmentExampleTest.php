<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class EnvironmentExampleTest extends TestCase
{
    public function testEnvironmentExampleExists(): void
    {
        $this->assertFileExists(dirname(__DIR__) . '/.env.example');
    }

    public function testEnvironmentExampleContainsApplicationKeys(): void
    {
        $content = file_get_contents(dirname(__DIR__) . '/.env.example');

        $this->assertIsString($content);

        foreach (['APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL', 'APP_TIMEZONE', 'APP_LOCALE'] as $key) {
            $this->assertStringContainsString($key . '=', $content);
        }
    }

    public function testEnvironmentExampleContainsDatabaseKeys(): void
    {
        $content = file_get_contents(dirname(__DIR__) . '/.env.example');

        $this->assertIsString($content);
        $this->assertStringContainsString('DB_CONNECTION=', $content);
        $this->assertStringContainsString('DB_DATABASE=', $content);
    }

    public function testGitignoreIgnoresEnvironmentFile(): void
    {
        $content = file_get_contents(dirname(__DIR__) . '/.gitignore');

        $this->assertIsString($content);
        $this->assertStringContainsString('/.env', $content);
    }
}
