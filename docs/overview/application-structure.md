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

Contains application-specific PHP classes.

Use this folder when adding code that belongs to your application rather than IntisariPHP core.

## `app/Controllers/`

Contains HTTP controllers.

Use this folder when a route needs a controller method instead of an inline closure.

```php
$app->get('/status', [StatusController::class, 'index']);
```

## `app/Middleware/`

Contains application middleware.

Use this folder for request or response handling that should wrap route execution. Middleware behavior depends on installed IntisariPHP core features.

## `app/Providers/`

Contains service providers.

Use this folder when registering application services or bootstrapping application-specific behavior.

## `bootstrap/`

Contains application bootstrap files.

`bootstrap/app.php` creates the application instance, loads `.env`, loads configuration, and registers the application service provider.

## `config/`

Contains PHP configuration files.

Use this folder for application, database, and session settings.

## `database/`

Contains local database-related files.

The starter uses this folder for the default SQLite database path when configured.

## `public/`

Contains the public web entry point.

`public/index.php` should be the web server document root entry point.

## `resources/views/`

Contains PHP view templates.

Use this folder for pages, layouts, partials, and error views used by the application.

## `routes/`

Contains route definitions.

Use `routes/web.php` for HTTP routes and `routes/console.php` for console commands.

## `storage/`

Contains runtime files.

Use this folder for cache, logs, and framework runtime files. The application must be able to write to relevant storage paths.

## `tests/`

Contains PHPUnit tests.

Use this folder when adding application tests.

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

Continue with [Application Lifecycle](lifecycle.md).
