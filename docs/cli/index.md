# Command Line Usage

IntisariPHP Starter includes a command line entry point for local development tasks.

The root `intisari` file is the main command line entry point. The repository also includes `bin/intisari`. Composer scripts use the root `intisari` entry file.

## Composer Shortcuts

The starter defines these scripts in `composer.json`.

| Composer Script | Maps To |
| --- | --- |
| `composer serve` | `php intisari serve` |
| `composer console` | `php intisari` |
| `composer test` | PHPUnit |

Use them from the project root:

```bash
composer serve
composer console
composer test
```

## `php intisari`

Run the command line entry point directly:

```bash
php intisari
```

This is the direct command behind:

```bash
composer console
```

## `php intisari serve`

Start the local development server:

```bash
php intisari serve
```

This is the direct command behind:

```bash
composer serve
```

## Console Routes

Application command line commands are defined in `routes/console.php`.

```php
$app->command('hello', static function ($input, $output): int {
    $output->writeln('Hello Intisari');

    return 0;
}, 'Print hello message');
```

The starter's available commands are defined in that file. Check `routes/console.php` before documenting or relying on a command.

## Common Command Line Errors

### Command Not Found

Run commands from the project root. Use Composer shortcuts when possible:

```bash
composer console
```

### Permission Denied

If executing the file directly fails, run it through PHP:

```bash
php intisari
```

### PHP Not Found

Confirm PHP is installed and available in your terminal:

```bash
php -v
```

### Port Already in Use

If the default port is already used, start the server on another port:

```bash
php intisari serve --port=8080
```

### Command Not Registered

Confirm the command exists in `routes/console.php`.


## Next Steps

Continue with [Testing](../testing/index.md).
