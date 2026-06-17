<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class ComposerAuditTest extends TestCase
{
    private string $root;

    /** @var array<string, mixed> */
    private array $composer;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
        $this->composer = json_decode(
            (string) file_get_contents($this->root . DIRECTORY_SEPARATOR . 'composer.json'),
            true
        );
    }

    // ----------------------------------------------------------------
    // composer.json structure
    // ----------------------------------------------------------------

    public function testComposerTypeIsProject(): void
    {
        $this->assertSame(
            'project',
            $this->composer['type'] ?? null,
            'composer.json "type" must be "project".'
        );
    }

    public function testComposerRequiresIntisari(): void
    {
        $require = $this->composer['require'] ?? [];
        $this->assertArrayHasKey(
            'lukman-ss/intisari',
            $require,
            'composer.json must require "lukman-ss/intisari".'
        );
    }

    public function testComposerDoesNotRequireIndividualPackages(): void
    {
        $require = $this->composer['require'] ?? [];
        $forbidden = [
            'lukman-ss/http',
            'lukman-ss/router',
            'lukman-ss/container',
            'lukman-ss/config',
            'lukman-ss/database',
            'lukman-ss/view',
            'lukman-ss/console',
            'lukman-ss/validation',
        ];

        foreach ($forbidden as $pkg) {
            $this->assertArrayNotHasKey(
                $pkg,
                $require,
                "Starter must not require individual framework package [{$pkg}] directly — use lukman-ss/intisari instead."
            );
        }
    }

    public function testAutoloadMapContainsOnlyAppNamespace(): void
    {
        $psr4 = $this->composer['autoload']['psr-4'] ?? [];

        foreach (array_keys($psr4) as $ns) {
            $this->assertStringStartsWith(
                'App\\',
                $ns,
                "autoload psr-4 must only map App\\ namespace, found [{$ns}]."
            );
        }
    }

    public function testAutoloadDevMapContainsOnlyTestsNamespace(): void
    {
        $psr4 = $this->composer['autoload-dev']['psr-4'] ?? [];

        foreach (array_keys($psr4) as $ns) {
            $this->assertStringStartsWith(
                'Tests\\',
                $ns,
                "autoload-dev psr-4 must only map Tests\\ namespace, found [{$ns}]."
            );
        }
    }

    // ----------------------------------------------------------------
    // No framework source code in starter
    // ----------------------------------------------------------------

    public function testNoSrcDirectoryExists(): void
    {
        $this->assertDirectoryDoesNotExist(
            $this->root . DIRECTORY_SEPARATOR . 'src',
            'Starter must not contain a src/ directory (that belongs to the framework core).'
        );
    }

    public function testNoIntisariNamespaceInAppSource(): void
    {
        $violations = $this->findNamespaceIn($this->root . DIRECTORY_SEPARATOR . 'app', 'namespace Intisari');

        $this->assertEmpty(
            $violations,
            'app/ must not declare namespace Intisari\\. Found in: ' . implode(', ', $violations)
        );
    }

    public function testAllAppFilesUseAppNamespace(): void
    {
        $violations = [];

        foreach ($this->phpFiles($this->root . DIRECTORY_SEPARATOR . 'app') as $file) {
            $content = file_get_contents($file);
            // If the file declares any namespace, it must start with App\
            if (preg_match('/^namespace\s+(\S+);/m', $content, $m)) {
                if (!str_starts_with($m[1], 'App\\') && $m[1] !== 'App') {
                    $violations[] = $file . ' (namespace ' . $m[1] . ')';
                }
            }
        }

        $this->assertEmpty(
            $violations,
            'All app/ PHP files must use App\\ namespace. Violations: ' . implode(', ', $violations)
        );
    }

    public function testAllTestFilesUseTestsNamespace(): void
    {
        $violations = [];

        foreach ($this->phpFiles($this->root . DIRECTORY_SEPARATOR . 'tests') as $file) {
            $content = file_get_contents($file);
            if (preg_match('/^namespace\s+(\S+);/m', $content, $m)) {
                if (!str_starts_with($m[1], 'Tests\\') && $m[1] !== 'Tests') {
                    $violations[] = $file . ' (namespace ' . $m[1] . ')';
                }
            }
        }

        $this->assertEmpty(
            $violations,
            'All tests/ PHP files must use Tests\\ namespace. Violations: ' . implode(', ', $violations)
        );
    }

    // ----------------------------------------------------------------
    // No CMS / admin panel
    // ----------------------------------------------------------------

    public function testNoCmsOrAdminDirectory(): void
    {
        $forbidden = ['cms', 'admin', 'panel'];

        foreach ($forbidden as $name) {
            $this->assertDirectoryDoesNotExist(
                $this->root . DIRECTORY_SEPARATOR . $name,
                "Starter must not contain a [{$name}/] directory."
            );
        }
    }

    // ----------------------------------------------------------------
    // README identifies as starter
    // ----------------------------------------------------------------

    public function testReadmeIdentifiesAsStarterNotCore(): void
    {
        $readme = (string) file_get_contents($this->root . DIRECTORY_SEPARATOR . 'README.md');

        $this->assertStringContainsStringIgnoringCase(
            'starter',
            $readme,
            'README must identify this project as a starter.'
        );

        $this->assertStringNotContainsStringIgnoringCase(
            'core framework',
            $readme,
            'README must not describe this project as the core framework.'
        );
    }

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------

    /**
     * @return list<string>
     */
    private function findNamespaceIn(string $dir, string $search): array
    {
        $violations = [];
        foreach ($this->phpFiles($dir) as $file) {
            if (str_contains((string) file_get_contents($file), $search)) {
                $violations[] = $file;
            }
        }
        return $violations;
    }

    /**
     * @return list<string>
     */
    private function phpFiles(string $dir): array
    {
        if (!is_dir($dir)) {
            return [];
        }

        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getRealPath();
            }
        }

        return $files;
    }
}
