# Getting Started with IntisariPHP Starter

This guide walks you through setting up and running a new IntisariPHP application from scratch.

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP >= 8.2** — [php.net/downloads](https://www.php.net/downloads)
- **Composer** — [getcomposer.org](https://getcomposer.org)

Verify your setup:

```bash
php -v
composer -V
```

---

## Step 1 — Create a New Project

Use `composer create-project` to scaffold a fresh application:

```bash
composer create-project lukman-ss/intisari-starter my-app
```

Then navigate into your project directory:

```bash
cd my-app
```

---

## Step 2 — Configure Your Environment

Copy the environment file template:

```bash
cp .env.example .env
```

Open `.env` and adjust the values to match your local setup. The most common variables to update are:

| Variable       | Default                  | Description                     |
|----------------|--------------------------|---------------------------------|
| `APP_NAME`     | `Intisari App`           | Application display name        |
| `APP_ENV`      | `local`                  | Environment (`local`, `production`) |
| `APP_DEBUG`    | `true`                   | Show detailed error messages     |
| `APP_URL`      | `http://localhost:8000`  | Base URL of the application      |
| `DB_CONNECTION`| `sqlite`                 | Database driver                 |
| `DB_DATABASE`  | `database/database.sqlite` | Path to the SQLite database file |

---

## Step 3 — Start the Development Server

Run the built-in PHP server using the Composer shortcut:

```bash
composer serve
```

This starts the server at [http://127.0.0.1:8000](http://127.0.0.1:8000).

> **Note:** The `serve` script is defined in `composer.json` as:
> `php -S 127.0.0.1:8000 -t public`

---

## Step 4 — Run the Tests

Verify everything is working by running the test suite:

```bash
composer test
```

A passing result confirms the project is bootstrapped correctly.

---

## Project Layout

```
my-app/
├── app/
│   ├── Controllers/        # HTTP request handlers
│   ├── Middleware/         # Request/response middleware
│   └── Providers/          # Service provider registrations
├── bootstrap/              # App initialization (app.php)
├── config/
│   ├── app.php             # Core application settings
│   └── database.php        # Database connection settings
├── public/
│   └── index.php           # Front controller (web root)
├── resources/
│   └── views/              # PHP view templates
├── routes/
│   ├── web.php             # HTTP route definitions
│   └── console.php         # Artisan-style console commands
├── storage/
│   ├── cache/              # Cached data
│   ├── logs/               # Application logs
│   └── framework/          # Framework runtime files
├── tests/                  # PHPUnit test files
├── .env                    # Your local environment config (not committed)
├── .env.example            # Environment variable template
└── composer.json           # Project dependencies and scripts
```

---

## Useful Composer Scripts

| Command          | Description                        |
|------------------|------------------------------------|
| `composer serve` | Start the local development server |
| `composer test`  | Run the PHPUnit test suite         |

---

## Core Framework

This starter is built on top of [`lukman-ss/intisari`](https://packagist.org/packages/lukman-ss/intisari), the core PHP framework engine that provides routing, HTTP handling, dependency injection, configuration, view rendering, database access, and validation.
