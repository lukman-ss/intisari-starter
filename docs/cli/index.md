# Command Line Usage

IntisariPHP Starter includes command line entry files for project tasks.

The root `intisari` file is the main command line entry point. The repository also includes `bin/intisari`. Composer scripts use the root `intisari` entry file.

## Available Commands

```bash
php intisari
php intisari about
php intisari env
php intisari hello
php intisari serve
php intisari route:list
php intisari config:cache
php intisari config:clear
php intisari make:controller UserController
php intisari make:middleware AuthMiddleware
php intisari make:provider PaymentServiceProvider
php intisari make:command SendEmailCommand
php intisari test
```

Start the development server:

```bash
php intisari serve
```

Display the console application command list or default console output:

```bash
php intisari
```

## Composer Shortcuts

The starter defines Composer scripts in `composer.json`.

| Composer Script | Maps To |
| --- | --- |
| `composer serve` | `php intisari serve` |
| `composer test` | `phpunit` |
| `composer console` | `php intisari` |

Examples:

```bash
composer serve
composer test
composer console
```

## Creating Console Commands

Application command line commands are defined in `routes/console.php`.

```php
$app->command('hello', static function ($input, $output): int {
    $output->writeln('Hello Intisari');

    return 0;
}, 'Print hello message');
```

The starter also includes a generator for command classes:

```bash
php intisari make:command SendEmailCommand
```

This creates a command class in `app/Commands/`. Registering and running generated commands depends on command loading behavior available in the starter and installed IntisariPHP core command line features.

## Troubleshooting Command Line

### Command Not Found

Run commands from the project root and use:

```bash
php intisari
```

### Composer Script Not Found

Confirm `composer.json` contains the expected script name.

```bash
composer console
```

### PHP Cannot Open `intisari`

Make sure you are in the project root where the `intisari` file exists.

### Console Command Not Registered

For closure commands, confirm the command is defined in `routes/console.php`.

### Permission Issue

If executing the file directly fails, use PHP explicitly:

```bash
php intisari serve
```

## Next Steps

Continue with [Testing](../testing/index.md).
