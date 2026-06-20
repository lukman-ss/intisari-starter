# Deployment

Deploying an IntisariPHP application to production follows standard PHP deployment practices. This guide covers the essential steps to ensure a secure, high-performance, and functional production environment.

## Production Requirements

Before deploying, verify your server has:

- **PHP 8.2 or newer** with required extensions (`mbstring`, `openssl`, `pdo`, `tokenizer`, `json`)
- **Composer** installed for dependency management
- **Web server** (Nginx or Apache) configured with PHP-FPM
- **Database** (MySQL, PostgreSQL, or SQLite) configured and accessible
- **Writable storage directories** for logs, cache, and sessions

---

## Production Checklist

- [ ] **Install production dependencies** — `composer install --no-dev --optimize-autoloader`
- [ ] **Create production `.env` manually** — Do not commit `.env` to git
- [ ] **Point document root to `public/`** — Project root must not be web-accessible
- [ ] **Set `APP_DEBUG=false`** — Prevents security disclosures on errors
- [ ] **Ensure `storage/` is writable** — Web server user needs write permission
- [ ] **Secure with HTTPS** — Set SSL certificate and HTTP redirects
- [ ] **Verify file permissions** — Restrict write access to only `storage/`
- [ ] **Clear or configure configuration cache** — Ensure cached config matches `.env`

---

## Install Production Dependencies

Install only production dependencies with an optimized autoloader:

```bash
composer install --no-dev --optimize-autoloader
```

This command:
- Skips development dependencies (PHPUnit, testing tools).
- Generates an optimized classmap autoloader for faster class loading.
- Reduces the vendor directory size.

> [!WARNING]
> Never run `composer install` without `--no-dev` in production. Development tools increase the server's attack surface and memory usage.

---

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

* **`APP_DEBUG=false`**: This is the most important setting. Debug mode exposes stack traces, environment variables, and sensitive data to users, which is a critical security vulnerability.
* **`APP_URL`**: Must match your production domain exactly, including the protocol (`https://`).
* **Database credentials**: Use strong passwords and a dedicated database user with minimal privileges.

---

## Configuration Caching

The CLI utility provides a command to compile and cache configurations:

```bash
php intisari config:cache
```

This compiles your application configuration files into a single array cache file at `storage/cache/config.php`.

> [!IMPORTANT]
> By default, the `bootstrap/app.php` file in the starter loads configuration directly from the config directory files (e.g. `config/*.php`). It does **not** automatically read from the compiled `storage/cache/config.php` file.
> 
> If you wish to enable configuration caching, you must manually update your [bootstrap/app.php](file:///d:/PHP%20PACKAGIST/intisari-starter/bootstrap/app.php) to load the cache if it exists, for example:
>
> ```php
> $cacheFile = $app->storagePath('cache/config.php');
> if (is_file($cacheFile)) {
>     $app->config()->loadCache($cacheFile);
> } else {
>     $app->loadConfiguration($app->configPath());
> }
> ```
> 
> Otherwise, running `config:cache` will write the cache file but it will not be loaded during HTTP or CLI bootstrapping.

Clear the cache whenever you change environment values:

```bash
php intisari config:clear
```

---

## Web Server Document Root

**The document root must point to `public/`, never the project root.**

```text
/path/to/app/public    ← CORRECT
/path/to/app           ← WRONG (exposes sensitive files)
```

The `public/` directory contains only `index.php` and static assets. The project root contains `.env`, `config/`, `storage/`, and other sensitive files that must never be accessible via the web.

---

## Web Server Configurations

### Nginx + PHP-FPM Configuration

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

### Apache Configuration

The starter includes a `public/.htaccess` file in the [public/](file:///d:/PHP%20PACKAGIST/intisari-starter/public/) directory to automatically route requests to `index.php` using `mod_rewrite`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>
```

Make sure the Apache host configuration has `AllowOverride All` enabled for the directory to allow `.htaccess` overrides.

---

## Directory Permissions

The `storage/` directory must be writable by the web server user (typically `www-data` or `nginx`):

```text
storage/
  ├── cache/           Configuration and data cache
  ├── logs/            Application log files
  └── framework/       Sessions and framework files
```

Set permissions on Linux/macOS:

```bash
# Create directories if they don't exist
mkdir -p storage/cache storage/logs storage/framework

# Set ownership to web server user
sudo chown -R www-data:www-data storage/

# Set permissions (writable by owner, readable/writable by group)
sudo chmod -R 775 storage/
```

### File Write Policy

| Directory | Permission | Reason |
| :--- | :---: | :--- |
| `storage/cache/` | Writable | Configuration and data cache |
| `storage/logs/` | Writable | Application log files |
| `storage/framework/` | Writable | Sessions and framework files |
| `public/` | Read-only | Static assets and front controller |
| `app/` | Read-only | Application source code |
| `config/` | Read-only | Configuration files |
| `routes/` | Read-only | Route definitions |
| `vendor/` | Read-only | Composer dependencies |

> [!WARNING]
> Do not make the entire project directory writable. Only the folders under `storage/` need write access.

---

## Deployment Workflow

A typical deployment workflow on the server:

```bash
# 1. Pull latest code
git pull origin main

# 2. Install production dependencies
composer install --no-dev --optimize-autoloader

# 3. Verify .env exists and has production values
grep APP_DEBUG .env  # should show APP_DEBUG=false

# 4. Ensure storage is writable
chmod -R 775 storage/

# 5. Clear old configuration cache
php intisari config:clear

# 6. Restart PHP-FPM to clear OPCache
sudo systemctl restart php8.2-fpm
```

---

## Rollback Workflow

If a deployment fails or introduces a critical bug, execute the following rollback steps:

1. **Revert the code**: Reset the branch or checkout the previous stable git commit/tag:
   ```bash
   git checkout <stable-tag-or-commit-hash>
   ```
2. **Re-install dependencies**: Run Composer to align dependencies with the reverted `composer.lock`:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. **Clear configuration cache**: If config files changed or caching is customized, run:
   ```bash
   php intisari config:clear
   ```
4. **Restart Services**: Restart PHP-FPM or Apache to clear PHP's OPCache and internal file caches:
   ```bash
   sudo systemctl restart php8.2-fpm
   ```

---

## Common Deployment Mistakes

### Leaving `APP_DEBUG=true` in Production
Exposes sensitive stack traces and credentials. Always verify it is set to `false`.

### Pointing Document Root to Project Root
Exposes `.env`, `config/`, and other sensitive files. Always point the web server config to `public/`.

### Copying Local `.env` to Production
Local configuration files reference local resources and tools. Always write production credentials manually in the production `.env`.

### Running Composer Install without `--no-dev`
Development dependencies increase memory usage and code footprint on the server. Always run with `--no-dev --optimize-autoloader`.

---

## Next

Continue to the [Security Documentation](../security/index.md).
