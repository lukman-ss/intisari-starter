# Application Overview

IntisariPHP Starter organizes code into a small set of directories with clear responsibilities. Understanding this structure helps you know where to place new code.

## Directory Structure

```text
app/              Application classes (controllers, middleware, providers)
bootstrap/        Application initialization
config/           Configuration files (app, database, session)
database/         Local database files
public/           Web entry point (index.php)
resources/views/  PHP view templates
routes/           HTTP routes and CLI commands
storage/          Cache, logs, and framework runtime files
tests/            PHPUnit tests
```

## Request Flow

Every HTTP request follows this path:

1. Web server sends request to `public/index.php`
2. `bootstrap/app.php` creates the application instance
3. Configuration is loaded from `config/` and `.env`
4. Routes are loaded from `routes/web.php`
5. Middleware processes the request
6. The matched route handler runs
7. A response is returned to the browser

## Key Files

| File | Purpose |
|------|---------|
| `public/index.php` | Front controller — all web requests enter here |
| `bootstrap/app.php` | Creates the application, loads env and config |
| `routes/web.php` | HTTP route definitions |
| `routes/console.php` | CLI command definitions |
| `config/app.php` | Application settings, providers, middleware |
| `intisari` | CLI entry point |

## Where to Put Your Code

| Task | Directory |
|------|-----------|
| Handle HTTP requests | `app/Controllers/` |
| Add request/response middleware | `app/Middleware/` |
| Register services or bindings | `app/Providers/` |
| Define routes | `routes/web.php` |
| Add CLI commands | `routes/console.php` |
| Create view templates | `resources/views/` |
| Write tests | `tests/` |

## Documentation

- [Application Structure](application-structure.md) — detailed directory reference
- [Request Lifecycle](request-lifecycle.md) — step-by-step request processing

## Next

Continue to [Application Structure](application-structure.md).
