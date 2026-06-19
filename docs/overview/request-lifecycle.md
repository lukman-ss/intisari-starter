# Request Lifecycle

The request lifecycle describes how an HTTP request reaches application code and becomes a response.

This page is a conceptual overview based on files visible in IntisariPHP Starter. Internal routing, middleware, and response details are handled by installed IntisariPHP core features.

## Lifecycle Diagram

```text
browser
  -> public/index.php
  -> bootstrap/app.php
  -> configuration
  -> routes/web.php
  -> middleware
  -> matched route
  -> controller or closure
  -> response
```

## 1. Request Enters `public/index.php`

`public/index.php` is the front controller. It is the web entry point that should be served by the web server.

```php
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->loadRoutes($app->routesPath('web.php'));
$app->run();
```

## 2. Bootstrap Runs

The front controller loads `bootstrap/app.php`. This file creates the application instance and prepares the starter application.

```php
$app = new Application(dirname(__DIR__));
```

## 3. Configuration Loads

The bootstrap file loads `.env` when present and then loads configuration from `config/`.

```php
$app->loadConfiguration($app->configPath());
```

Configuration includes application settings, middleware registration, database settings, and session settings.

## 4. Routes Load

After bootstrap, `public/index.php` loads web routes.

```php
$app->loadRoutes($app->routesPath('web.php'));
```

The starter stores web route definitions in `routes/web.php`.

## 5. Middleware Processes

Application middleware is listed in `config/app.php`.

```php
'middleware' => [
    App\Middleware\ExampleMiddleware::class,
],
```

Middleware can wrap request handling. The exact middleware pipeline is an IntisariPHP core-dependent feature.

## 6. Route Is Matched

The router matches the incoming request path and method to a registered route.

```php
$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
```

## 7. Controller or Closure Runs

The matched route runs a controller method or closure.

```php
$app->get('/status', [StatusController::class, 'index']);
```

```php
$app->get('/health', static fn (): string => 'OK');
```

## 8. Response Is Sent

The handler return value becomes the response sent to the browser.

The starter includes examples that return a string and a JSON response object:

```php
return 'Welcome to IntisariPHP';
```

```php
return Response::json(['status' => 'ok']);
```

## Next Steps

- [Routing](../basics/routing.md)
- [Controllers](../basics/controllers.md)
- [Middleware](../basics/middleware.md)
