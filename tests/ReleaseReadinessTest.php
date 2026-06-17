<?php

declare(strict_types=1);

namespace Tests;

use Intisari\Application;
use Lukman\Http\Request;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Phase 20 — Release Readiness Audit
 *
 * Validates all pre-release requirements for lukman-ss/intisari-starter.
 */
class ReleaseReadinessTest extends TestCase
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
    // composer validate requirements
    // ----------------------------------------------------------------

    public function testComposerJsonIsValid(): void
    {
        $this->assertNotEmpty($this->composer, 'composer.json must be valid JSON.');
        $this->assertArrayHasKey('name', $this->composer);
        $this->assertArrayHasKey('require', $this->composer);
    }

    public function testComposerTypeIsProject(): void
    {
        $this->assertSame('project', $this->composer['type'] ?? null);
    }

    public function testComposerRequiresIntisariOnly(): void
    {
        $require = $this->composer['require'] ?? [];
        $this->assertArrayHasKey('lukman-ss/intisari', $require);

        // Must not require sub-packages individually
        $subPackages = [
            'lukman-ss/http', 'lukman-ss/router', 'lukman-ss/container',
            'lukman-ss/config', 'lukman-ss/database', 'lukman-ss/view',
            'lukman-ss/console', 'lukman-ss/validation',
        ];
        foreach ($subPackages as $pkg) {
            $this->assertArrayNotHasKey($pkg, $require, "Must not require [{$pkg}] directly.");
        }
    }

    // ----------------------------------------------------------------
    // .env / .env.example
    // ----------------------------------------------------------------

    public function testEnvExampleExists(): void
    {
        $this->assertFileExists(
            $this->root . '/.env.example',
            '.env.example must exist for composer create-project friendliness.'
        );
    }

    public function testDotEnvIsNotTrackedOrPresent(): void
    {
        // .env should be gitignored — we verify the .gitignore rule exists
        $gitignore = (string) file_get_contents($this->root . '/.gitignore');
        $this->assertStringContainsString(
            '/.env',
            $gitignore,
            '.env must be listed in .gitignore.'
        );
    }

    // ----------------------------------------------------------------
    // .gitignore coverage
    // ----------------------------------------------------------------

    public function testVendorIsGitignored(): void
    {
        $gitignore = (string) file_get_contents($this->root . '/.gitignore');
        $this->assertStringContainsString('/vendor/', $gitignore);
    }

    public function testStorageRuntimeIsGitignored(): void
    {
        $gitignore = (string) file_get_contents($this->root . '/.gitignore');
        $this->assertStringContainsString('/storage/cache/*', $gitignore);
        $this->assertStringContainsString('/storage/logs/*', $gitignore);
    }

    public function testPhpunitCacheIsGitignored(): void
    {
        $gitignore = (string) file_get_contents($this->root . '/.gitignore');
        // Either the directory or the result cache file should be ignored
        $hasCache = str_contains($gitignore, '/.phpunit.cache/')
            || str_contains($gitignore, '/.phpunit.result.cache');
        $this->assertTrue($hasCache, 'PHPUnit cache artifacts must be gitignored.');
    }

    public function testGitkeepFilesAreTracked(): void
    {
        $gitignore = (string) file_get_contents($this->root . '/.gitignore');
        $this->assertStringContainsString('!/storage/cache/.gitkeep', $gitignore);
        $this->assertStringContainsString('!/storage/logs/.gitkeep', $gitignore);
    }

    // ----------------------------------------------------------------
    // public/index.php as front controller
    // ----------------------------------------------------------------

    public function testFrontControllerExists(): void
    {
        $this->assertFileExists($this->root . '/public/index.php');
    }

    public function testFrontControllerBootstrapsApp(): void
    {
        $content = (string) file_get_contents($this->root . '/public/index.php');
        $this->assertStringContainsString("require dirname(__DIR__) . '/vendor/autoload.php'", $content);
        $this->assertStringContainsString("require dirname(__DIR__) . '/bootstrap/app.php'", $content);
        $this->assertStringContainsString('$app->run()', $content);
    }

    // ----------------------------------------------------------------
    // bootstrap/app.php returns Application
    // ----------------------------------------------------------------

    public function testBootstrapReturnsApplicationInstance(): void
    {
        $app = require $this->root . '/bootstrap/app.php';
        $this->assertInstanceOf(Application::class, $app);
    }

    public function testBootstrapLoadsConfiguration(): void
    {
        $app = require $this->root . '/bootstrap/app.php';
        // config should be bound after bootstrap
        $this->assertTrue(method_exists($app, 'config'));
        $this->assertNotNull($app->config());
    }

    // ----------------------------------------------------------------
    // HTTP stack works end-to-end
    // ----------------------------------------------------------------

    public function testAppHandlesRequest(): void
    {
        $app = require $this->root . '/bootstrap/app.php';
        $app->loadRoutes($app->routesPath('web.php'));

        $response = $app->handle(new Request('GET', '/health'));
        $this->assertSame(200, $response->status());
    }

    public function testStatusEndpointWorks(): void
    {
        $app = require $this->root . '/bootstrap/app.php';
        $app->loadRoutes($app->routesPath('web.php'));

        $response = $app->handle(new Request('GET', '/status'));
        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('ok', $response->content());
    }

    // ----------------------------------------------------------------
    // No framework core source in starter
    // ----------------------------------------------------------------

    public function testNoSrcDirectory(): void
    {
        $this->assertDirectoryDoesNotExist($this->root . '/src');
    }

    public function testNoIntisariNamespaceInAppSource(): void
    {
        $violations = [];
        foreach ($this->phpFiles($this->root . '/app') as $path) {
            if (str_contains((string) file_get_contents($path), 'namespace Intisari')) {
                $violations[] = $path;
            }
        }
        $this->assertEmpty($violations, 'app/ must not declare namespace Intisari\\.');
    }

    // ----------------------------------------------------------------
    // No CMS / admin
    // ----------------------------------------------------------------

    public function testNoCmsOrAdminDirectory(): void
    {
        foreach (['cms', 'admin', 'panel'] as $dir) {
            $this->assertDirectoryDoesNotExist($this->root . '/' . $dir);
        }
    }

    // ----------------------------------------------------------------
    // README correctness
    // ----------------------------------------------------------------

    public function testReadmeExistsAndMentionsStarter(): void
    {
        $readme = (string) file_get_contents($this->root . '/README.md');
        $this->assertStringContainsStringIgnoringCase('starter', $readme);
    }

    public function testReadmeMentionsCorePackage(): void
    {
        $readme = (string) file_get_contents($this->root . '/README.md');
        $this->assertStringContainsString('lukman-ss/intisari', $readme);
    }

    public function testReadmeHasEssentialCommands(): void
    {
        $readme = (string) file_get_contents($this->root . '/README.md');
        $this->assertStringContainsString('composer create-project lukman-ss/intisari-starter', $readme);
        $this->assertStringContainsString('cp .env.example .env', $readme);
        $this->assertStringContainsString('composer serve', $readme);
        $this->assertStringContainsString('composer test', $readme);
    }

    // ----------------------------------------------------------------
    // composer create-project friendliness
    // ----------------------------------------------------------------

    public function testComposerScriptsHaveServeAndTest(): void
    {
        $scripts = $this->composer['scripts'] ?? [];
        $this->assertArrayHasKey('test', $scripts, 'composer.json must define "test" script.');
        $this->assertArrayHasKey('serve', $scripts, 'composer.json must define "serve" script.');
    }

    public function testComposerMinimumStabilityIsStable(): void
    {
        $stability = $this->composer['minimum-stability'] ?? 'stable';
        $this->assertSame('stable', $stability, 'minimum-stability must be "stable" for Packagist release.');
    }

    // ----------------------------------------------------------------
    // Helper
    // ----------------------------------------------------------------

    /** @return list<string> */
    private function phpFiles(string $dir): array
    {
        if (!is_dir($dir)) {
            return [];
        }
        $files = [];
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        /** @var SplFileInfo $file */
        foreach ($it as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getRealPath();
            }
        }
        return $files;
    }
}
