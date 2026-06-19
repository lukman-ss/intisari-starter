# Configuration

Configuration files define application settings outside of controllers, routes, and views.

IntisariPHP Starter stores configuration files in `config/`. Each file returns a PHP array and can read values from environment variables.

The starter loads `.env` from the project root when it exists, then loads configuration from the `config/` directory during bootstrap.

## The `config/` Folder

The `config/` folder contains application configuration files.

Common files include:

```text
config/app.php
config/database.php
config/session.php
```

The main application configuration is `config/app.php`:

```php
return [
    'name' => $env('APP_NAME', 'Intisari App'),
    'env' => $env('APP_ENV', 'local'),
    'debug' => filter_var($env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
    'url' => $env('APP_URL', 'http://localhost:8000'),
    'timezone' => $env('APP_TIMEZONE', 'Asia/Jakarta'),
    'locale' => $env('APP_LOCALE', 'en'),
];
```

It also lists application service providers and middleware configured by the starter.

## `.env` and `.env.example`

The `.env` file stores environment-specific values for one machine or server.

The `.env.example` file is the shared template. Copy it before running the application:

```bash
cp .env.example .env
```

Do not commit `.env` to version control.

## Environment Variables

| Variable | Purpose | Default in Template |
| --- | --- | --- |
| `APP_NAME` | Application display name | `Intisari App` |
| `APP_ENV` | Current environment name | `local` |
| `APP_DEBUG` | Enables detailed error output | `true` |
| `APP_URL` | Base application URL | `http://localhost:8000` |
| `APP_TIMEZONE` | Application timezone | `Asia/Jakarta` |
| `APP_LOCALE` | Application locale | `en` |
| `DB_CONNECTION` | Default database connection | `sqlite` |
| `DB_DATABASE` | Database name or SQLite path | `database/database.sqlite` |
| `SESSION_DRIVER` | Session storage driver | `file` |
| `SESSION_LIFETIME` | Session lifetime in minutes | `120` |

## Minimal `.env`

```env
APP_NAME="Intisari App"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=en

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## Local vs Production Configuration

Local configuration can enable debugging:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

Production configuration must disable debug output and use the real production domain:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
```

Set `APP_URL` to the actual production domain. Do not leave it as `localhost` in production.

The starter defaults to `Asia/Jakarta` for `APP_TIMEZONE` when no environment value is provided.

## Configuration Principles

- Do not commit `.env` to version control.
- Use `.env.example` as the shared template.
- Set `APP_DEBUG=false` in production.
- Set `APP_URL` to the correct production domain.
- Keep production secrets out of documentation and source code.
- Clear cached configuration after changing environment values.

## Configuration Cache

Cache configuration for a deployed or optimized environment:

```bash
php intisari config:cache
```

Clear the cache when configuration changes:

```bash
php intisari config:clear
```

## Troubleshooting

### Config Not Loaded

Confirm `.env` exists in the project root and that configuration files are in `config/`.

If configuration was cached before a change, clear it:

```bash
php intisari config:clear
```

### Env Typo

Environment variable names must match the keys used by the config files.

```env
APP_DEBUG=true
```

`APP_DEBIG=true` will not be read by `config/app.php`.

### Debug Still Enabled

Check both `.env` and any cached configuration.

```env
APP_DEBUG=false
```

Then clear and rebuild config cache if needed.

### Wrong App URL

If generated links or environment-specific values point to the wrong host, check `APP_URL`.

```env
APP_URL=http://127.0.0.1:8000
```

## Next Steps

Continue with [Database](../database/index.md).
