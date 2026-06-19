# IntisariPHP Starter

![IntisariPHP Starter Hero](docs/hero.png)

Starter project for building PHP applications with [IntisariPHP core](https://packagist.org/packages/lukman-ss/intisari).

## Requirements

- PHP >= 8.2
- Composer

## Quick Start

Create a project and enter the application directory:

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
```

Copy the environment template:

```bash
cp .env.example .env
```

## Development Server

Start the built-in PHP development server:

```bash
composer serve
```

The application will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

You can also open the command line entry point with:

```bash
composer console
```

## Running Tests

```bash
composer test
```

## Documentation

Read the documentation:

- [Documentation Index](docs/index.md)
- [Introduction](docs/intro/index.md)
- [Installation](docs/installation/index.md)
- [Build Your First Application](docs/tutorials/build-your-first-app.md)

## Core Package

This starter project is powered by [`lukman-ss/intisari`](https://packagist.org/packages/lukman-ss/intisari).

## License

MIT
