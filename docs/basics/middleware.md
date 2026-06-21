# Middleware

## What Is Middleware?

Middleware can inspect an HTTP request, delegate to the next request handler, and inspect or modify the returned response. Authentication checks, request logging, and filtering are possible use cases; the starter does not implement authentication middleware.

## `app/Middleware/` Directory

Application middleware uses the `App\Middleware` namespace and lives in:

```text
app/Middleware/
`-- ExampleMiddleware.php
```

Middleware files are application source and must not be directly web-accessible.

## Existing Middleware

`ExampleMiddleware` delegates the request and adds an `X-Intisari` response header:

```php
final class ExampleMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return $handler->handle($request)->header('X-Intisari', 'starter');
    }
}
```

Calling `$handler->handle($request)` continues the pipeline.

## Creating Middleware Manually

Create `app/Middleware/TraceMiddleware.php`:

```php
<?php

declare(strict_types=1);

namespace App\Middleware;

use Lukman\Http\MiddlewareInterface;
use Lukman\Http\Request;
use Lukman\Http\RequestHandlerInterface;
use Lukman\Http\Response;

final class TraceMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return $handler->handle($request)->header('X-Trace', 'enabled');
    }
}
```

## Creating Middleware with the CLI

The verified generator creates the directory and class when needed:

```bash
php intisari make:middleware TraceMiddleware
```

It generates the correct namespace, imports, interface, and `process()` method. Use `--force` only when intentionally overwriting the file:

```bash
php intisari make:middleware TraceMiddleware --force
```

## Middleware Signature

The installed HTTP package defines `Lukman\Http\MiddlewareInterface` with this method:

```php
public function process(Request $request, RequestHandlerInterface $handler): Response;
```

`Request`, `RequestHandlerInterface`, and `Response` are also in the `Lukman\Http` namespace. A `handle($request, $next)` method does not implement this interface.

## Registration Process

`config/app.php` currently lists the example class under `middleware`:

```php
'middleware' => [
    App\Middleware\ExampleMiddleware::class,
],
```

The current `bootstrap/app.php` loads configuration but does not automatically pass this list to the runtime middleware stack. Register global middleware explicitly after creating the application and before handling requests:

```php
$app->middleware(App\Middleware\ExampleMiddleware::class);
```

To use the configured list, bootstrap code must explicitly read it and pass its entries to the verified `$app->middleware(...)` method. Merely creating a class or adding it to `config/app.php` does not activate it in the current starter.

## Common Mistakes

- Using the wrong namespace or missing `Lukman\Http` imports.
- Implementing `handle()` instead of the required `process()` signature.
- Returning something other than `Lukman\Http\Response`.
- Forgetting `$handler->handle($request)` when the request should continue.
- Assuming `config/app.php` automatically activates middleware.
- Expecting a generated class to be registered automatically.

## Limitations

- The starter includes only `ExampleMiddleware`; it does not include authentication middleware.
- Middleware listed in configuration is not automatically wired by the current bootstrap.
- The documented registration is global. This guide does not claim route-specific middleware registration.
- Early responses are possible by returning a `Response` without delegating, but application-specific authentication or filtering logic must be implemented by the developer.

## Next

Continue to [Configuration](configuration.md).
