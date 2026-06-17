# Getting Started with IntisariPHP Starter

This guide walks you through setting up and running a new IntisariPHP starter project.

## Prerequisites

- **PHP >= 8.2** - [php.net/downloads](https://www.php.net/downloads)
- **Composer** - [getcomposer.org](https://getcomposer.org)

Verify your setup:

```bash
php -v
composer -V
```

## Step 1 - Create a New Project

Use `composer create-project` to scaffold a fresh application:

```bash
composer create-project lukman-ss/intisari-starter my-app
```

Then navigate into your project directory:

```bash
cd my-app
```

## Step 2 - Configure Your Environment

Copy the environment file template:

```bash
cp .env.example .env
```

Open `.env` and adjust the values to match your local setup. The default starter variables are:

| Variable | Default | Description |
| --- | --- | --- |
| `APP_NAME` | `Intisari App` | Application display name |
| `APP_ENV` | `local` | Application environment |
| `APP_DEBUG` | `true` | Show detailed error messages |
| `APP_URL` | `http://localhost:8000` | Base URL of the application |
| `DB_CONNECTION` | `sqlite` | Database driver |
| `DB_DATABASE` | `database/database.sqlite` | SQLite database path |

## Step 3 - Start the Development Server

Run the built-in PHP server using the Composer shortcut:

```bash
composer serve
```

This starts the server at [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Step 4 - Run the Tests

Verify the starter project by running:

```bash
composer test
```

## Project Layout

```text
my-app/
|-- app/
|   |-- Controllers/        # HTTP request handlers
|   |-- Middleware/         # Request/response middleware
|   `-- Providers/          # Service provider registrations
|-- bootstrap/              # App initialization
|-- config/
|   |-- app.php             # Application settings
|   `-- database.php        # Database connection settings
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
| `composer test` | Run the PHPUnit test suite |

## Framework Dependency

This starter is built on top of [`lukman-ss/intisari`](https://packagist.org/packages/lukman-ss/intisari).
