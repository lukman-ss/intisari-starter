# Security

IntisariPHP Starter provides a minimal application base. Security controls that are not present must be designed and verified for the application.

## Environment Files

Create `.env` from `.env.example` for each environment. Commit only `.env.example` with safe placeholders.

```bash
cp .env.example .env
```

Keep `.env` out of version control and restrict filesystem access. Do not hardcode production secrets in PHP source, documentation, or tracked configuration files.

## Debug Mode

Local development may use:

```env
APP_ENV=local
APP_DEBUG=true
```

Production must use:

```env
APP_ENV=production
APP_DEBUG=false
```

Do not add stack traces, exception messages, credentials, SQL, or filesystem paths to public error responses.

## Public Directory

Configure the web server document root as the absolute `public/` path:

```nginx
root /var/www/my-app/public;
```

Never use the project root. Source code, `.env`, configuration, storage, dependencies, tests, scripts, and documentation must not be directly web-accessible.

## Hidden Files

Block hidden files at the web-server layer:

```nginx
location ~ /\. {
    deny all;
}
```

Verify that `.env`, `.git`, and other project metadata return no content over HTTP.

## Input Validation

Treat query parameters, form data, JSON bodies, headers, cookies, and uploaded files as untrusted. Validate type, format, size, and allowed values before using input.

```php
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    return 'Invalid ID';
}
```

For values used in SQL, use parameter bindings.

## Output Escaping

Raw dynamic HTML output is unsafe because a value can contain executable markup.

**Unsafe raw output example:**
```php
<p>Welcome, <?= $value ?></p>
```

Use `htmlspecialchars()` as the safe default for text and HTML attribute values.

**Safe escaped output example:**
```php
<p>Welcome, <?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></p>
```

Escaping rules differ for JavaScript, URLs, CSS, and other contexts, so do not reuse HTML escaping blindly outside HTML text or attributes.

## Database Credentials

Store supported connection values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app_database
DB_USERNAME=app_user
DB_PASSWORD=replace-with-a-secret
```

Use separate credentials per environment and grant the production database user only required privileges. Never print credentials in errors or logs. Use parameterized database queries instead of concatenating untrusted values into SQL.

## Dependency Updates

Audit installed dependencies:

```bash
composer audit
```

Review available updates and changelogs before changing locked versions. Apply updates in a controlled branch, then run:

```bash
composer test
composer docs:check
```

Commit `composer.lock` so deployments use reviewed versions.

## File Permissions

Only required runtime paths under `storage/` should be writable by the PHP process. Application source, configuration, routes, `vendor/`, and `public/` should not be writable by that process during normal operation.

```bash
chown -R www-data:www-data storage
chmod -R 775 storage
```

Adjust ownership and modes for the hosting environment. Do not make the entire project world-writable. Keep `storage/` outside the public web root.

## Next

Continue to [Build Your First App](../tutorials/build-your-first-app.md).
