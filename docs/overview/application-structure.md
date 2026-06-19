# Application Structure

This page describes every directory in IntisariPHP Starter and what belongs there. Use this as a reference when adding new code to your application.

## `app/`

The `app/` directory contains your application-specific PHP classes. This is where you write your business logic.

### Function

Organizes application code by concern: controllers, middleware, and service providers. The `app/` namespace is configured for PSR-4 autoloading in `composer.json`.

### Subdirectories

| Directory | Purpose |
|-----------|---------|
| `app/Controllers/` | HTTP request handlers |
| `app/Middleware/` | Request/response middleware |
| `app/Providers/` | Service provider registrations |

### Files You Create Here

- Controllers: `HomeController.php`, `UserController.php`, `ApiController.php`
- Middleware: `AuthMiddleware.php`, `CorsMiddleware.php`
- Providers: `AppServiceProvider.php`, `RouteServiceProvider.php`

### Example

```php
// app/Controllers/UserController.php
namespace App\Controllers;

class UserController
{
    public function index()
    {
        return 'List of users';
    }
}
```

## `app/Controllers/`

### Function

Controllers handle HTTP requests and return responses. They group related route handlers into classes instead of using closures.

### Files You Create Here

- `HomeController.php` — home page and dashboard
- `UserController.php` — user listing, profile, settings
- `ApiController.php` — REST API endpoints

### Example

```php
// app/Controllers/HomeController.php
namespace App\Controllers;

class HomeController
{
    public function index()
    {
        return view('home');
    }
    
    public function about()
    {
        return view('about');
    }
}
```

Reference this controller in `routes/web.php`:

```php
$app->get('/', [HomeController::class, 'index']);
$app->get('/about', [HomeController::class, 'about']);
```

## `app/Middleware/`

### Function

Middleware intercepts HTTP requests before or after they reach your controller. Use middleware for authentication, logging, CORS headers, or request modification.

### Files You Create Here

- `AuthMiddleware.php` — check if user is authenticated
- `CorsMiddleware.php` — add CORS headers to responses
- `LoggingMiddleware.php` — log incoming requests

### Example

```php
// app/Middleware/AuthMiddleware.php
namespace App\Middleware;

class AuthMiddleware
{
    public function handle($request, $next)
    {
        if (!isset($_SESSION['user_id'])) {
            return redirect('/login');
        }
        
        return $next($request);
    }
}
```

Register middleware in `config/app.php`.

## `app/Providers/`

### Function

Service providers register services, bindings, and event listeners with the dependency injection container. They are loaded during application bootstrap.

### Files You Create Here

- `AppServiceProvider.php` — application-specific bindings
- `RouteServiceProvider.php` — route loading logic

### Example

```php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Intisari\ServiceProviderInterface;
use Intisari\Container;

class AppServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->singleton('logger', function () {
            return new \App\Services\Logger();
        });
    }
}
```

Register providers in `config/app.php`.

## `bootstrap/`

### Function

Contains application initialization code. The `app.php` file creates the application instance, loads environment variables from `.env`, loads configuration from `config/`, and registers service providers.

### Files Here

- `app.php` — application bootstrap (do not modify unless you understand the framework)

### Example

The bootstrap process:

```php
// bootstrap/app.php (simplified)
$app = new Application(dirname(__DIR__));
$app->loadEnvironment();
$app->loadConfiguration();
$app->registerProviders();
return $app;
```

## `config/`

### Function

PHP configuration files that return arrays. Configuration values are loaded at runtime and can be overridden by environment variables in `.env`.

### Files Here

| File | Purpose |
|------|---------|
| `config/app.php` | Application name, environment, debug, timezone, locale, providers, middleware |
| `config/database.php` | Database connections (SQLite, MySQL, PostgreSQL) |
| `config/session.php` | Session driver and lifetime |

### Example

```php
// config/app.php
return [
    'name' => $env('APP_NAME', 'IntisariPHP'),
    'env' => $env('APP_ENV', 'production'),
    'debug' => $env('APP_DEBUG', false),
    'timezone' => $env('APP_TIMEZONE', 'UTC'),
];
```

### Configuration Caching

Cache configuration for faster loading in production:

```bash
php intisari config:cache
```

Clear the cache when you change config files:

```bash
php intisari config:clear
```

