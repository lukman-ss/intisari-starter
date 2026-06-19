# Application Overview

IntisariPHP Starter uses a simple application architecture built around a front controller, route files, controllers, views, configuration, runtime storage, and tests.

The starter keeps IntisariPHP core behavior in the installed `lukman-ss/intisari` package and keeps application code in this project.

## Front Controller

The web entry point is `public/index.php`.

It loads Composer autoloading, gets the configured application instance from `bootstrap/app.php`, loads web routes, and runs the application.

```php
require dirname(__DIR__) . '/vendor/autoload.php';

$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->loadRoutes($app->routesPath('web.php'));
$app->run();
```

## Routes

Routes are defined in `routes/web.php` for HTTP requests and `routes/console.php` for console commands.

```php
$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
```

## Controllers

Controllers live in `app/Controllers/`. They keep request handling code separate from route definitions.

The starter includes `HomeController` and `StatusController`.

## Views

Views live in `resources/views/`. The default home page uses PHP view templates and a layout file.

View rendering depends on the installed IntisariPHP core view features.

## Config

Configuration files live in `config/` and return PHP arrays. The starter includes application, database, and session configuration files.

Environment values are loaded from `.env` when the file exists.

## Storage

Runtime files belong in `storage/`. The starter includes directories for cache, logs, and framework runtime files.

## Tests

Tests live in `tests/` and run through PHPUnit.

```bash
composer test
```

## Next Steps

Continue with [Application Structure](application-structure.md).
