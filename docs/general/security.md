# Security Guidelines

Security in an IntisariPHP Starter application starts with safe configuration, controlled public access, careful input handling, escaped output, and regular dependency maintenance.

This page covers baseline practices for a small PHP application. Any feature not shown in this starter should be verified against your installed Intisari packages or your application code.

## Environment Files

Do not commit `.env` to version control.

Use `.env.example` as the shared template and create the real `.env` file manually per environment.

Never store production secrets directly in repository files.

## Debug Mode

Debug mode should be enabled only for local development.

```env
APP_ENV=local
APP_DEBUG=true
```

In production, always disable debug output:

```env
APP_ENV=production
APP_DEBUG=false
```

Debug output can expose file paths, environment values, stack traces, or implementation details.

## Public Directory

The web server document root must point to `public/`.

```text
/path/to/app/public
```

Do not expose the project root to the public web. The root contains application code, configuration, storage files, Composer metadata, and dependencies.

## Input Validation

Validate user input before using it.

Examples of user input include query strings, form fields, JSON request bodies, route parameters, uploaded files, and headers.

Validation helpers, request objects, or validation packages depend on installed IntisariPHP core features and application dependencies. Use the tools available in your project and keep validation close to the request handling code.

## Output Escaping

Escape dynamic output in views before rendering it.

```php
<h1><?= $e($title ?? 'Untitled') ?></h1>
```

If `$e()` is not available in your installed view feature, use an escaping function supported by your project.

Do not print raw user input into HTML.

## Database Credentials

Store database credentials in `.env`, not in committed source files.

```env
DB_CONNECTION=mysql
DB_DATABASE=app
DB_USERNAME=app_user
DB_PASSWORD=secret
```

Use different credentials for local, testing, staging, and production environments.

## Dependency Updates

Keep Composer dependencies updated.

```bash
composer outdated
```

Review dependency changes before deploying updates to production.

## File Permissions

Only runtime directories should be writable by the web server user.

Common writable paths include:

```text
storage/cache
storage/logs
storage/framework
```

Avoid making the full project directory writable.

## Next Steps

Continue with [Deployment](../deployment/index.md).
