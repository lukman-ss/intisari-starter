# Application Overview

IntisariPHP Starter separates application code, bootstrap logic, configuration, public files, runtime storage, and tests into explicit paths.

## Top-Level Structure

```text
app/                 Application classes
bootstrap/           Application initialization
config/              Application configuration
database/            Local database files
docs/                Project documentation
public/              Web server document root
resources/views/     PHP view templates
routes/               Web and console definitions
storage/              Writable runtime data
tests/                PHPUnit tests
.env.example          Environment template
composer.json         Dependencies and autoloading
intisari              CLI entry point
phpunit.xml           PHPUnit configuration
```

## Public and Private Paths

The web server document root must point to `public/`. Application code, configuration, views, storage, tests, documentation, Composer files, and environment files must not be directly web-accessible.

The `storage/` directory must be writable by the application where runtime files are used, but it must remain outside the public web root.

## Application Code

Application-owned PHP classes live under `app/`:

- `app/Controllers/` contains HTTP request handlers.
- `app/Middleware/` contains request and response middleware.
- `app/Providers/` contains application service providers.
- `app/Commands/` is created on demand by `php intisari make:command`; it is not present in a fresh starter checkout.

## Key Entry Points

| File | Purpose |
| --- | --- |
| `public/index.php` | Receives web requests, loads the application, and runs it |
| `bootstrap/app.php` | Creates and configures the application instance |
| `routes/web.php` | Registers default HTTP routes |
| `routes/console.php` | Registers project CLI commands |
| `intisari` | Starts the console application |

## Next

Continue to [Application Structure](application-structure.md).
