# Installation

IntisariPHP Starter is installed via Composer's `create-project` command. The entire process takes less than five minutes.

## Installation Overview

| Step | Command | Description |
|------|---------|-------------|
| 1. Create project | `composer create-project lukman-ss/intisari-starter my-app` | Downloads the starter and installs dependencies |
| 2. Enter directory | `cd my-app` | Navigate into your new project |
| 3. Copy env file | `cp .env.example .env` | Create your local environment configuration |
| 4. Start server | `composer serve` | Launch the development server |
| 5. Run tests | `composer test` | Verify the installation |

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
