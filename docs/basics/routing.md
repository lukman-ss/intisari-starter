# Routing

## What Is Routing?

Routing maps an HTTP method and URI to a handler. IntisariPHP Starter registers routes through the application instance with calls such as `$app->get(...)`.

## `routes/web.php`

Application HTTP routes belong in `routes/web.php`. The front controller loads this file before running the application:

```php
$app->loadRoutes($app->routesPath('web.php'));
```

Use the provided `$app` variable to register routes.

## Default Routes

The starter registers three GET routes:

```php
use App\Controllers\HomeController;
use App\Controllers\StatusController;

$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);
```

| Path | Result |
| --- | --- |
| `/` | Runs `HomeController::index` |
| `/health` | Returns `OK` |
| `/status` | Runs `StatusController::index` |

## Closure Routes

Use a closure for a small inline handler:

```php
$app->get('/health', static fn (): string => 'OK');
```

Move larger request logic to a controller.

## Controller Routes

A controller route uses an autoloadable class and public method pair:

```php
use App\Controllers\StatusController;

$app->get('/status', [StatusController::class, 'index']);
```

The class namespace, filename, and referenced method must match.

## HTTP Methods

The installed application API provides these registration methods:

```php
$app->get('/example', $handler);
$app->post('/example', $handler);
$app->put('/example', $handler);
$app->patch('/example', $handler);
$app->delete('/example', $handler);
```

Replace `$handler` with a closure or controller pair. A request matches only a route registered for the same HTTP method and path.

## Testing Routes in a Browser

Start the local server:

```bash
composer serve
```

Open these GET endpoints:

```text
http://127.0.0.1:8000/
http://127.0.0.1:8000/health
http://127.0.0.1:8000/status
```

Use an HTTP client rather than the browser address bar for non-GET methods.

## Testing Routes with `curl`

```bash
curl -i http://127.0.0.1:8000/health
curl -i http://127.0.0.1:8000/status
```

Add `-X POST`, `-X PUT`, `-X PATCH`, or `-X DELETE` when testing a route registered for that method.

## Listing Routes

List the routes loaded from `routes/web.php`:

```bash
php intisari route:list
```

Use the output to confirm the HTTP method, URI, and handler for `/`, `/health`, and `/status`.

## Common Mistakes

- Registering routes with an undefined helper instead of `$app->get(...)` or another verified method.
- Omitting the leading slash from a path.
- Sending a different HTTP method from the one registered.
- Forgetting a controller `use` statement.
- Using a controller namespace, filename, class name, or method that does not match.
- Editing another route file while `public/index.php` loads `routes/web.php`.
- Requesting a different host or port from the running development server.

## Advanced Routing Limitations

The starter examples verify direct registration with `get`, `post`, `put`, `patch`, and `delete`, using closures or controller pairs.

The installed router package also contains route parameters, constraints, names, groups, and route-specific middleware APIs. Those capabilities are package-dependent and are not used by the starter's default routes.

Verify the installed router version before adopting them. Route model binding and named middleware groups are not implemented by the starter.

## Next

Continue to [Controllers](controllers.md).
