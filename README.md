# IntisariPHP Starter

A minimal, fast, and clean starter project for IntisariPHP applications.

## Requirements

- PHP >= 8.2
- Composer

## Quick Start

Create a new project using Composer:

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
```

## Development Server

Start the built-in development server:

```bash
composer serve
```

The application will be accessible at `http://127.0.0.1:8000`.

## Testing & Quality

Run the test suite:

```bash
composer test
```

Check source integrity:

```bash
composer source:check
```

Check documentation integrity:

```bash
composer docs:check
```

## Documentation

For more information on how to build applications, refer to the documentation in the `docs` directory.
- [REST API Tutorial](docs/tutorials/rest-api.md)

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
