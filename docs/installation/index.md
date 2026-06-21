# Installation

IntisariPHP Starter is installed through Composer. No custom installer is required.

## Recommended Path

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
composer test
```

On Windows PowerShell, create the environment file with:

```powershell
Copy-Item .env.example .env
```

Run `composer test` after stopping the development server, or use a second terminal.

## Local Development Assumptions

- PHP 8.2 or newer and Composer 2.x are available on the command line.
- The project directory is writable.
- Port `8000` is available, or another port is selected.
- `.env` contains local values based on `.env.example`.
- The default PHP development server is used only for local development.

For command details, see [Composer Installation](composer.md) and [Running the Application](running.md).

## Next

Continue to [Composer Installation](composer.md).
