<?php

declare(strict_types=1);

namespace App\Middleware;

use Lukman\Http\MiddlewareInterface;
use Lukman\Http\Request;
use Lukman\Http\RequestHandlerInterface;
use Lukman\Http\Response;

final class ExampleMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return $handler->handle($request);
    }
}
