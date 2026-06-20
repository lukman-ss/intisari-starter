# Middleware

## What Is Middleware?

Middleware is a class that can inspect an HTTP request, delegate to the next request handler, and inspect or modify the returned response.

A middleware can also return a response without calling the next handler. Authentication is a possible middleware use case, but the starter does not implement authentication middleware.

## `app/Middleware/` Directory

Application middleware lives in `app/Middleware/` under the `App\Middleware` namespace.

```text
app/Middleware/
└── ExampleMiddleware.php
```

Middleware classes are application source and must not be directly web-accessible.

## Middleware Interface and Signature

The installed HTTP package defines `Lukman\Http\MiddlewareInterface`. Its verified method signature is:

```php
public function process(Request $request, RequestHandlerInterface $handler): Response;
```

The starter does not use a `handle($request, $next)` middleware signature.

## Configured Middleware in `config/app.php`

The starter config lists `ExampleMiddleware`:

```php
'middleware' => [
    App\Middleware\ExampleMiddleware::class,
],
```

This makes the class name available as `app.middleware` in the configuration repository. In the current starter bootstrap, that config value is not automatically passed to the runtime middleware stack.

## `ExampleMiddleware`

`app/Middleware/ExampleMiddleware.php` delegates the request and adds an `X-Intisari` header to the response:

```php
final class ExampleMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return $handler->handle($request)->header('X-Intisari', 'starter');
    }
}
```

Calling `$handler->handle($request)` continues the request pipeline. The returned response is then modified before it is returned to the caller.

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

The starter registers a working middleware generator:

```bash
php intisari make:middleware TraceMiddleware
```

The command creates `app/Middleware/TraceMiddleware.php` with the verified imports, interface, and `process()` method.

Use `--force` only when intentionally replacing an existing generated file:

```bash
php intisari make:middleware TraceMiddleware --force
```

## Runtime Registration

The installed `Intisari\Application` exposes a global middleware registration method:

```php
$app->middleware(App\Middleware\ExampleMiddleware::class);
```

Register middleware after creating the application and before handling requests. If the application uses the `app.middleware` config list, application bootstrap code must explicitly read that list and pass its entries to `$app->middleware(...)`.

Do not assume that placing a class in `app/Middleware/` or adding it to `config/app.php` activates it automatically in the current starter.

## Common Mistakes

### Using the Wrong Method Signature

Implement `process(Request $request, RequestHandlerInterface $handler): Response`. A `handle($request, $next)` method does not satisfy the installed interface.

### Missing Interface Imports

Import `MiddlewareInterface`, `Request`, `RequestHandlerInterface`, and `Response` from `Lukman\Http`.

### Forgetting to Delegate

Call `$handler->handle($request)` when the request should continue. Skip delegation only when intentionally returning an early response.

### Returning the Wrong Type

The `process()` method must return `Lukman\Http\Response`.

### Assuming Config Means Active

The current `config/app.php` list is not wired automatically by `bootstrap/app.php`. Confirm runtime registration before expecting middleware effects.

## Next

Continue to [Configuration](configuration.md).
