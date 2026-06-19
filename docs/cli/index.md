# Command Line Usage

IntisariPHP Starter includes a CLI entry point for development tasks. All commands are accessed through the root `intisari` file or Composer scripts.

## Entry Point

The root `intisari` file is the main CLI entry point. All Composer scripts reference this file.

```bash
php intisari <command>
```

## Composer Scripts

Composer scripts provide shortcuts for common commands:

| Script | Maps to | Description |
|--------|---------|-------------|
| `composer serve` | `php intisari serve` | Start the development server |
| `composer console` | `php intisari` | Open the interactive console |
| `composer test` | `phpunit` | Run the PHPUnit test suite |

### composer serve

Start the built-in PHP development server:

```bash
composer serve
```

The application will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

To use a custom host or port:

```bash
php intisari serve --host=0.0.0.0 --port=8080
```

### composer test

Run the PHPUnit test suite:

```bash
composer test
```

Expected output:

```text
PHPUnit 10.x by Sebastian Bergmann and contributors.

OK (X tests, Y assertions)
```

### composer console

Open the interactive console or run commands directly:

```bash
composer console
```

Or run a specific command:

```bash
php intisari route:list
php intisari about
```

## Available Commands

All commands are defined in `routes/console.php`.

### Development Commands

| Command | Description |
|---------|-------------|
| `serve` | Start the built-in development server |
| `test` | Run the PHPUnit test suite |
| `hello` | Print hello message |
| `about` | Display application information |
| `env` | Display the current environment |

### Route Commands

| Command | Description |
|---------|-------------|
| `route:list` | List all registered web routes |

### Configuration Commands

| Command | Description |
|---------|-------------|
| `config:cache` | Cache configuration for faster loading |
| `config:clear` | Remove the configuration cache |

### Generator Commands

| Command | Description |
|---------|-------------|
| `make:controller <Name>` | Generate a controller class in `app/Controllers/` |
| `make:middleware <Name>` | Generate a middleware class in `app/Middleware/` |
| `make:provider <Name>` | Generate a service provider class in `app/Providers/` |
| `make:command <Name>` | Generate a console command class in `app/Commands/` |

### Using Generator Commands

**Create a controller:**

```bash
php intisari make:controller User
```

This creates `app/Controllers/UserController.php` with a basic template.

**Create middleware:**

```bash
php intisari make:middleware Auth
```

This creates `app/Middleware/Auth.php` implementing `MiddlewareInterface`.

**Create a service provider:**

```bash
php intisari make:provider Event
```

This creates `app/Providers/EventServiceProvider.php`.

**Create a console command:**

```bash
php intisari make:command SendEmails
```

This creates `app/Commands/SendEmailsCommand.php` extending `Lukman\Console\Command`.

Use the `--force` option to overwrite existing files:

```bash
php intisari make:controller User --force
```

## The routes/console.php File

Commands are registered in `routes/console.php` using `$app->command()`:

```php
$app->command('hello', static function ($input, $output): int {
    $output->writeln('Hello Intisari');
    return 0;
}, 'Print hello message');
```

Each command receives:

- `$input` — access to arguments and options
- `$output` — methods to write to the terminal

The command must return an integer exit code (`0` for success, non-zero for failure).

## Commands Not Available

The following commands are **not** included in the starter:

| Command | Status |
|---------|--------|
| `make:model` | Not available |
| `make:migration` | Not available |
| `migrate` | Not available |
| `db:seed` | Not available |
| `route:cache` | Not available |

Database migrations, seeders, and model generators depend on features that are not configured in the starter. If these features become available through the IntisariPHP core database package, they will be documented here.

## Common Errors

### Command not found

**Symptom:** `Command "xyz" not found`

**Solution:** Run from the project root and verify the command exists in `routes/console.php`:

```bash
php intisari
```

This lists all available commands.

### Permission denied

**Symptom:** `bash: ./intisari: Permission denied`

**Solution:** Run through PHP instead of executing the file directly:

```bash
php intisari serve
```

Or add execute permission:

```bash
chmod +x intisari
```

### PHP not found

**Symptom:** `php: command not found` or `'php' is not recognized`

**Solution:** Install PHP 8.2 or newer and add it to your system PATH.

**macOS:**

```bash
brew install php@8.2
```

**Ubuntu/Debian:**

```bash
sudo apt install php8.2-cli
```

**Windows:**

Download from [windows.php.net](https://windows.php.net/download/) and add the PHP directory to your system PATH.

Verify installation:

```bash
php -v
```

### Port already in use

**Symptom:** `Failed to listen on 127.0.0.1:8000`

**Solution:** Start the server on a different port:

```bash
php intisari serve --port=8080
```

Or stop the process using port 8000:

**macOS/Linux:**

```bash
lsof -i :8000
kill -9 <PID>
```

**Windows PowerShell:**

```powershell
Get-NetTCPConnection -LocalPort 8000 | Select-Object OwningProcess
Stop-Process -Id <PID>
```

## Next

Continue to [Testing](../testing/index.md).
