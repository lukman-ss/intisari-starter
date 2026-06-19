# Security

Security in an IntisariPHP Starter application starts with safe configuration, a protected document root, careful input handling, escaped output, and controlled file permissions.

This page covers baseline practices for the starter. Any feature not shown in this repository should be treated as a limitation or verified against installed IntisariPHP core features.

## Environment Files

Do not commit `.env` to version control.

Use `.env.example` as the shared template. Create the real `.env` file manually for each environment.

Do not store secrets in repository files.

## Debug Mode

Debug mode is useful locally:

```env
APP_ENV=local
APP_DEBUG=true
```

Disable debug output in production:

```env
APP_ENV=production
APP_DEBUG=false
```

## Public Directory

The web server document root must point to `public/`.

```text
/path/to/application/public
```

Do not expose the project root to the public web.

## Input Validation

Validate user input before using it.

Examples of user input include form fields, query strings, JSON payloads, headers, and uploaded files.

Validation tools are core-dependent unless provided by your installed packages.

## Output Escaping

Escape dynamic output in views.

```php
<h1><?= $e($title ?? 'Untitled') ?></h1>
```

If `$e()` is not available, use the escaping function supported by your application.

## Database Credentials

Store database credentials and secrets in environment configuration, not in committed source files.

```env
DB_CONNECTION=mysql
DB_DATABASE=application
DB_USERNAME=application_user
DB_PASSWORD=secret
```

Do not store production database credentials in the repository.

## Dependency Updates

Keep Composer dependencies updated and review changes before deploying.

```bash
composer outdated
```

Apply updates intentionally and test the application before production deployment.

## File Permissions

Runtime directories should be writable by the application runtime user.

```text
storage/cache
storage/logs
storage/framework
```

Avoid making the full project directory writable.

## Next Steps

Continue with [Deployment](../deployment/index.md).
