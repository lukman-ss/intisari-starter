<?php

declare(strict_types=1);

namespace Tests;

use App\Controllers\HomeController;
use Intisari\Application;
use Lukman\Http\Request;
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
    }

    public function testHealthRouteReturnsOk(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $app->loadRoutes($app->routesPath('web.php'));

        $response = $app->handle(new Request('GET', '/health'));

        $this->assertSame(200, $response->status());
        $this->assertSame('OK', $response->content());
    }

    public function testHomeControllerIsValid(): void
    {
        $controller = new HomeController();

        $this->assertStringContainsString('Welcome to IntisariPHP', $controller->index());
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
