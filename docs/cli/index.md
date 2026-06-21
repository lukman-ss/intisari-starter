# Intisari CLI

IntisariPHP Starter includes a small project CLI for local development and application maintenance.

## CLI Entry Point

The root `intisari` file loads Composer autoloading, requires `bootstrap/app.php` and `routes/console.php`, then runs the console application:

```bash
php intisari
```

The CLI executes a command and exits. It is not an interactive console or REPL.

## Composer Scripts

The scripts defined in [`composer.json`](../../composer.json) are:

| Script | Runs | Purpose |
| --- | --- | --- |
| `composer serve` | `php intisari serve` | Start the local development server |
| `composer console` | `php intisari` | Run the CLI entry point |
| `composer test` | `phpunit` | Run PHPUnit |
| `composer docs:check` | `php scripts/check-docs.php` | Validate documentation structure and links |

## Available Commands

The application commands below are registered in [`routes/console.php`](../../routes/console.php).

### Development

| Command | Supported options | Purpose |
| --- | --- | --- |
| `php intisari hello` | None | Print `Hello Intisari` |
| `php intisari serve` | `--host`, `--port` | Start PHP's local development server |

`serve` defaults to `127.0.0.1:8000`. The host accepts letters, digits, dots, and hyphens. The port must be from `1` through `65535`.

```bash
php intisari serve --host=127.0.0.1 --port=8080
```

### Routing

| Command | Supported options | Purpose |
| --- | --- | --- |
| `php intisari route:list` | None | Load `routes/web.php` and display registered routes |

### Configuration

| Command | Supported options | Purpose |
| --- | --- | --- |
| `php intisari config:cache` | None | Write loaded configuration to `storage/cache/config.php` |
| `php intisari config:clear` | None | Remove `storage/cache/config.php` |

The current `bootstrap/app.php` loads `config/` directly and does not consume the generated cache file.

### Generators

| Command | Argument | Supported options | Generated path |
| --- | --- | --- | --- |
| `php intisari make:controller` | `<name>` | `--force` | `app/Controllers/<Name>Controller.php` |
| `php intisari make:middleware` | `<name>` | `--force` | `app/Middleware/<Name>.php` |
| `php intisari make:provider` | `<name>` | `--force` | `app/Providers/<Name>ServiceProvider.php` |
| `php intisari make:command` | `<name>` | `--force` | `app/Commands/<Name>Command.php` |

`--force` overwrites an existing target file. Without it, the command leaves existing content unchanged.

### Application Info

| Command | Supported options | Purpose |
| --- | --- | --- |
| `php intisari about` | None | Display application name, environment, PHP version, and base path |
| `php intisari env` | None | Display the current application environment |

### Testing

| Command | Supported options | Purpose |
| --- | --- | --- |
| `php intisari test` | None | Run `composer test` and return its exit status |

## Generator Examples

```bash
php intisari make:controller UserController
php intisari make:middleware TraceMiddleware
php intisari make:provider PaymentServiceProvider
php intisari make:command SendReportCommand
```

The controller, provider, and command generators append their conventional suffix when it is omitted. Names must be valid PHP class identifiers.

Generated command classes are files only; the generator does not automatically register them for execution.

## Custom Console Commands

Register closure commands in `routes/console.php` with the verified `$app->command()` API:

```php
$app->command('report:status', static function ($input, $output): int {
    $output->writeln('Report ready');

    return 0;
}, 'Display report status');
```

The command is available after it is registered and the CLI reloads `routes/console.php`.

## Commands Not Available

IntisariPHP Starter does not register these commands or subsystems:

- `make:model`
- `make:migration`
- `migrate`
- `db:seed`
- Queue workers or queue management commands
- Scheduler commands

Check `routes/console.php` for the application command registry before relying on another command name.

## Common Errors

- **Command is not defined:** check spelling and registration in `routes/console.php`.
- **Missing generator name:** provide the required class name argument.
- **Invalid generator name:** use a valid PHP identifier without slashes, hyphens, or other special characters.
- **Target already exists:** choose another name or use `--force` only when replacement is intentional.
- **Invalid host or port:** use an allowed hostname and a port from `1` through `65535`.
- **Address already in use:** stop the conflicting process or select another port with `--port`.
- **Generated command is unavailable:** register the command; file generation alone does not add it to the CLI registry.

## Next

Continue to [Testing](../testing/index.md).
