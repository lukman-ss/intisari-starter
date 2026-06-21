# IntisariPHP Starter

![IntisariPHP Starter Hero](docs/hero.png)

A minimal project starter for building PHP applications with IntisariPHP.

## Requirements

- PHP 8.2 or newer
- Composer 2.x

## Quick Start

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
```

The application is available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Development Server

```bash
composer serve
```

The built-in development server is intended for local development only.

## Running Tests

```bash
composer test
```

## Documentation

Start with the [documentation index](docs/index.md) or the [getting started guide](docs/getting-started.md). See the [CLI guide](docs/cli/index.md) for available console commands.

## Project Structure

```text
app/          Application classes
bootstrap/    Application bootstrap
config/       Configuration files
database/     Local database files
public/       Web entry point
resources/    View templates
routes/       Web and console routes
storage/      Runtime files
tests/        PHPUnit tests
```

## IntisariPHP core

IntisariPHP Starter uses [`lukman-ss/intisari`](https://packagist.org/packages/lukman-ss/intisari) as its framework runtime.

## License

MIT
