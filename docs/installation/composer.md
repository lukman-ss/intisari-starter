# Composer Installation

Composer creates the project and installs its dependencies.

## Create the Project

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
```

`create-project` downloads the starter package and runs dependency installation in the new directory. If you already have a project checkout, run `composer install` inside it to install the exact dependency versions recorded in `composer.lock`.

## Environment File

On macOS or Linux:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Keep `.env` local because it may contain environment-specific or sensitive values.

## Autoloading

Composer configures PSR-4 autoloading for `App\` from `app/` and `Tests\` from `tests/`. After adding or moving classes, regenerate the autoloader when necessary:

```bash
composer dump-autoload
```

Class namespaces and filenames must match the configured directory structure and filesystem casing.

## Common Composer Errors

- **PHP version mismatch:** run `php -v` and use PHP 8.2 or newer.
- **Composer is unavailable:** run `composer -V`; install Composer 2.x if the command is missing.
- **Missing PHP extension:** read Composer's error, enable the named extension, then rerun the command.
- **Permission denied:** create the project in a writable directory and verify Composer can write its cache and `vendor/`.
- **Dependency resolution failure:** use the committed `composer.lock` with `composer install`; do not delete it as a first troubleshooting step.

See [Installation Troubleshooting](troubleshooting.md) for application startup issues.

## Next

Continue to [Running the Application](running.md).
