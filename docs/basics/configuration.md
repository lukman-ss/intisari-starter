# Configuration

IntisariPHP Starter uses a simple configuration system. Configuration files live in `config/` and return PHP arrays. Environment-specific values are stored in `.env` and read by the config files at runtime.

## Configuration Files

| File | Purpose |
|------|---------|
| `config/app.php` | Application name, environment, debug, URL, timezone, locale, providers, middleware |
| `config/database.php` | Database connections (SQLite, MySQL, PostgreSQL) |
| `config/session.php` | Session driver and lifetime |

Each config file returns a PHP array:

```php
// config/app.php (excerpt)
return [
    'name' => $env('APP_NAME', 'Intisari App'),
    'env' => $env('APP_ENV', 'local'),
    'debug' => filter_var($env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
];
```

## The .env File

The `.env` file stores values that differ between environments (local, staging, production). It is loaded at runtime and read by config files.

### Creating Your .env File

Copy the template to create your local environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### The .env.example File

The `.env.example` file is the template that ships with the starter. It contains all available environment variables with sensible defaults:

```env
APP_NAME="Intisari App"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=en

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

Commit `.env.example` to version control so other developers know which variables are available.

## Environment Variables

| Variable | Purpose | Default |
|----------|---------|---------|
| `APP_NAME` | Application display name shown in views and emails | `Intisari App` |
| `APP_ENV` | Environment name (`local`, `staging`, `production`) | `local` |
| `APP_DEBUG` | Enable detailed error output. **Must be `false` in production.** | `true` |
| `APP_URL` | Base application URL used for links and redirects | `http://127.0.0.1:8000` |
| `APP_TIMEZONE` | Application timezone for date/time functions | `Asia/Jakarta` |
| `APP_LOCALE` | Application locale for language and formatting | `en` |
| `DB_CONNECTION` | Default database driver (`sqlite`, `mysql`, `pgsql`) | `sqlite` |
| `DB_DATABASE` | Database name or SQLite file path | `database/database.sqlite` |
| `SESSION_DRIVER` | Session storage driver (`file`) | `file` |
| `SESSION_LIFETIME` | Session lifetime in minutes | `120` |

## How Config Files Read Environment Values

Each config file defines a local `$env` closure that reads from `$_ENV` and `$_SERVER`:

```php
$env = static function (string $key, mixed $default = null): mixed {
    return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
};

return [
    'name' => $env('APP_NAME', 'Intisari App'),
    'env' => $env('APP_ENV', 'local'),
];
```

If a variable is not set in `.env`, the default value is used.

## Local Configuration

For local development, use these settings:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Key points:

- **APP_DEBUG=true** — shows detailed error messages for easier debugging
- **APP_URL=http://127.0.0.1:8000** — matches the development server address
- **DB_CONNECTION=sqlite** — no database server needed for local development

## Production Configuration

For production, change these critical settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_DATABASE=your_database_name
```

### Critical Production Rules

**1. Never commit `.env` to version control**

The `.gitignore` file included with the starter already excludes `.env`. This prevents secrets (database passwords, API keys) from being exposed in your repository.

```gitignore
# Already included in .gitignore
.env
```

**2. Set `APP_DEBUG=false` in production**

Debug mode exposes sensitive information (stack traces, environment variables, database queries) to users. This is a critical security risk.

```env
# WRONG — in production
APP_DEBUG=true

# CORRECT — in production
APP_DEBUG=false
```

**3. Set `APP_URL` to the real domain in production**

The `APP_URL` value is used for generating links and redirects. If it doesn't match your actual domain, links will be broken.

```env
# WRONG — in production
APP_URL=http://127.0.0.1:8000

# CORRECT — in production
APP_URL=https://yourdomain.com
```

## Configuration Cache

Cache all configuration into a single file for faster loading in production:

```bash
php intisari config:cache
```

This creates `storage/cache/config.php` with all configuration values pre-resolved.

Clear the cache after changing `.env` or config files:

```bash
php intisari config:clear
```

**Tip:** Run `config:cache` as part of your deployment process. Run `config:clear` whenever you update environment variables.

## Security Checklist

Before deploying to production:

- [ ] `.env` is not committed to version control
- [ ] `APP_DEBUG=false` is set
- [ ] `APP_URL` matches the production domain
- [ ] Database credentials are secure
- [ ] `storage/` directory is not publicly accessible
- [ ] Configuration cache is enabled (`config:cache`)

## Next

Continue to [Database](../database/index.md).
