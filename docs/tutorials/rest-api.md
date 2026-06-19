# Getting Started with REST APIs

This tutorial creates simple JSON endpoints in an IntisariPHP Starter application.

The goal is to add small API routes, return JSON responses when supported by installed IntisariPHP core or HTTP package features, and test the endpoints with `curl`.

## API Routes

API routes can be registered in `routes/web.php` using the same route style as the starter's default web routes.

## 1. Add `/api/ping`

Open `routes/web.php` and add a small API route:

```php
$app->get('/api/ping', static fn (): string => '{"message":"pong"}');
```

Start the local server:

```bash
composer serve
```

Test the endpoint:

```bash
curl http://127.0.0.1:8000/api/ping
```

Expected body:

```json
{"message":"pong"}
```

## JSON Responses

The starter includes `StatusController`, which returns a JSON response object from `Lukman\Http\Response`.

If your installed IntisariPHP core or HTTP package supports it, return JSON with:

```php
use Lukman\Http\Response;

return Response::json(['message' => 'pong']);
```

If JSON helpers or response objects are not available in your installed version, a minimal fallback is returning a JSON string:

```php
$app->get('/api/ping', static fn (): string => '{"message":"pong"}');
```

Conceptually, JSON endpoints should also send a `Content-Type: application/json` header. Use the response or header API provided by installed IntisariPHP core or HTTP package features when available.

## 2. Add `/api/status`

For a controller-based API endpoint, create `app/Controllers/ApiStatusController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use Lukman\Http\Response;

final class ApiStatusController
{
    public function index(): Response
    {
        return Response::json([
            'status' => 'ok',
            'service' => 'intisari-starter',
        ]);
    }
}
```

Then register the route in `routes/web.php`:

```php
use App\Controllers\ApiStatusController;

$app->get('/api/status', [ApiStatusController::class, 'index']);
```

If `Response::json()` is not available in your installed HTTP package, return a JSON string until you confirm the supported response API.

```php
public function index(): string
{
    return '{"status":"ok","service":"intisari-starter"}';
}
```

Test the endpoint:

```bash
curl http://127.0.0.1:8000/api/status
```

## HTTP Status Codes

HTTP status code support depends on the response API available from installed IntisariPHP core or HTTP package features.

Use a `200 OK` response for successful read-only endpoints such as `/api/ping` and `/api/status`. For other status codes, check the installed package documentation before assuming a method name or constructor signature.

## Organizing API Controllers

For a small application, API controllers can live in `app/Controllers/`.

Example:

```text
app/
  Controllers/
    ApiStatusController.php
```

If the application grows, you may choose a clearer naming convention such as `UserApiController` or a sub-namespace, but only add structure when it helps the project.

## Testing with `curl`

```bash
curl http://127.0.0.1:8000/api/ping
curl http://127.0.0.1:8000/api/status
```

Use verbose mode when debugging headers:

```bash
curl -i http://127.0.0.1:8000/api/status
```

## Next Steps

- Review [Routing](../basics/routing.md).
- Review [Controllers](../basics/controllers.md).
- Review [Testing](../testing/index.md).
- Continue with [Configuration](../basics/configuration.md).
