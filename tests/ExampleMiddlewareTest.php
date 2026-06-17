<?php

declare(strict_types=1);

namespace Tests;

use App\Middleware\ExampleMiddleware;
use Lukman\Http\MiddlewareInterface;
use Lukman\Http\Request;
use Lukman\Http\RequestHandlerInterface;
use Lukman\Http\Response;
use PHPUnit\Framework\TestCase;

final class ExampleMiddlewareTest extends TestCase
{
    public function testMiddlewareClassIsValid(): void
    {
        $this->assertInstanceOf(MiddlewareInterface::class, new ExampleMiddleware());
    }

    public function testMiddlewareAddsHeader(): void
    {
        $middleware = new ExampleMiddleware();
        $handler = new class implements RequestHandlerInterface {
            public function handle(Request $request): Response
            {
                return new Response('OK');
            }
        };

        $response = $middleware->process(new Request('GET', '/'), $handler);

        $this->assertSame('starter', $response->headers()->get('X-Intisari'));
    }

    public function testMiddlewareCallsNextHandler(): void
    {
        $middleware = new ExampleMiddleware();
        $handler = new class implements RequestHandlerInterface {
            public bool $called = false;

            public function handle(Request $request): Response
            {
                $this->called = true;

                return new Response('next');
            }
        };

        $response = $middleware->process(new Request('GET', '/'), $handler);

        $this->assertTrue($handler->called);
        $this->assertSame('next', $response->content());
    }
}
