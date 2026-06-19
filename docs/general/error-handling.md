# Error Handling and Debugging

IntisariPHP Starter uses environment configuration to control debug behavior.

The most important setting is `APP_DEBUG`. Enable it locally to see useful error details while developing. Disable it in production to avoid exposing sensitive information.

## Enabling Debug Mode Locally

For local development, use:

```env
APP_ENV=local
APP_DEBUG=true
```

This makes debugging easier while you are building routes, controllers, views, and configuration.

## Disabling Debug Mode in Production

For production, use:

```env
APP_ENV=production
APP_DEBUG=false
```

Never leave debug output enabled on a public production server. Error details may expose file paths, environment information, or implementation details.

## Reading Logs

The starter includes a `storage/logs/` directory.

If installed IntisariPHP core features or application code write log files, check this directory when debugging server errors.

```text
storage/logs/
```

Log format and error handler behavior depend on installed IntisariPHP core features and your application code.

## Common Errors

Common development errors include:

- Route path typo.
- Wrong controller namespace.
- Missing Composer dependencies.
- Invalid `.env` value.
- PHP syntax error in a controller or view.
- File permission issue in `storage/`.

## Blank Page Troubleshooting

If the browser shows a blank page:

1. Confirm the local server is running.
2. Check `APP_DEBUG=true` in local `.env`.
3. Check PHP syntax in the edited file.
4. Check `storage/logs/` if log output is available.
5. Clear cached configuration if environment values changed.

```bash
php intisari config:clear
```

## 404 Route Not Found

A 404 usually means the requested path does not match a route.

Check `routes/web.php`:

```php
$app->get('/status', [StatusController::class, 'index']);
```

Then check the requested URL:

```text
http://127.0.0.1:8000/status
```

Also verify route spelling, leading slash usage, and controller imports.

## 500 Server Error

A 500 error usually means application code failed while handling the request.

Check:

- Controller namespace and method name.
- PHP syntax errors.
- View file errors.
- Environment values.
- File permissions for `storage/`.
- Logs in `storage/logs/` when available.

Do not assume an internal error handler API unless it is documented by the installed IntisariPHP core package.

## Next Steps

Continue with [Configuration](../basics/configuration.md).
