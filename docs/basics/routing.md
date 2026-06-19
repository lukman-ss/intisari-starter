# Routing

Routing connects an incoming HTTP request to application code.

In IntisariPHP Starter, web routes are defined in `routes/web.php`. The file is loaded from `public/index.php` before the application runs.

```php
$app->loadRoutes($app->routesPath('web.php'));
```

Routes are registered against the application/router object using the starter's `$app` route methods.

## Basic Routes

The default `routes/web.php` file contains these routes:

```php
$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);
```

Each route has an HTTP method, a URI path, and a handler.

## HTTP Methods

Common HTTP methods are:

- `GET` for reading pages or data.
- `POST` for creating or submitting data.
- `PUT` or `PATCH` for updating existing data.
- `DELETE` for deleting data.

The starter uses `GET` routes by default. Other methods depend on the route methods exposed by installed IntisariPHP core routing features.

## Closure Routes

Use a closure route for very small responses.

```php
$app->get('/health', static fn (): string => 'OK');
```

Closure routes are useful for simple status endpoints or temporary local checks.

## Controller Routes

Use a controller route when the request needs application logic.

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
```

The controller class should exist under the `App\Controllers` namespace.

```php
namespace App\Controllers;

final class HomeController
{
    public function index(): string
    {
        return 'Welcome to IntisariPHP';
    }
}
```

## Route Parameters

Route parameter support should be verified against installed IntisariPHP core routing features before use.

If your installed version supports parameters, follow the syntax documented by IntisariPHP core. Do not assume Laravel-style parameter syntax unless the router confirms it.

## Inspect Routes

The starter provides a route list command:

```bash
php intisari route:list
```

## Troubleshooting

### Route Not Found

Confirm the route exists in `routes/web.php` and that the requested path matches exactly.

```php
$app->get('/status', [StatusController::class, 'index']);
```

### Wrong Controller Namespace

Controller routes must reference the correct namespace.

```php
use App\Controllers\StatusController;
```

The class file should be in `app/Controllers/`.

### Server Not Restarted

If you are using the development server and changes are not visible, stop and start it again.

```bash
composer serve
```

### Typo in Path

`/status` and `/stats` are different paths. Check the browser URL, the route definition, and any links that point to the route.

## Next Steps

Continue with [Controllers](controllers.md).
