# IntisariPHP Starter

![IntisariPHP Starter Hero](docs/hero.png)

A minimal PHP application starter built on IntisariPHP. Includes routing, controllers, views, configuration, and PHPUnit setup out of the box.

## Requirements

- PHP >= 8.2
- Composer

## Quick Start

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
```

The application will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

```bash
composer test
```

## Running Development Server

```bash
composer serve
```

## Running Tests

```bash
composer test
```

## Project Structure

```text
app/          Application classes
bootstrap/    Application bootstrap
config/       Configuration files
database/     Local database files
public/       Web entry point
resources/    Views and frontend resources
routes/       Web and console routes
storage/      Runtime files
tests/        PHPUnit tests
```

## Console Commands (CLI)

The application comes with the `intisari` command-line utility. You can use it to perform various tasks:

- Start development server:
  ```bash
  php intisari serve
  ```
- List all registered routes:
  ```bash
  php intisari route:list
  ```
- Cache configuration files:
  ```bash
  php intisari config:cache
  ```
- Clear configuration cache:
  ```bash
  php intisari config:clear
  ```
- Create a new controller:
  ```bash
  php intisari make:controller UserController
  ```
- Create a new middleware:
  ```bash
  php intisari make:middleware AuthMiddleware
  ```
- Create a new service provider:
  ```bash
  php intisari make:provider PaymentServiceProvider
  ```
- Create a new console command:
  ```bash
  php intisari make:command SendEmailCommand
  ```
- View application environment details:
  ```bash
  php intisari env
  ```
- Display information about the application:
  ```bash
  php intisari about
  ```
- Run tests:
  ```bash
  php intisari test
  ```

## Documentation

- [Documentation Index](docs/index.md)
- [Getting Started](docs/getting-started.md)
- [Command Line Usage](docs/cli/index.md)
- [Deployment](docs/deployment/index.md)

## IntisariPHP Core

This starter is powered by [`lukman-ss/intisari`](https://packagist.org/packages/lukman-ss/intisari).

## License

MIT
