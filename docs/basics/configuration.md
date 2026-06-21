# Configuration

## `config/` Directory

The starter loads PHP files from `config/`. Each file returns an array stored under a key matching its filename:

```text
config/
|-- app.php
|-- database.php
`-- session.php
```

For example, `config/app.php` provides values under the `app` configuration key.

## `.env`

`bootstrap/app.php` loads `.env` when the file exists, before it loads `config/`. Create it from the committed template:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Keep `.env` out of version control because it can contain credentials and machine-specific values.

## `.env.example`

`.env.example` lists the environment variables used by the starter config files. Keep its values safe and update it whenever those files add or remove a variable.

## Application Configuration

`config/app.php` reads:

| Variable | Default | Purpose |
| --- | --- | --- |
| `APP_NAME` | `Intisari App` | Application name |
| `APP_ENV` | `local` | Environment name |
| `APP_DEBUG` | `true` | Debug flag converted to boolean |
| `APP_URL` | `http://127.0.0.1:8000` | Application URL |
| `APP_TIMEZONE` | `Asia/Jakarta` | Application timezone setting |
| `APP_LOCALE` | `en` | Application locale setting |

The file also defines provider and middleware class lists. The current bootstrap registers `AppServiceProvider` directly and does not automatically consume those lists.

## Database Configuration

`config/database.php` defines `sqlite`, `mysql`, and `pgsql` connection arrays.

SQLite uses:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

MySQL and PostgreSQL also use these supported variables:

| Variable | MySQL default | PostgreSQL default |
| --- | --- | --- |
| `DB_HOST` | `127.0.0.1` | `127.0.0.1` |
| `DB_PORT` | `3306` | `5432` |
| `DB_DATABASE` | `intisari` | `intisari` |
| `DB_USERNAME` | `root` | `postgres` |
| `DB_PASSWORD` | empty | empty |
| `DB_CHARSET` | `utf8mb4` | `utf8` |

Set `DB_CONNECTION=mysql` or `DB_CONNECTION=pgsql` before using server database values. Host, port, username, password, and charset are not used by the SQLite connection array.

## Session Configuration

`config/session.php` reads:

| Variable | Default | Configured value |
| --- | --- | --- |
| `SESSION_DRIVER` | `file` | Session storage driver name |
| `SESSION_LIFETIME` | `120` | Integer session lifetime |

It also defines `storage/framework/sessions` as the file path. These values are core-dependent configuration: the starter reserves and exposes them, while actual session behavior depends on the installed session integration. Keep the storage path writable and outside `public/`.

## Local vs. Production

A typical local configuration uses:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

For production, set environment-specific values, use real database credentials where required, and disable detailed debugging:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
```

Do not commit production credentials. The web server must expose only `public/`.

## Config Cache

The starter provides:

```bash
php intisari config:cache
php intisari config:clear
```

`config:cache` loads `config/` and writes `storage/cache/config.php`. `config:clear` removes that file.

The current `bootstrap/app.php` still loads the `config/` directory directly and does not read the generated cache file. The commands are available, but the cache is not an active bootstrap optimization in this starter.

## Common Mistakes

- Customizing `.env.example` instead of creating a local `.env`.
- Committing `.env` or real credentials.
- Adding an environment variable without reading it in a config file.
- Using MySQL or PostgreSQL host settings while `DB_CONNECTION=sqlite`.
- Reusing the wrong port or charset when changing database drivers.
- Assuming provider or middleware arrays are automatically registered.
- Assuming `SESSION_DRIVER` guarantees a driver is active without the required core integration.
- Assuming `config:cache` changes the current bootstrap loading path.

## Next

Continue to [Error Handling](error-handling.md).
