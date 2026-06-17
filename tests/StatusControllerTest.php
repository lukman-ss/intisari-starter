<?php

declare(strict_types=1);

namespace Tests;

use App\Controllers\StatusController;
use Lukman\Http\Request;

final class StatusControllerTest extends TestCase
{
    public function testStatusRouteReturns200(): void
    {
        $app = $this->createApplication();

        $response = $app->handle(new Request('GET', '/status'));

        $this->assertSame(200, $response->status());
    }

    public function testStatusResponseContainsOk(): void
    {
        $app = $this->createApplication();

        $response = $app->handle(new Request('GET', '/status'));

        $this->assertStringContainsString('ok', $response->content());
    }

    public function testStatusResponseIsValidJson(): void
    {
        $app = $this->createApplication();

        $response = $app->handle(new Request('GET', '/status'));

        $decoded = json_decode($response->content(), true);
        $this->assertIsArray($decoded, 'Status response must be valid JSON.');
        $this->assertSame('ok', $decoded['status']);
        $this->assertArrayHasKey('app', $decoded);
        $this->assertArrayHasKey('environment', $decoded);
        $this->assertArrayHasKey('php', $decoded);
    }

    public function testStatusResponseDoesNotContainSecrets(): void
    {
        $app = $this->createApplication();

        $response = $app->handle(new Request('GET', '/status'));
        $body = $response->content();

        $forbidden = ['password', 'secret', 'key', 'token', 'DB_PASSWORD', 'APP_KEY'];

        foreach ($forbidden as $word) {
            $this->assertStringNotContainsStringIgnoringCase(
                $word,
                $body,
                "Status response must not expose sensitive keyword [{$word}]."
            );
        }
    }

    public function testStatusControllerDirectlyReturnsSomething(): void
    {
        $controller = new StatusController();
        $result = $controller->index();

        // Whether it returns a Response or a string, it should contain 'ok'
        $body = is_object($result) && method_exists($result, 'content')
            ? $result->content()
            : (string) $result;

        $this->assertStringContainsString('ok', $body);
    }

    public function testStatusRouteIsRegistered(): void
    {
        $app = $this->createApplication();
        $routes = $app->router()->routes()->all();

        $found = false;
        foreach ($routes as $route) {
            if (
                method_exists($route, 'methods') &&
                method_exists($route, 'path') &&
                in_array('GET', $route->methods(), true) &&
                $route->path() === '/status'
            ) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, 'GET /status route must be registered.');
    }
}
