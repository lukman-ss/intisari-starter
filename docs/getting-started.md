# Getting Started with IntisariPHP Starter

This guide creates a local IntisariPHP Starter project with Composer.

## Prerequisites

Install PHP 8.2 or newer and Composer 2.x. See [Requirements](intro/requirements.md) for verification commands and recommended extensions.

## Install and Run

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
composer test
```

On Windows PowerShell, replace the `cp` command with:

```powershell
Copy-Item .env.example .env
```

`composer serve` starts the local development server at `http://127.0.0.1:8000`. Stop it with `Ctrl+C`, then run `composer test` if both commands cannot share the same terminal session.

## First Files to Review

- `routes/web.php` defines HTTP routes.
- `app/Controllers/` contains controllers.
- `resources/views/` contains view templates.
- `config/` contains application configuration.
- `.env` contains local environment values and should not be committed.

## Next

Continue to the [Installation Overview](installation/index.md).
