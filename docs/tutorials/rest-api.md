# Getting Started with REST APIs

This tutorial creates simple JSON endpoints in an IntisariPHP Starter application.

The goal is to add `/api/ping` and `/api/status`, return small JSON payloads, and test them with `curl`.

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

```text
{"message":"pong"}
```

## JSON Responses

The safest minimal response is a JSON string:

```php
$app->get('/api/ping', static fn (): string => '{"message":"pong"}');
```

If your installed IntisariPHP core or HTTP package supports a JSON response helper, you may use it instead:

```php
use Lukman\Http\Response;

return Response::json(['message' => 'pong']);
```

Conceptually, JSON endpoints should also send a `Content-Type: application/json` header. Use the response or header API provided by installed IntisariPHP core or HTTP package features when available.

## 2. Add `/api/status`

For a controller-based API endpoint, create `app/Controllers/ApiStatusController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

final class ApiStatusController
{
    public function index(): string
    {
        return '{"status":"ok","service":"intisari-starter"}';
    }
}
```

Then register the route in `routes/web.php`:

```php
use App\Controllers\ApiStatusController;

$app->get('/api/status', [ApiStatusController::class, 'index']);
```

If `Response::json()` is available in your installed HTTP package, you can use it instead of the fallback string:

```php
use Lukman\Http\Response;

public function index(): Response
{
    return Response::json([
        'status' => 'ok',
        'service' => 'intisari-starter',
    ]);
}
```

Test the endpoint:

```bash
curl http://127.0.0.1:8000/api/status
```

## HTTP Status Codes

HTTP status codes describe the result of an HTTP request.

Use `200 OK` for successful read-only endpoints such as `/api/ping` and `/api/status`. Custom status code support depends on the response API available from installed IntisariPHP core or HTTP package features.

## Organizing API Controllers

For a small application, API controllers can live in `app/Controllers/`.

Example:

```text
app/
  Controllers/
    ApiStatusController.php
```

If the application grows, use clear controller names such as `ApiStatusController`. Add more structure only when it helps the project.

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
