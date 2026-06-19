# Routing

Routing connects an incoming HTTP request to application code.

In IntisariPHP Starter, web routes are defined in `routes/web.php`. This file is loaded from `public/index.php` before the application runs.

```php
$app->loadRoutes($app->routesPath('web.php'));
```

Routes are registered through the starter's `$app` route methods.

## `routes/web.php`

The default `routes/web.php` file contains these routes:

```php
$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);
```

Each route has an HTTP method, a URI path, and a handler.

## Closure Route

A closure route is useful for a small response.

```php
$app->get('/health', static fn (): string => 'OK');
```

Open it in the browser:

```text
http://127.0.0.1:8000/health
```

## Controller Route

A controller route points to a controller class and method.

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
```

The controller class should use the `App\Controllers` namespace.

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

final class HomeController
{
    public function index(): string
    {
        return 'Welcome to IntisariPHP';
    }
}
```

Open it in the browser:

```text
http://127.0.0.1:8000/
```

## Common HTTP Methods

Common HTTP methods are:

- `GET` for reading pages or data.
- `POST` for creating or submitting data.
- `PUT` or `PATCH` for updating existing data.
- `DELETE` for deleting data.

The starter uses `GET` routes by default. Other methods depend on the route methods exposed by installed IntisariPHP core routing features.

## Reading a Route from the Browser

When the local server is running, the browser path maps to the route path.

```text
http://127.0.0.1:8000/status
```

Matches:

```php
$app->get('/status', [StatusController::class, 'index']);
```

## Inspect Routes

The starter provides a route list command:

```bash
php intisari route:list
```

## Troubleshooting

### Wrong Path

The browser path must match the route path.

`/status` and `/stats` are different paths.

### Wrong Controller Namespace

Controller routes must reference the correct namespace.

```php
use App\Controllers\StatusController;
```

The class file should be in `app/Controllers/`.

### Route Not Registered

Confirm the route exists in `routes/web.php`.

```php
$app->get('/status', [StatusController::class, 'index']);
```

### Server Not Running

Start the development server before opening the application in a browser.

```bash
composer serve
```

## Next Steps

Continue with [Controllers](controllers.md).
