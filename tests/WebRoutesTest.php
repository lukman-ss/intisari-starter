<?php

declare(strict_types=1);

namespace Tests;

use App\Controllers\HomeController;
use App\Controllers\StatusController;
use Intisari\Application;
use Lukman\Http\Request;
use Lukman\Console\Input;
use Lukman\Console\Output;
use PHPUnit\Framework\TestCase;

final class WebRoutesTest extends TestCase
{
    public function testWebRoutesFileCanBeIncludedWithAppAndRouterVariables(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $router = $app->router();

        require dirname(__DIR__) . '/routes/web.php';

        $routes = $router->routes()->all();

        $this->assertTrue($this->hasRoute($routes, 'GET', '/'));
        $this->assertTrue($this->hasRoute($routes, 'GET', '/health'));
        $this->assertTrue($this->hasRoute($routes, 'GET', '/status'));
    }

    public function testDefaultRoutesAreRegistered(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $app->loadRoutes($app->routesPath('web.php'));

        $routes = $app->router()->routes()->all();

        $this->assertTrue($this->hasRoute($routes, 'GET', '/'));
        $this->assertTrue($this->hasRoute($routes, 'GET', '/health'));
        $this->assertTrue($this->hasRoute($routes, 'GET', '/status'));
    }

    public function testHomeRouteReturnsOkAndWelcomeText(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $app->loadRoutes($app->routesPath('web.php'));

        $response = $app->handle(new Request('GET', '/'));

        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('Welcome to IntisariPHP', $response->content());
    }

    public function testHealthRouteReturnsOk(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $app->loadRoutes($app->routesPath('web.php'));

        $response = $app->handle(new Request('GET', '/health'));

        $this->assertSame(200, $response->status());
        $this->assertSame('OK', $response->content());
    }

    public function testStatusRouteReturnsOkAndJsonStatus(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $app->loadRoutes($app->routesPath('web.php'));

        $response = $app->handle(new Request('GET', '/status'));

        $this->assertSame(200, $response->status());
        $decoded = json_decode($response->content(), true);
        $this->assertIsArray($decoded);
        $this->assertSame('ok', $decoded['status'] ?? null);
    }

    public function testHomeControllerIsValid(): void
    {
        $controller = new HomeController();

        $this->assertStringContainsString('Welcome to IntisariPHP', $controller->index());
    }

    public function testRouteListCommandWorksAndOutputsDefaultRoutes(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        require dirname(__DIR__) . '/routes/console.php';

        $stream = fopen('php://memory', 'w+');
        $output = new Output($stream, $stream);
        $input = new Input(['intisari', 'route:list']);

        $exitCode = $app->runConsole($input, $output);

        rewind($stream);
        $consoleOutput = stream_get_contents($stream);
        fclose($stream);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Method', $consoleOutput);
        $this->assertStringContainsString('URI', $consoleOutput);
        $this->assertStringContainsString('Handler', $consoleOutput);
        $this->assertStringContainsString('/', $consoleOutput);
        $this->assertStringContainsString('/health', $consoleOutput);
        $this->assertStringContainsString('/status', $consoleOutput);
    }

    /**
     * @param array<int, object> $routes
     */
    private function hasRoute(array $routes, string $method, string $path): bool
    {
        foreach ($routes as $route) {
            if (method_exists($route, 'methods') && method_exists($route, 'path')) {
                if (in_array($method, $route->methods(), true) && $route->path() === $path) {
                    return true;
                }
            }
        }

        return false;
    }
}
