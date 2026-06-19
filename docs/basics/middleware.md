# Middleware

Middleware wraps request handling logic around route execution.

In IntisariPHP Starter, middleware classes live in `app/Middleware/`. The starter includes `app/Middleware/ExampleMiddleware.php`.

Middleware is useful for logic that should run before or after a request reaches a route handler.

## When to Use Middleware

Use middleware for cross-cutting request behavior, such as:

- Logging request or response information.
- Access checks when your application provides the required core-dependent support.
- Request filtering.
- Maintenance mode checks.
- Adding common response headers.

Do not put route-specific business logic in middleware. Keep that logic in controllers or application services.

## Example Middleware

The starter example uses middleware classes from installed HTTP components:

```php
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
        return $handler->handle($request)->header('X-Intisari', 'starter');
    }
}
```

This example calls the next handler, then adds an `X-Intisari` response header.

Implementation details follow IntisariPHP core and installed HTTP package support.

## Registering Middleware

The starter registers application middleware in `config/app.php`.

```php
'middleware' => [
    App\Middleware\ExampleMiddleware::class,
],
```

The exact middleware pipeline and ordering behavior are IntisariPHP core-dependent features.

## Limitations

- Middleware registration depends on IntisariPHP core support.
- Check the actual application bootstrap and configuration before adding custom middleware.
- The starter does not document advanced middleware groups, aliases, or per-route middleware.
- Use only middleware features supported by your installed IntisariPHP core version.

## Next Steps

Continue with [Configuration](configuration.md).
