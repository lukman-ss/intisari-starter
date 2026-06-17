# IntisariPHP Starter

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
