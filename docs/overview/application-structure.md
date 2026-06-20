# Application Structure

This reference describes the paths included in IntisariPHP Starter and when they should be changed.

## Directory Reference

| Path | Purpose | Common files | When to edit | Web-accessible |
| --- | --- | --- | --- | --- |
| `app/` | Contains application-owned PHP classes under the `App\` namespace. | `Controllers/`, `Middleware/`, `Providers/` | Edit when implementing application behavior. | No |
| `app/Controllers/` | Contains HTTP route handlers. | `HomeController.php`, `StatusController.php` | Edit or add controllers when handling new requests. | No |
| `app/Middleware/` | Contains middleware classes implementing the HTTP middleware contract. | `ExampleMiddleware.php` | Edit or add middleware when request or response processing needs an additional layer. | No |
| `app/Providers/` | Contains application service providers. | `AppServiceProvider.php` | Edit when registering or bootstrapping application services. | No |
| `bootstrap/` | Creates the application instance and loads environment, configuration, and the application provider. | `app.php` | Change only when application startup wiring must change. | No |
| `config/` | Contains PHP files that return configuration arrays. | `app.php`, `database.php`, `session.php` | Edit when adding or changing application configuration defaults. | No |
| `database/` | Holds local database files when the application uses a file-based database. | `.gitkeep`, optional `database.sqlite` | Edit or populate when using SQLite locally. | No |
| `docs/` | Contains the project user guide. | `index.md`, topic directories | Edit when documented behavior or workflows change. | No |
| `public/` | Contains the front controller and public web-server configuration. This is the web root. | `index.php`, `.htaccess` | Edit only for public entry-point or web-server concerns. | Yes |
| `resources/views/` | Contains PHP view templates, layouts, partials, and error-page examples. | `home.php`, `layouts/app.php`, `partials/header.php`, `errors/` | Edit when changing rendered HTML. | No |
| `routes/` | Contains HTTP route and console command registration. | `web.php`, `console.php` | Edit when adding routes or project CLI commands. | No |
| `storage/` | Holds writable runtime data. It must remain outside the public web root. | `cache/`, `logs/`, `framework/` | Usually not edited as source; ensure required directories exist and are writable. | No |
| `tests/` | Contains PHPUnit tests and test support classes under the `Tests\` namespace. | `TestCase.php`, `Unit/`, `Feature/`, application tests | Edit when application behavior or project contracts change. | No |

## File Reference

| File | Purpose | When to edit | Web-accessible |
| --- | --- | --- | --- |
| `.env.example` | Lists environment variables used by the starter with safe example values. | Edit when config files add or remove environment variables. | No |
| `composer.json` | Defines package metadata, dependencies, PSR-4 mappings, and Composer scripts. | Edit when dependency, autoloading, or script requirements change. | No |
| `intisari` | Loads the application and console routes, then runs the CLI. | Rarely; project commands normally belong in `routes/console.php`. | No |
| `phpunit.xml` | Defines PHPUnit bootstrap, suites, and test environment values. | Edit when test suites or test environment settings change. | No |

## Generated Commands Directory

The fresh starter does not contain `app/Commands/`. Running the following command creates the directory when needed:

```bash
php intisari make:command SendEmailCommand
```

Generated command classes are application code and must not be web-accessible.

## Web Root and Runtime Permissions

Configure the web server to serve only `public/`. Do not point the document root at the project root.

Allow the application to write to the required paths under `storage/`, while keeping the entire directory unavailable over HTTP.

## Next

Continue to [Request Lifecycle](request-lifecycle.md).