## `database/`

### Function

Local database files and storage. The default SQLite database path is `database/database.sqlite`.

### Files Here

- `database.sqlite` — SQLite database file (created when you first use the database)

### Example

Configure SQLite in `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Create the database file:

```bash
touch database/database.sqlite
```

## `public/`

### Function

The web server document root. Only `public/index.php` is publicly accessible. This directory isolates application code from web-accessible files for security.

### Files Here

- `index.php` — front controller that handles all web requests
- `assets/` — static files (CSS, JavaScript, images) if you add them

### Example

```php
// public/index.php (simplified)
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$router = $app->make('router');
require __DIR__ . '/../routes/web.php';
$app->run();
```

### Web Server Configuration

Point your web server document root to `public/`:

```nginx
server {
    root /var/www/my-app/public;
    location / {
        try_files $uri /index.php?$query_string;
    }
}
```

## `resources/views/`

### Function

PHP view templates that render HTML responses. Views use the IntisariPHP core view engine with layout inheritance and escaping helpers.

### Subdirectories

| Directory | Purpose |
|-----------|---------|
| `resources/views/layouts/` | Base layouts extended by page views |
| `resources/views/partials/` | Reusable view fragments (header, footer) |
| `resources/views/errors/` | Custom error pages (404, 500) |

### Files You Create Here

- `home.php` — home page view
- `about.php` — about page view
- `users/index.php` — user listing page
- `layouts/app.php` — base layout
- `partials/header.php` — shared header

### Example

```php
<!-- resources/views/home.php -->
<?php $extend('layouts.app') ?>

<?php $start('content') ?>
<h1>Welcome to <?= $e($title) ?></h1>
<p>This is the home page.</p>
<?php $end() ?>
```

```php
<!-- resources/views/layouts/app.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?= $e($appName) ?></title>
</head>
<body>
    <?php $include('partials.header') ?>
    <main>
        <?= $section('content') ?>
    </main>
</body>
</html>
```

## `routes/`

### Function

Route definitions that map HTTP requests to handlers. Routes are loaded by `public/index.php` (web routes) and `intisari` (console commands).

### Files Here

| File | Purpose |
|------|---------|
| `routes/web.php` | HTTP routes for web pages |
| `routes/console.php` | CLI command definitions |

### Example

```php
// routes/web.php
$app->get('/', function () {
    return view('home');
});

$app->get('/users', [UserController::class, 'index']);
$app->post('/users', [UserController::class, 'store']);
```

```php
// routes/console.php
$console->command('users:list', function () {
    // List users from database
});
```

## `storage/`

### Function

Runtime files that must be writable by the application. This directory stores cache files, logs, and framework runtime data.

### Subdirectories

| Directory | Purpose |
|-----------|---------|
| `storage/cache/` | Cached data (including `config.php` from `config:cache`) |
| `storage/logs/` | Application log files |
| `storage/framework/` | Framework runtime files (sessions, compiled views, cache) |

### Permissions

Ensure these directories are writable:

```bash
chmod -R 775 storage/
```

### Example

Log files are created automatically:

```
storage/logs/app-2026-01-15.log
```

## `tests/`

### Function

PHPUnit tests organized into unit and feature tests. The `tests/` namespace is configured for PSR-4 autoloading in `composer.json`.

### Subdirectories

| Directory | Purpose |
|-----------|---------|
| `tests/Unit/` | Isolated unit tests for single classes or methods |
| `tests/Feature/` | Feature tests that exercise multiple components (HTTP, database) |

### Files You Create Here

- `tests/Unit/UserTest.php` — test user model methods
- `tests/Feature/Http/HomeTest.php` — test home page HTTP response
- `tests/TestCase.php` — base test class with `createApplication()` helper

### Example

```php
// tests/Feature/Http/HomeTest.php
namespace Tests\Feature\Http;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_page_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
```

Run tests:

```bash
composer test
```

## Other Files

| File | Purpose |
|------|---------|
| `.env.example` | Environment variable template |
| `composer.json` | Dependencies, autoloading, and scripts |
| `phpunit.xml` | PHPUnit configuration |
| `intisari` | CLI entry point |
| `bin/intisari` | Alternative CLI entry point |

## Next

Continue to [Request Lifecycle](request-lifecycle.md).
