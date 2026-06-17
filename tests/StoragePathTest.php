<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class StoragePathTest extends TestCase
{
    private string $root;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
    }

    public function testRequiredStorageFoldersExist(): void
    {
        foreach ($this->storagePaths() as $path) {
            $this->assertDirectoryExists($this->root . '/' . $path);
        }
    }

    public function testRequiredStorageGitkeepFilesExist(): void
    {
        foreach ($this->storagePaths() as $path) {
            $this->assertFileExists($this->root . '/' . $path . '/.gitkeep');
        }
    }

    public function testStorageGitignoreKeepsGitkeepFiles(): void
    {
        $gitignore = (string) file_get_contents($this->root . '/.gitignore');

        $this->assertStringContainsString('/storage/cache/*', $gitignore);
        $this->assertStringContainsString('/storage/logs/*', $gitignore);
        $this->assertStringContainsString('/storage/framework/*', $gitignore);

        foreach ($this->storagePaths() as $path) {
            $this->assertStringContainsString('!/' . $path . '/.gitkeep', $gitignore);
        }
    }

    /**
     * @return list<string>
     */
    private function storagePaths(): array
    {
        return [
            'storage/cache',
            'storage/logs',
            'storage/framework',
            'storage/framework/views',
            'storage/framework/cache',
        ];
    }
}
