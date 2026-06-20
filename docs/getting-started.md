# Getting Started with IntisariPHP Starter

This guide walks you through setting up and running a new IntisariPHP Starter application in about five minutes.

## Prerequisites

Before you start, make sure you have:

- **PHP 8.2 or newer** — [Download PHP](https://www.php.net/downloads)
- **Composer** — [Download Composer](https://getcomposer.org)

Verify your setup by running:

```bash
php -v
composer -V
```

## Step 1 — Create a New Project

Use `composer create-project` to scaffold a fresh application:

```bash
composer create-project lukman-ss/intisari-starter my-app
```

This command downloads the starter skeleton, installs all dependencies, and configures autoloading.

Navigate into your project directory:

```bash
cd my-app
```

## Step 2 — Configure Your Environment

The starter includes an `.env.example` file with default configuration. Copy it to `.env`:

**macOS / Linux:**

```bash
cp .env.example .env
```

**Windows PowerShell:**

```powershell
Copy-Item .env.example .env
```

Open `.env` in your editor and adjust the values for your local setup. The default variables are:

| Variable | Default | Description |
| --- | --- | --- |
| `APP_NAME` | `Intisari App` | Application display name |
| `APP_ENV` | `local` | Application environment |
| `APP_DEBUG` | `true` | Show detailed error messages |
| `APP_URL` | `http://127.0.0.1:8000` | Base URL of the application |
| `APP_TIMEZONE` | `Asia/Jakarta` | Application timezone |
| `APP_LOCALE` | `en` | Application locale |
| `DB_CONNECTION` | `sqlite` | Database driver |
| `DB_DATABASE` | `database/database.sqlite` | SQLite database path |
| `SESSION_DRIVER` | `file` | Session storage driver |
| `SESSION_LIFETIME` | `120` | Session lifetime in minutes |

## Step 3 — Start the Development Server

Start the built-in PHP development server:

```bash
composer serve
```

This runs `php intisari serve` under the hood. The server starts at [http://127.0.0.1:8000](http://127.0.0.1:8000).

Open this URL in your browser. You should see the IntisariPHP welcome page.

To use a custom host or port:

```bash
php intisari serve --host=0.0.0.0 --port=8080
```

The default routes are defined in `routes/web.php`:

```php
$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);
```

## Step 4 - Run the Tests

Verify the starter application by running:

```bash
composer test
```

## Project Layout

```text
my-app/
|-- app/
|   |-- Controllers/        # HTTP request handlers
|   |-- Middleware/         # Application middleware
|   `-- Providers/          # Service provider registrations
|-- bootstrap/              # App initialization
|-- config/
|   |-- app.php             # Application settings
|   |-- database.php        # Database connection settings
|   `-- session.php         # Session settings
|-- database/               # Local database files
|-- public/
|   `-- index.php           # Front controller
|-- resources/
|   `-- views/              # PHP view templates
|-- routes/
|   |-- web.php             # HTTP route definitions
|   `-- console.php         # Console commands
|-- storage/
|   |-- cache/              # Cached data
|   |-- logs/               # Application logs
|   `-- framework/          # Framework runtime files
|-- tests/                  # PHPUnit test files
|-- .env                    # Your local environment config
|-- .env.example            # Environment variable template
`-- composer.json           # Project dependencies and scripts
```

## Useful Composer Scripts

| Command | Description |
| --- | --- |
| `composer serve` | Start the local development server |
| `composer console` | Run the Intisari CLI entry point |
| `composer test` | Run the PHPUnit test suite |

## Available CLI Commands

These commands are defined in `routes/console.php`:

```bash
php intisari hello
php intisari serve
php intisari route:list
php intisari config:cache
php intisari config:clear
php intisari make:controller UserController
php intisari make:middleware AuthMiddleware
php intisari make:provider PaymentServiceProvider
php intisari make:command SendEmailCommand
php intisari about
php intisari env
php intisari test
```

## Core Package

This starter is built on top of [IntisariPHP core](https://packagist.org/packages/lukman-ss/intisari).

## Next

Continue to [Introduction](intro/index.md).
