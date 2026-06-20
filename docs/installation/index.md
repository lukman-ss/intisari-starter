# Installation

IntisariPHP Starter is installed via Composer's `create-project` command. The entire process takes less than five minutes.

## Installation Flow

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
composer test
```

This sequence creates the project, enters its directory, creates the local environment file, starts the development server, and verifies the installation with PHPUnit.

## What Gets Installed

When you run `composer create-project`, the following are set up automatically:

- **IntisariPHP core** — the framework runtime, installed into `vendor/`
- **Development dependencies** — PHPUnit and testing tools
- **PSR-4 autoloading** — configured for `app/` and `tests/` namespaces
- **Composer scripts** — `serve`, `test`, `console`

## Environment Setup

The `.env` file stores your local configuration values:

- Application name and environment (`APP_NAME`, `APP_ENV`)
- Debug mode (`APP_DEBUG`)
- Application URL (`APP_URL`)
- Database connection (`DB_*`)
- Session settings (`SESSION_*`)

**Important:** Do not commit `.env` to version control. Add it to your `.gitignore` file.

## After Installation

Once installation is complete, your application will be available at:

```text
http://127.0.0.1:8000
```

The test suite should pass without errors:

```text
OK (X tests, Y assertions)
```

## Documentation

- [Composer Installation](composer.md) — detailed installation steps with platform-specific notes
- [Running the Application](running.md) — starting the development server and running tests

## Next

Continue to [Composer Installation](composer.md).
