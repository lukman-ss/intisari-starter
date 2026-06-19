# Application Lifecycle

The request lifecycle describes how an HTTP request reaches application code and becomes a response.

This page is a conceptual overview based on the starter entry points. Some internal details depend on the installed IntisariPHP core package.

## Conceptual Flow

```text
Browser request
  -> public/index.php
  -> bootstrap
  -> application instance
  -> middleware
  -> route matching
  -> controller or closure
  -> response
```

## `public/index.php`

The front controller receives every web request handled by the PHP application.

It loads Composer autoloading, imports the application from `bootstrap/app.php`, loads `routes/web.php`, and runs the application.

## Bootstrap

`bootstrap/app.php` creates the `Intisari\Application` instance.

It also loads `.env` when present, loads configuration files, and registers `App\Providers\AppServiceProvider`.

```php
$app = new Application(dirname(__DIR__));
$app->loadConfiguration($app->configPath());
$app->register(AppServiceProvider::class);
```

## Application Instance

The application instance holds the configured application state and exposes methods used by the starter, such as loading configuration, loading routes, registering providers, and running the app.

## Middleware

Middleware is configured in `config/app.php`.

```php
'middleware' => [
    App\Middleware\ExampleMiddleware::class,
],
```

The exact middleware pipeline behavior depends on installed IntisariPHP core features.

## Route Matching

Routes are loaded from `routes/web.php`.

```php
$app->get('/status', [StatusController::class, 'index']);
```

IntisariPHP core matches the incoming request to a registered route.

## Controller or Closure

After a route is matched, the route handler is executed.

A handler can be a controller method:

```php
$app->get('/', [HomeController::class, 'index']);
```

Or a closure:

```php
$app->get('/health', static fn (): string => 'OK');
```

## Response

The handler result becomes the HTTP response.

The starter includes examples that return strings and a JSON response object.

```php
return Response::json(['status' => 'ok']);
```

## Next Steps

Continue with [Routing](../basics/routing.md).
