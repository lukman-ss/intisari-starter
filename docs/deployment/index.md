# Deployment

Deploying an IntisariPHP application to production follows standard PHP deployment practices. This guide covers the essential steps to ensure a secure and functional production environment.

## Production Requirements

Before deploying, verify your server has:

- **PHP 8.2 or newer** with required extensions (`mbstring`, `openssl`, `pdo`, `tokenizer`, `json`)
- **Composer** installed for dependency management
- **Web server** (Nginx or Apache) configured with PHP-FPM
- **Database** (MySQL, PostgreSQL, or SQLite) configured and accessible
- **Writable storage directories** for logs, cache, and sessions

## Production Checklist

1. Install production dependencies
2. Create production `.env` manually on the server
3. Point the web server document root to `public/`
4. Ensure `storage/` is writable
5. Set `APP_DEBUG=false`
6. Cache configuration
7. Verify file permissions
8. Enable HTTPS

## Install Production Dependencies

Install only production dependencies with an optimized autoloader:

```bash
composer install --no-dev --optimize-autoloader
```

This command:

- Skips development dependencies (PHPUnit, testing tools)
- Generates an optimized classmap autoloader for faster class loading
- Reduces the vendor directory size

**Never run `composer install` without `--no-dev` in production.** Development tools increase attack surface and memory usage.

## Environment Configuration

Create `.env` **manually on the production server**. Do not copy your local `.env` file — production values must be different from development.

```env
APP_NAME="Your App Name"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=en

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=app_user
DB_PASSWORD=strong_secure_password

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### Critical Production Settings

**APP_DEBUG=false** — This is the most important setting. Debug mode exposes stack traces, environment variables, and sensitive data to users. This is a critical security vulnerability.

**APP_URL** — Must match your production domain exactly, including the protocol (`https://`). Incorrect URLs break links, redirects, and asset paths.

**Database credentials** — Use strong passwords and a dedicated database user with minimal privileges.

### Cache Configuration

After setting environment values, cache the configuration for faster loading:

```bash
php intisari config:cache
```

Clear the cache whenever you change environment values:

```bash
php intisari config:clear
php intisari config:cache
```

## Web Server Document Root

**The document root must point to `public/`, never the project root.**

```text
/path/to/app/public    ← CORRECT
/path/to/app           ← WRONG — exposes sensitive files
```

The `public/` directory contains only `index.php` and static assets. The project root contains `.env`, `config/`, `storage/`, and other sensitive files that must never be accessible via the web.

### Why This Matters

If the document root points to the project root:

- `.env` could be downloaded (contains database passwords)
- `config/` files could be read (contains application logic)
- `storage/logs/` could be accessed (contains error details)
- `composer.json` could be read (reveals dependencies and versions)

## Basic Nginx + PHP-FPM Configuration

```nginx
server {
    listen 80;
    server_name example.com;
    
    # Document root MUST point to public/
    root /var/www/my-app/public;
    index index.php index.html;

    # Handle all requests through front controller
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Pass PHP files to PHP-FPM
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to hidden files (.env, .git, etc.)
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### HTTPS Configuration

In production, always use HTTPS. Add a redirect from HTTP and configure SSL:

```nginx
server {
    listen 80;
    server_name example.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name example.com;

    ssl_certificate /etc/ssl/certs/example.com.crt;
    ssl_certificate_key /etc/ssl/private/example.com.key;

    root /var/www/my-app/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Storage Directory

The `storage/` directory must be writable by the web server user (typically `www-data` or `nginx`):

```text
storage/
  cache/           Configuration and data cache
  logs/            Application log files
  framework/       Sessions, compiled views, and framework files
```

Set permissions:

```bash
# Create directories if they don't exist
mkdir -p storage/cache storage/logs storage/framework

# Set ownership to web server user
sudo chown -R www-data:www-data storage/

# Set permissions (writable by owner, readable by group)
sudo chmod -R 775 storage/
```

**Important:** The `storage/` directory must never be inside the document root. It should be outside `public/` so it cannot be accessed via the web.

## File Permissions

Restrict write access to only the directories that need it:

| Directory | Permission | Reason |
|-----------|:---:|--------|
| `storage/cache/` | Writable | Configuration and data cache |
| `storage/logs/` | Writable | Application log files |
| `storage/framework/` | Writable | Sessions and framework files |
| `public/` | Read-only | Static assets and front controller |
| `app/` | Read-only | Application source code |
| `config/` | Read-only | Configuration files |
| `routes/` | Read-only | Route definitions |
| `vendor/` | Read-only | Composer dependencies |

**Do not make the entire project writable.** Only `storage/` needs write access.

## Security Checklist

Before going live, verify every item:

- [ ] **APP_DEBUG=false** — debug mode is disabled
- [ ] **APP_URL** — matches production domain with HTTPS
- [ ] **.env not in version control** — `.gitignore` excludes `.env`
- [ ] **Document root is `public/`** — project root is not web-accessible
- [ ] **storage/ is writable** — web server can write logs and cache
- [ ] **storage/ is not web-accessible** — outside document root
- [ ] **HTTPS enabled** — SSL certificate configured
- [ ] **Production dependencies only** — `composer install --no-dev` used
- [ ] **Configuration cached** — `php intisari config:cache` run
- [ ] **Database credentials secure** — strong password, minimal privileges
- [ ] **Hidden files blocked** — Nginx denies access to `.env`, `.git`
- [ ] **PHP version supported** — PHP 8.2+ with required extensions

## Deployment Workflow

A typical deployment workflow:

```bash
# 1. Pull latest code
git pull origin main

# 2. Install production dependencies
composer install --no-dev --optimize-autoloader

# 3. Verify .env exists and has production values
cat .env | grep APP_DEBUG  # should show false

# 4. Ensure storage is writable
chmod -R 775 storage/

# 5. Cache configuration
php intisari config:cache

# 6. Restart PHP-FPM to clear opcache
sudo systemctl restart php8.2-fpm
```

## Common Deployment Mistakes

### Leaving APP_DEBUG=true in production

This exposes sensitive information to users. Always verify:

```bash
grep APP_DEBUG .env
# Should show: APP_DEBUG=false
```

### Pointing document root to project root

This exposes `.env`, `config/`, and other sensitive files. Always point to `public/`:

```nginx
root /var/www/my-app/public;    # Correct
root /var/www/my-app;           # WRONG
```

### Copying local .env to production

Local `.env` contains development values. Create production `.env` manually on the server with production-specific values.

### Running composer install without --no-dev

Development dependencies increase memory usage and attack surface. Always use:

```bash
composer install --no-dev --optimize-autoloader
```

### Forgetting to cache configuration

Without configuration caching, every request reads all config files from disk. Cache after deployment:

```bash
php intisari config:cache
```

## Next

Continue to [Security](../security/index.md).
