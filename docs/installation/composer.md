# Composer Installation

Composer is the standard way to install IntisariPHP Starter.

The `create-project` command downloads the starter, installs dependencies, and prepares the project for local development.

## Create a Project

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
```

## Copy the Environment File

```bash
cp .env.example .env
```

On Windows PowerShell, use:

```powershell
Copy-Item .env.example .env
```

## About `.env`

The `.env` file stores local environment values such as application name, environment, debug mode, application URL, database connection, and session settings.

Do not commit machine-specific or secret values from `.env` to version control. Use `.env.example` as the shared template.

## Common Installation Problems

### PHP Version Too Old

The starter requires PHP `>=8.2`.

```bash
php -v
```

Upgrade PHP if the installed version is lower than 8.2.

### Composer Not Installed

If `composer` is not recognized, install Composer and make sure it is available in your terminal path.

```bash
composer -V
```

### Permission Issue

Permission errors usually happen when the current user cannot write to the target directory.

Create the project in a writable directory, or adjust permissions before running Composer again.

### Missing PHP Extension

Composer may fail when a required PHP extension is missing.

```bash
php -m
```

Enable the missing extension in your PHP configuration, then rerun the Composer command.

## Next Steps

Continue with [Running the Application](running.md).
