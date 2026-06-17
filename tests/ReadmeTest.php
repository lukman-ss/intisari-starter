<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class ReadmeTest extends TestCase
{
    private string $readmePath;
    private string $content;

    protected function setUp(): void
    {
        $this->readmePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'README.md';
        $this->content = (string) file_get_contents($this->readmePath);
    }

    public function testReadmeExists(): void
    {
        $this->assertFileExists($this->readmePath, 'README.md must exist in the project root.');
    }

    public function testReadmeContainsProjectName(): void
    {
        $this->assertStringContainsString(
            'IntisariPHP Starter',
            $this->content,
            'README must contain the project name "IntisariPHP Starter".'
        );
    }

    public function testReadmeContainsCreateProjectCommand(): void
    {
        $this->assertStringContainsString(
            'composer create-project lukman-ss/intisari-starter my-app',
            $this->content,
            'README must contain the "composer create-project" install command.'
        );
    }

    public function testReadmeContainsImportantLocalDevelopmentCommands(): void
    {
        foreach ([
            'composer create-project lukman-ss/intisari-starter my-app',
            'cp .env.example .env',
            'composer serve',
            'composer test',
        ] as $command) {
            $this->assertStringContainsString(
                $command,
                $this->content,
                "README must contain [{$command}]."
            );
        }
    }

    public function testReadmeContainsCopyEnvCommand(): void
    {
        $this->assertStringContainsString(
            'cp .env.example .env',
            $this->content,
            'README must contain the "cp .env.example .env" command.'
        );
    }

    public function testReadmeContainsServeCommand(): void
    {
        $this->assertStringContainsString(
            'composer serve',
            $this->content,
            'README must contain the "composer serve" command.'
        );
    }

    public function testReadmeContainsTestCommand(): void
    {
        $this->assertStringContainsString(
            'composer test',
            $this->content,
            'README must contain the "composer test" command.'
        );
    }

    public function testReadmeContainsFolderStructure(): void
    {
        // At minimum the key folders should be mentioned
        $folders = ['app/', 'config/', 'public/', 'routes/', 'storage/', 'tests/'];

        foreach ($folders as $folder) {
            $this->assertStringContainsString(
                $folder,
                $this->content,
                "README folder structure must mention [{$folder}]."
            );
        }
    }

    public function testReadmeContainsCorePackageLink(): void
    {
        $this->assertStringContainsString(
            'lukman-ss/intisari',
            $this->content,
            'README must reference the core package "lukman-ss/intisari".'
        );
    }
}
