# Installation Troubleshooting

Use these checks when a local installation does not run as expected.

## PHP Is Too Old

Run `php -v`. The active command-line PHP version must be 8.2 or newer. If several PHP versions are installed, verify the terminal resolves the intended executable.

## Composer Is Missing

Run `composer -V`. Install Composer 2.x or correct your `PATH` if the command is not found.

## Environment File Is Missing

On macOS or Linux:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

## Port Is Already in Use

Select another supported port:

```bash
php intisari serve --port=8080
```

## SQLite File Is Missing

The default configuration expects `database/database.sqlite`. Create an empty file on macOS or Linux:

```bash
touch database/database.sqlite
```

On Windows PowerShell:

```powershell
New-Item -ItemType File -Force database/database.sqlite
```

Confirm `.env` contains `DB_CONNECTION=sqlite` and the correct `DB_DATABASE` path.

## Storage Is Not Writable

Verify the current user can create files under `storage/`. Create missing runtime directories if needed:

```bash
mkdir -p storage/cache storage/logs storage/framework
```

On Windows PowerShell:

```powershell
New-Item -ItemType Directory -Force storage/cache, storage/logs, storage/framework
```

## Blank Page

Check the terminal running the server and application logs under `storage/logs/`. For local diagnosis, set these values in `.env`:

```env
APP_ENV=local
APP_DEBUG=true
```

Do not enable `APP_DEBUG` in production because detailed errors may expose internal information.

## Route Not Found

Confirm the path and HTTP method are registered in `routes/web.php`, then list registered routes:

```bash
php intisari route:list
```

Route paths are case-sensitive. Also confirm the URL uses the same host and port as the running server.

## Next

Continue to [Application Overview](../overview/index.md).
