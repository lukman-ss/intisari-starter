# Composer Installation

IntisariPHP Starter uses Composer's `create-project` command to scaffold a new application. This page covers the installation steps in detail.

## Prerequisites

Before installing, ensure you have:

- **PHP 8.2 or newer** — run `php -v` to check
- **Composer 2.x** — run `composer -V` to check

See [Requirements](../intro/requirements.md) for a complete list of required PHP extensions.

## Create a New Project

Run the following command to create a new IntisariPHP Starter application:

```bash
composer create-project lukman-ss/intisari-starter my-app
```

This command:

1. Downloads the IntisariPHP Starter skeleton from Packagist.
2. Resolves and installs the IntisariPHP core framework.
3. Installs development dependencies (PHPUnit, testing tools).
4. Generates `composer.lock` to lock dependency versions.
5. Configures PSR-4 autoloading for `app/` and `tests/` namespaces.

Navigate into your new project:

```bash
cd my-app
```

## Environment Setup

The starter includes an `.env.example` file with default configuration values. You must copy this file to `.env` before running the application.

**macOS and Linux:**

```bash
cp .env.example .env
```

**Windows PowerShell:**

```powershell
Copy-Item .env.example .env
```

**Windows Command Prompt:**

```powershell
copy .env.example .env
```

### Default Environment Values

The `.env.example` file includes these settings:

```env
APP_NAME=IntisariPHP
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

You can modify these values after installation to match your local environment.

### Security Note

**Do not commit `.env` to version control.** The `.gitignore` file included with the starter already excludes `.env` by default. This prevents sensitive configuration values (database passwords, API keys) from being exposed in your repository.

## Verify the Installation

Start the development server to confirm the installation succeeded:

```bash
composer serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser. You should see the IntisariPHP welcome page.

Then run the test suite to confirm all components are working:

```bash
composer test
```

Expected output:

```text
PHPUnit 10.x by Sebastian Bergmann and contributors.

OK (X tests, Y assertions)
```

## Troubleshooting

### PHP version too old

The starter requires PHP 8.2 or newer. If you see a version error, upgrade PHP:

```bash
php -v
```

On Ubuntu/Debian: `sudo apt install php8.2`

On macOS: `brew install php@8.2`

On Windows: Download from [windows.php.net](https://windows.php.net/download/).

### Composer not installed

If `composer` is not recognized, install Composer from [getcomposer.org](https://getcomposer.org).

```bash
composer -V
```

### Permission errors

If Composer cannot write to the target directory, create the project in a writable location:

```bash
composer create-project lukman-ss/intisari-starter ~/projects/my-app
```

Or adjust directory permissions before running the command.

## Next Steps

Once installation is complete:

1. Continue to [Running the Application](running.md) to start the development server.
2. Read [Application Overview](../overview/index.md) to understand the project structure.
3. Follow the [Build Your First App](../tutorials/build-your-first-app.md) tutorial.

## Next

Continue to [Running the Application](running.md).
