# Middleware

Middleware is code that runs before or after your route handler processes a request. It wraps around the request-response cycle, allowing you to inspect, modify, or reject requests and responses.

## What Is Middleware?

Think of middleware as a series of layers that a request passes through on its way to your controller, and that a response passes through on its way back to the browser.

```text
Request → Middleware 1 → Middleware 2 → Controller
                                            ↓
Response ← Middleware 1 ← Middleware 2 ← Response
```

Each middleware can:

- Inspect the incoming request
- Modify the request before passing it to the next layer
- Short-circuit the request and return a response early
- Modify the response after the controller has run

## When to Use Middleware

Middleware is ideal for cross-cutting concerns that apply to many or all routes:

| Use Case | Description |
|----------|-------------|
| Logging | Record every incoming request to a log file |
| Authentication | Check if the user is logged in before reaching the controller |
| Request filtering | Validate headers, IP addresses, or request format |
| Maintenance mode | Return a maintenance page when the application is being updated |
| CORS headers | Add cross-origin headers to responses |
| Request timing | Measure how long a request takes to process |

## Creating Middleware

Middleware classes live in `app/Middleware/` and use the `App\Middleware` namespace.

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

This is the actual `ExampleMiddleware` included with the starter. It adds a custom header to every response.

### Generate with CLI

```bash
php intisari make:middleware AuthMiddleware
```

This creates a new middleware file in `app/Middleware/`.

## Registering Middleware

Middleware is registered globally in `config/app.php`:

```php
'middleware' => [
    App\Middleware\ExampleMiddleware::class,
],
```

All registered middleware runs on every HTTP request in the order they are listed.

## Use Case Examples

### Logging Middleware

Record each request to a log file:

```php
final class LoggingMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $method = $request->method();
        $path = $request->path();
        $timestamp = date('Y-m-d H:i:s');
        
        file_put_contents(
            'storage/logs/access.log',
            "[$timestamp] $method $path\n",
            FILE_APPEND
        );
        
        return $handler->handle($request);
    }
}
```

### Authentication Check

Redirect unauthenticated users to a login page:

```php
final class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!isset($_SESSION['user_id'])) {
            return new Response('', 302, ['Location' => '/login']);
        }
        
        return $handler->handle($request);
    }
}
```

### Request Filtering

Block requests from specific IP addresses:

```php
final class IpFilterMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $blocked = ['192.168.1.100', '10.0.0.50'];
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
        
        if (in_array($clientIp, $blocked, true)) {
            return new Response('Forbidden', 403);
        }
        
        return $handler->handle($request);
    }
}
```

### Maintenance Mode

Return a maintenance page when enabled:

```php
final class MaintenanceMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (file_exists('storage/framework/down')) {
            return new Response(
                '<h1>We are currently undergoing maintenance. Please try again later.</h1>',
                503
            );
        }
        
        return $handler->handle($request);
    }
}
```

## Middleware Lifecycle

Conceptually, middleware executes in this order:

```text
1. Browser sends request
2. Middleware 1 receives request
   → Can inspect or modify request
   → Calls $handler->handle($request) to continue
3. Middleware 2 receives request
   → Can inspect or modify request
   → Calls $handler->handle($request) to continue
4. Route handler (controller or closure) executes
5. Response flows back through Middleware 2
   → Can modify response
6. Response flows back through Middleware 1
   → Can modify response
7. Browser receives response
```

A middleware can choose to:

- **Pass through** — call `$handler->handle($request)` and return its result
- **Short-circuit** — return a response without calling the next handler
- **Modify before** — change the request before calling the next handler
- **Modify after** — change the response after the next handler returns

## Limitations

The following limitations apply to the starter's middleware system:

- **Global registration only** — Middleware registered in `config/app.php` runs on every route. The starter does not document per-route middleware assignment.
- **No middleware groups** — Grouping middleware by name (e.g., `auth`, `api`) depends on IntisariPHP core features that are not configured in the starter.
- **Order depends on registration** — Middleware runs in the exact order listed in `config/app.php`. Reorder the array to change execution priority.
- **No route-level middleware** — Applying middleware to individual routes depends on IntisariPHP core router features.

For advanced middleware features (per-route assignment, middleware groups, middleware parameters), refer to the IntisariPHP core documentation.

## Next

Continue to [Configuration](configuration.md).
