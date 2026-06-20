# Configuration

## `config/` Directory

The starter loads PHP configuration files from `config/`. Each file returns an array and is stored under a key matching its filename.

```text
config/
├── app.php
├── database.php
└── session.php
```

For example, values returned by `config/app.php` are available under the `app` configuration key.

## `.env` File

The `.env` file contains values that vary between environments. `bootstrap/app.php` loads it when the file exists, before loading the configuration directory.

Create the local file from the template:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Do not commit `.env`. It may contain credentials and machine-specific settings.

## `.env.example`

`.env.example` documents every environment variable read by the starter configuration files. Keep example values safe and update the template whenever config files add or remove a variable.

The default local connection remains SQLite. Server database values are present for MySQL or PostgreSQL and are unused while `DB_CONNECTION=sqlite`.

## Application Configuration

`config/app.php` reads these variables:

| Variable | Default | Configuration value |
| --- | --- | --- |
| `APP_NAME` | `Intisari App` | Application name |
| `APP_ENV` | `local` | Environment name |
| `APP_DEBUG` | `true` | Debug configuration flag |
| `APP_URL` | `http://127.0.0.1:8000` | Application URL |
| `APP_TIMEZONE` | `Asia/Jakarta` | Application timezone setting |
| `APP_LOCALE` | `en` | Application locale setting |

The same file also returns `providers` and `middleware` arrays. In the current starter bootstrap, those arrays are configuration values and are not automatically registered at runtime.

## Database Configuration

`config/database.php` defines SQLite, MySQL, and PostgreSQL connection arrays.

### SQLite

SQLite is the default local connection and uses only:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

`DB_DATABASE` is a filesystem path for SQLite.

### MySQL

Use the server database variables when selecting MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=intisari
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

### PostgreSQL

Set values appropriate for PostgreSQL when selecting `pgsql`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=intisari
DB_USERNAME=postgres
DB_PASSWORD=
DB_CHARSET=utf8
```

The server database variables are not required for SQLite.

## Session Configuration

`config/session.php` exists and is read by the core session service provider. It uses:

| Variable | Default | Purpose |
| --- | --- | --- |
| `SESSION_DRIVER` | `file` | Session storage driver |
| `SESSION_LIFETIME` | `120` | Session lifetime in minutes |

The file driver stores session data under `storage/framework/sessions`. Keep this path writable and outside the public web root.

## Local and Production Configuration

Local development defaults:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Production values must be set for the deployed environment:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
```

Use production-specific database credentials and never commit them. Keep `APP_TIMEZONE=Asia/Jakarta` unless the application intentionally uses another timezone setting.

## Configuration Cache Command

The starter registers these commands:

```bash
php intisari config:cache
php intisari config:clear
```

`config:cache` writes the loaded configuration to `storage/cache/config.php`. `config:clear` removes that file.

The current `bootstrap/app.php` loads the `config/` directory directly and does not read `storage/cache/config.php`. Treat the cache command as available tooling, not as an active runtime optimization, until bootstrap cache loading is implemented.

## Common Mistakes

### Missing `.env`

Copy `.env.example` to `.env` before customizing local values.

### Committing Secrets

Commit `.env.example`, not `.env`. Do not place real database passwords in documentation or committed configuration.

### Using Server Variables for SQLite

SQLite uses `DB_CONNECTION` and `DB_DATABASE`. Host, port, username, password, and charset settings apply to MySQL or PostgreSQL.

### Wrong Database Port or Charset

Update `DB_PORT`, `DB_USERNAME`, and `DB_CHARSET` when switching between MySQL and PostgreSQL. Values copied for one server are not automatically changed for another.

### Assuming Config Arrays Are Runtime Registration

The current bootstrap explicitly registers `AppServiceProvider`; it does not automatically consume the `providers` or `middleware` arrays from `config/app.php`.

### Assuming the Cache Is Loaded

Creating `storage/cache/config.php` does not change the current bootstrap loading path. Clear stale cache files when reviewing configuration behavior.

## Next

Continue to [Error Handling](error-handling.md).
