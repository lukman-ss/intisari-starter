<?php

declare(strict_types=1);

namespace App\Middleware;

use Lukman\Http\MiddlewareInterface;
use Lukman\Http\Request;
use Lukman\Http\RequestHandlerInterface;
use Lukman\Http\Response;

/**
 * Example middleware — demonstrates the MiddlewareInterface contract.
 * Add your logic inside process() and pass to the next handler.
 */
final class ExampleMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        // Optionally inspect or modify the request here.

        $response = $handler->handle($request);

        // Optionally inspect or modify the response here.

        return $response;
    }
}
