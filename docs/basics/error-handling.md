# Error Handling

IntisariPHP core catches exceptions raised during HTTP handling and converts them into HTTP responses.

## Debug Configuration

Use local environment values while developing:

```env
APP_ENV=local
APP_DEBUG=true
```

Use production values on a public deployment:

```env
APP_ENV=production
APP_DEBUG=false
```

`config/app.php` reads `APP_DEBUG` into `app.debug`. In the current starter, `bootstrap/app.php` does not automatically pass that value to the core exception handler. The handler therefore remains in its default non-debug state unless application bootstrap code explicitly enables debug behavior.

Do not expose exception messages, stack traces, environment values, credentials, or filesystem paths in production.

## Actual HTTP Error Responses

The installed exception handler returns plain-text responses:

| Condition | Status | Default body |
| --- | ---: | --- |
| Route not found | `404` | `Not Found` |
| HTTP method not allowed | `405` | `Method Not Allowed` |
| Validation exception | `422` | `Unprocessable Entity` |
| Other exception | `500` | `Internal Server Error` |

The starter contains example views under `resources/views/errors/`, but the current exception handler does not render those files automatically.

## Common 404 Causes

- The URI is not registered in `routes/web.php`.
- The request uses the wrong HTTP method.
- The route path contains a spelling or case mismatch.
- `routes/web.php` was not loaded by the entry point.

Inspect registered routes with:

```bash
php intisari route:list
```

## Common 500 Causes

- PHP syntax or runtime error in a controller, middleware, or view.
- Missing class, method, view, or dependency.
- Invalid configuration value.
- Database connection failure.
- Runtime directory permission failure.

## Reading Error Output Locally

Run the development server in a terminal:

```bash
composer serve
```

Reproduce the request and inspect that terminal first. PHP startup errors and uncaught process-level output appear there. If application code or server configuration writes logs, inspect the configured log destination as well.

## Safe Debugging Workflow

1. Reproduce the failure locally.
2. Confirm the request method and URI with `php intisari route:list`.
3. Run `php -l` on recently edited PHP files.
4. Inspect the terminal running the development server.
5. Verify `.env` and config values without printing secrets.
6. Check database connectivity and runtime directory permissions when relevant.
7. Return production configuration to `APP_DEBUG=false` before deployment.

Avoid adding raw exception output to public responses as a debugging shortcut.

## Next

Continue to [Logging](logging.md).
