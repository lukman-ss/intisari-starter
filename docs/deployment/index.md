# Deployment

Deploy IntisariPHP Starter as a standard PHP application. Review the target environment and application requirements before making it public.

## Production Requirements

- PHP 8.2 or newer with the extensions required by the application and selected database driver.
- Composer 2.x during the build or deployment step.
- A web server configured to pass `public/index.php` to PHP-FPM.
- Production database access when the application uses a server database.
- Write access to the required paths under `storage/`.
- TLS termination for public traffic.

The starter does not require a queue worker, scheduler, migration step, or container runtime.

## Install Production Dependencies

Install versions from `composer.lock` without development packages:

```bash
composer install --no-dev --optimize-autoloader
```

Run this from a clean release directory. Do not run `composer update` as an unreviewed production deployment step.

## Production `.env`

Create `.env` on the target environment with production-specific values. Do not commit it or copy local credentials unchanged.

```env
APP_NAME="My Application"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=en

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app_database
DB_USERNAME=app_user
DB_PASSWORD=replace-with-a-secret
DB_CHARSET=utf8mb4

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

Use only variables read by the starter config files. Restrict access to `.env` and provide the database user only the privileges the application needs.

## Disable Debug Output

Production configuration must contain:

```env
APP_ENV=production
APP_DEBUG=false
```

The current bootstrap loads `app.debug` but does not automatically pass it to the core exception handler, whose default is non-debug. Keep `APP_DEBUG=false` and never add public stack traces, exception messages, credentials, or filesystem paths as a diagnostic shortcut.

## Use `public/` as the Document Root

The web server document root must be the absolute `public/` directory:

```text
/var/www/my-app/public
```

Never serve the project root. `.env`, `config/`, `storage/`, `vendor/`, source code, tests, and documentation must not be directly web-accessible.

## Keep `storage/` Writable and Private

Create the runtime directories and grant the PHP-FPM user the minimum required write access:

```bash
mkdir -p storage/cache storage/logs storage/framework
chown -R www-data:www-data storage
chmod -R 775 storage
```

Adjust the user, group, and permission mode for the hosting environment. Do not make the whole project writable. `storage/` must remain outside the document root.

## Config Cache

The commands are available:

```bash
php intisari config:cache
php intisari config:clear
```

`config:cache` writes `storage/cache/config.php`, and `config:clear` removes it. The current `bootstrap/app.php` loads `config/` directly and does not read the generated cache.

Do not treat this cache as an active production optimization unless bootstrap loading is deliberately implemented and tested.

## Nginx and PHP-FPM Example

Replace the domain, project path, and PHP-FPM socket with values for the server:

```nginx
server {
    listen 80;
    server_name example.com;

    root /var/www/my-app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ ^/index\.php(/|$) {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root/index.php;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }

    location ~ /\. {
        deny all;
    }
}
```

Validate Nginx configuration before reloading it. Confirm `/`, `/health`, and `/status` through the deployed hostname.

## HTTPS

Use a valid certificate and redirect HTTP traffic to HTTPS at Nginx, a reverse proxy, or the hosting platform. Set `APP_URL` to the public HTTPS URL. Certificate issuance and renewal are deployment-environment responsibilities.

## Deployment Checklist

- [ ] PHP version and required extensions are available.
- [ ] Reviewed code and `composer.lock` are in the release.
- [ ] `composer install --no-dev --optimize-autoloader` succeeds.
- [ ] Production `.env` exists and is not public.
- [ ] `APP_ENV=production` and `APP_DEBUG=false` are set.
- [ ] Database credentials and connectivity are verified when used.
- [ ] Nginx root points to the release's `public/` directory.
- [ ] Required `storage/` paths exist, are writable, and are private.
- [ ] Nginx and PHP-FPM configuration validates and reloads successfully.
- [ ] HTTPS and redirects are active.
- [ ] `/health` returns the expected response.
- [ ] A rollback target is known before traffic is switched.

## Rollback

Keep the previous known-good release and its matching `composer.lock`. If verification fails, switch traffic or the release symlink back.

Run `composer install --no-dev --optimize-autoloader` in that release if needed, clear only application caches affected by the deployment, and reload PHP-FPM safely.

Do not delete the previous release until the new release has been verified. Database rollback is not documented because this starter has no migration system.

## Common Mistakes

- Serving the project root instead of `public/`.
- Leaving `APP_DEBUG=true` or exposing exception details publicly.
- Committing or publicly serving `.env`.
- Running `composer update` directly in production without reviewing dependency changes.
- Installing development dependencies in the production release.
- Making the entire project writable instead of only required storage paths.
- Assuming `config:cache` is loaded by the current bootstrap.
- Using an Nginx PHP-FPM socket that does not match the installed PHP version.
- Skipping HTTPS or deployment health checks.
- Adding migration, queue, or scheduler steps that the starter does not implement.

## Next

Continue to [Security](../security/index.md).
