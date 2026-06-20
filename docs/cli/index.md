# Command Line Utility

IntisariPHP Starter includes the Intisari CLI utility to assist with development, routing, configuration management, generation of class files, and application inspection.

## CLI Entry Point

The entry point for CLI commands is the `intisari` script located in the root of the project. You can run it from your terminal using:

```bash
php intisari
```

Running `php intisari` without any command will display the list of available commands along with the application's name and version.

> [!NOTE]
> The CLI utility is not an interactive console or REPL. It only parses and executes the specified command, then exits.

You can get general command listings or detailed help information for any command using:

```bash
php intisari list
php intisari help <command-name>
```

---

## Composer Scripts

The starter application includes several convenient Composer script shortcuts in [composer.json](file:///d:/PHP%20PACKAGIST/intisari-starter/composer.json):

| Composer Command | Equivalent CLI Command | Purpose |
| :--- | :--- | :--- |
| `composer serve` | `php intisari serve` | Start the local development server |
| `composer console` | `php intisari` | Display the list of all available commands |
| `composer test` | `phpunit` | Run the test suite via PHPUnit |

---

## Available Commands

Commands are grouped by their purpose.

### Development

| Command | Options | Purpose |
| :--- | :--- | :--- |
| `php intisari serve` | `--host`, `--port` | Start PHP's built-in development server |
| `php intisari hello` | None | Print a starter greeting message |
| `php intisari list` | None | List all available console commands |
| `php intisari help <command>` | None | Display help details for a specific command |

#### Options for `serve`:
* `--host`: Specifies the host address to bind the server to (defaults to `127.0.0.1`). Must contain only letters, digits, dots, or hyphens.
* `--port`: Specifies the port to run the server on (defaults to `8000`). Must be an integer between `1` and `65535`.

Example:
```bash
php intisari serve --host=127.0.0.1 --port=8080
```

### Routing

| Command | Options | Purpose |
| :--- | :--- | :--- |
| `php intisari route:list` | None | List all routes registered in [routes/web.php](file:///d:/PHP%20PACKAGIST/intisari-starter/routes/web.php) in a structured table |

### Configuration

| Command | Options | Purpose |
| :--- | :--- | :--- |
| `php intisari config:cache` | None | Compile and cache configuration into a single file at `storage/cache/config.php` for faster loading |
| `php intisari config:clear` | None | Delete the cached configuration file at `storage/cache/config.php` |

### Generators

| Command | Argument | Options | Target Path |
| :--- | :--- | :--- | :--- |
| `php intisari make:controller` | `<name>` | `--force` | `app/Controllers/<Name>Controller.php` |
| `php intisari make:middleware` | `<name>` | `--force` | `app/Middleware/<Name>.php` |
| `php intisari make:provider` | `<name>` | `--force` | `app/Providers/<Name>ServiceProvider.php` |
| `php intisari make:command` | `<name>` | `--force` | `app/Commands/<Name>Command.php` |

#### Options for Generators:
* `--force`: Force overwrite the class file if it already exists in the destination folder.

### Application Info

| Command | Options | Purpose |
| :--- | :--- | :--- |
| `php intisari about` | None | Display basic details of the application (Name, Environment, PHP Version, and Base Path) |
| `php intisari env` | None | Display the current application environment (e.g., `local`, `production`) |

### Testing

| Command | Options | Purpose |
| :--- | :--- | :--- |
| `php intisari test` | None | Execute application tests by triggering `composer test` and forwarding the exit status |

---

## Generator Command Examples

All generator commands require a class name as an argument. The names must be valid PHP class names.

### Create a Controller
Creates a controller class in `app/Controllers/`. The suffix `Controller` is automatically appended if not provided.
```bash
php intisari make:controller UserController
```

### Create a Middleware
Creates a middleware class in `app/Middleware/` implementing [Lukman\Http\MiddlewareInterface](file:///d:/PHP%20PACKAGIST/http/src/MiddlewareInterface.php).
```bash
php intisari make:middleware AuthMiddleware
```

### Create a Service Provider
Creates a provider class in `app/Providers/` extending [Intisari\ServiceProvider](file:///d:/PHP%20PACKAGIST/intisari/src/ServiceProvider.php). The suffix `ServiceProvider` is automatically appended if not provided.
```bash
php intisari make:provider DatabaseServiceProvider
```

### Create a Command
Creates a command class in `app/Commands/` extending [Lukman\Console\Command](file:///d:/PHP%20PACKAGIST/console/src/Command.php). The suffix `Command` is automatically appended if not provided.
```bash
php intisari make:command SendEmailsCommand
```

### Force Overwrite Files
If a file already exists at the target path, the generator fails to protect existing logic. To force overwrite it, use the `--force` option:
```bash
php intisari make:controller UserController --force
```

---

## Customizing `routes/console.php`

All CLI commands in the application are registered via `$app->command()` in [routes/console.php](file:///d:/PHP%20PACKAGIST/intisari-starter/routes/console.php).

You can add your own closure-based console commands like this:

```php
$app->command('hello', static function ($input, $output): int {
    $output->writeln('Hello Intisari');
    return 0;
}, 'Print hello message');
```

For class-based commands (e.g. generated via `make:command`), you must instantiate and register them inside your console configuration or within [routes/console.php](file:///d:/PHP%20PACKAGIST/intisari-starter/routes/console.php) by instantiating the command class and calling `$app->add($command)`:

```php
use App\Commands\SendEmailsCommand;

$app->add(new SendEmailsCommand());
```

---

## Commands Not Available

The CLI utility is a lightweight runner for Intisari application tasks. It does **not** feature full compatibility with other framework tools (such as Laravel's Artisan). The following commands and categories are **not** available in the starter:

* **Database Operations**: `make:model`, `make:migration`, `migrate`, `db:seed`
* **Queue Management**: `queue:*` or queue workers
* **Task Scheduling**: `schedule:*` or cron schedulers

If database or migration support is needed, you must integrate an external package (such as Doctrine or Phinx) and handle configuration manually.

---

## Common Errors

### Command Not Found
* **Message**: `Command "some:command" is not defined.`
* **Cause**: You typed a command that does not exist or has not been registered in [routes/console.php](file:///d:/PHP%20PACKAGIST/intisari-starter/routes/console.php).
* **Solution**: Check spelling or run `php intisari` / `php intisari list` to view all registered commands.

### Missing Generator Name
* **Message**: `Not enough arguments (missing: "name").`
* **Cause**: You executed a `make:*` generator without providing a class name.
* **Solution**: Always specify a class name argument: `php intisari make:controller UserController`.

### Invalid Generator Name
* **Message**: `The name must be a valid PHP class name.`
* **Cause**: The name argument contains invalid characters (e.g. hyphens, slashes, or special symbols).
* **Solution**: Provide a valid alphanumeric PHP identifier starting with a letter or underscore (e.g., `UserController`).

### Target Already Exists
* **Message**: `Controller already exists.` (or Middleware, Provider, Command already exists)
* **Cause**: A file with the same name exists at the destination path, and the `--force` option was not specified.
* **Solution**: Use the `--force` flag if you intend to overwrite the file.

### Invalid Host or Port for Server
* **Message**: `Invalid host value.` or `Port must be between 1 and 65535.`
* **Cause**: The `--host` parameter contains disallowed characters or the `--port` parameter is not within the valid range (1 - 65535).
* **Solution**: Use a valid IP address / hostname and a valid port number.

### Address Already in Use
* **Message**: Output showing standard PHP server bind errors or failure to start.
* **Cause**: Another service or server instance is already running on the designated host and port.
* **Solution**: Choose a different port using the `--port` option:
  ```bash
  php intisari serve --port=8080
  ```

---

## Next

Continue to the [Testing Documentation](../testing/index.md).
