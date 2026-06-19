# Application Structure

This page documents the actual folder structure included in IntisariPHP Starter.

Use this reference when deciding where to place application code, configuration, templates, and runtime files.

## Structure

```text
app/
  Controllers/
  Middleware/
  Providers/
bootstrap/
config/
database/
public/
resources/
  views/
routes/
storage/
tests/
.env.example
composer.json
```

## `app/`

Function: contains application-specific PHP classes.

Use it when adding code that belongs to your application rather than IntisariPHP core.

Common examples:

```text
app/Controllers/HomeController.php
app/Middleware/ExampleMiddleware.php
app/Providers/AppServiceProvider.php
```

## `app/Controllers/`

Function: contains HTTP controllers.

Use it when a route needs a controller method instead of an inline closure.

Example file:

```text
app/Controllers/StatusController.php
```

```php
$app->get('/status', [StatusController::class, 'index']);
```

## `app/Middleware/`

Function: contains application middleware.

Use it for request or response handling that should wrap route execution. Middleware behavior depends on installed IntisariPHP core features.

Example file:

```text
app/Middleware/ExampleMiddleware.php
```

## `app/Providers/`

Function: contains service providers.

Use it when registering application services or bootstrapping application-specific behavior.

Example file:

```text
app/Providers/AppServiceProvider.php
```

## `bootstrap/`

Function: contains application bootstrap files.

Use it for application startup wiring. `bootstrap/app.php` creates the application instance, loads `.env`, loads configuration, and registers the application service provider.

Example file:

```text
bootstrap/app.php
```

## `config/`

Function: contains PHP configuration files.

Use it for application, database, and session settings.

Example files:

```text
config/app.php
config/database.php
config/session.php
```

## `database/`

Function: contains local database-related files.

Use it for local database files. The starter uses this folder for the default SQLite database path when configured.

Example file:

```text
database/database.sqlite
```

The SQLite file is not created by default.

## `public/`

Function: contains the public web entry point.

Use it as the web server document root. `public/index.php` should be the only public entry point.

Example file:

```text
public/index.php
```

## `resources/views/`

Function: contains PHP view templates.

Use it for pages, layouts, partials, and error views used by the application.

Example files:

```text
resources/views/home.php
resources/views/layouts/app.php
resources/views/partials/header.php
resources/views/errors/404.php
resources/views/errors/500.php
```

## `routes/`

Function: contains route definitions.

Use `routes/web.php` for HTTP routes and `routes/console.php` for console commands.

Example files:

```text
routes/web.php
routes/console.php
```

## `storage/`

Function: contains runtime files.

Use this folder for cache, logs, and framework runtime files. The application must be able to write to relevant storage paths.

Example paths:

```text
storage/cache/
storage/logs/
storage/framework/
```

## `tests/`

Function: contains PHPUnit tests.

Use this folder when adding application tests.

Example files:

```text
tests/TestCase.php
tests/Feature/HomePageTest.php
tests/Unit/ExampleTest.php
```

## `.env.example`

Provides the shared environment template.

Copy it to `.env` for local configuration.

```bash
cp .env.example .env
```

## `composer.json`

Defines package metadata, dependencies, autoloading, and Composer scripts.

Use it to review required packages and available scripts such as `composer serve` and `composer test`.

## Next Steps

Continue with [Request Lifecycle](request-lifecycle.md).
