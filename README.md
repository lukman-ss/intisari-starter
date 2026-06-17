# IntisariPHP Starter

![IntisariPHP Starter Hero](docs/hero.png)

Starter project for building applications with the [IntisariPHP](https://packagist.org/packages/lukman-ss/intisari) framework.

## Requirements

- PHP >= 8.2
- Composer

## Installation

Create a new project using Composer:

```bash
composer create-project lukman-ss/intisari-starter my-app
```

Move into your project directory:

```bash
cd my-app
```

Copy the environment file:

```bash
cp .env.example .env
```

## Development Server

Start the built-in PHP development server:

```bash
composer serve
```

The application will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Running Tests

```bash
composer test
```

## Intisari CLI (Console Commands)

This starter project includes a Laravel-style CLI tool named `intisari` located at the root of the project. It works similarly to Laravel's `artisan`, allowing you to run, manage, and generate various components of your application.

### Available Commands

- **Serve Development Server:**
  Starts the built-in development server.
  ```bash
  php intisari serve
  # Options: --host=0.0.0.0 --port=8080
  ```

- **Route Listing:**
  Lists all registered web routes in a clean ASCII table.
  ```bash
  php intisari route:list
  ```

- **Configuration Cache:**
  Caches all configuration files into a single optimized cache file.
  ```bash
  php intisari config:cache
  ```

- **Configuration Clear:**
  Removes the configuration cache file.
  ```bash
  php intisari config:clear
  ```

- **Generate Controller:**
  Creates a new `final` controller class in `app/Controllers/`.
  ```bash
  php intisari make:controller UserController
  # Suffix 'Controller' is automatically appended if omitted.
  # Options: --force to overwrite.
  ```

- **Generate Middleware:**
  Creates a new `final` middleware class in `app/Middleware/`.
  ```bash
  php intisari make:middleware AuthMiddleware
  # Options: --force to overwrite.
  ```

- **Generate Service Provider:**
  Creates a new `final` service provider class in `app/Providers/`.
  ```bash
  php intisari make:provider PaymentServiceProvider
  # Options: --force to overwrite.
  ```

- **Generate Console Command:**
  Creates a new console command class in `app/Commands/`.
  ```bash
  php intisari make:command SendEmailCommand
  # Resolves class name into a colon command (e.g. SendEmailCommand -> send:email).
  # Options: --force to overwrite.
  ```

- **About Application:**
  Displays basic metadata about the application, environment, PHP version, and base path.
  ```bash
  php intisari about
  ```

- **Environment Display:**
  Displays the current application environment safely without exposing secrets.
  ```bash
  php intisari env
  ```

- **Run Tests:**
  Runs the application tests via PHPUnit.
  ```bash
  php intisari test
  ```

## Project Structure

```text
my-app/
|-- app/
|   |-- Controllers/        # HTTP controllers
|   |-- Middleware/         # Application middleware
|   `-- Providers/          # Service providers
|-- bootstrap/              # Application bootstrap files
|-- config/                 # Configuration files
|-- database/               # Local database files
|-- public/                 # Web root and front controller
|-- resources/
|   `-- views/              # PHP view templates
|-- routes/
|   |-- web.php             # Web routes
|   `-- console.php         # Console commands
|-- storage/
|   |-- cache/              # Application cache
|   |-- logs/               # Log files
|   `-- framework/          # Framework runtime files
|-- tests/                  # PHPUnit tests
|-- .env.example            # Environment variable template
`-- composer.json
```

## Documentation

For a step-by-step guide, see [docs/getting-started.md](docs/getting-started.md).

## Framework Dependency

This starter project is powered by [`lukman-ss/intisari`](https://packagist.org/packages/lukman-ss/intisari).
