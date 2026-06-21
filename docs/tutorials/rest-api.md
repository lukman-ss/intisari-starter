# REST API Basics

This tutorial adds two small JSON endpoints and tests them with `curl`. It does not add authentication, a validation layer, or resource controllers.

## API Route Concept

An API route maps an HTTP method and path to a handler that returns machine-readable data. The starter loads API and page routes from the same `routes/web.php` file. Prefixing API paths with `/api` is an application convention used in this tutorial.

## Add `/api/ping`

Add the `Response` import near the top of `routes/web.php`:

```php
use Lukman\Http\Response;
```

Then add this route after the existing routes:

```php
$app->get('/api/ping', static fn (): Response => Response::json([
    'message' => 'pong',
]));
```

This closure returns a JSON response with status `200`.

## Add `/api/status`

Create `app/Controllers/ApiStatusController.php`:

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

Import the controller near the top of `routes/web.php`:

```php
use App\Controllers\ApiStatusController;
```

Register the route:

```php
$app->get('/api/status', [ApiStatusController::class, 'index']);
```

Confirm both endpoints appear:

```bash
php intisari route:list
```

## Return JSON with the Supported Method

The installed `Lukman\Http\Response` class provides the verified factory:

```php
return Response::json(['status' => 'ok']);
```

It JSON-encodes the array, returns a response object, and sets the JSON content type. It also accepts a status code:

```php
return Response::json(['error' => 'Not Found'], 404);
```

Use `Response::json()` instead of returning a raw `json_encode()` string when the client needs the correct JSON response header.

## `curl` Examples

Start the server:

```bash
composer serve
```

From another terminal, request the ping endpoint:

```bash
curl -i http://127.0.0.1:8000/api/ping
```

The response body is:

```json
{"message":"pong"}
```

Request the status endpoint:

```bash
curl -i http://127.0.0.1:8000/api/status
```

The response body is:

```json
{"status":"ok","service":"intisari-starter"}
```

The `-i` option includes headers, allowing you to confirm the HTTP status and JSON content type.

## HTTP Status Code Basics

- `200 OK` means the request succeeded.
- `201 Created` is appropriate after successfully creating a resource.
- `400 Bad Request` indicates invalid request data or syntax.
- `404 Not Found` indicates no matching endpoint or requested item.
- `405 Method Not Allowed` means the path exists for another HTTP method.
- `500 Internal Server Error` indicates an unhandled server failure.

Choose the status explicitly when the default `200` is not correct:

```php
return Response::json(['created' => true], 201);
```

## Organizing API Controllers

For a small application, use clear class names under `app/Controllers/`:

```text
app/Controllers/
|-- HomeController.php
|-- StatusController.php
`-- ApiStatusController.php
```

Larger applications may use `app/Controllers/Api/`, but the namespace must match the directory:

```php
namespace App\Controllers\Api;
```

The starter does not generate or register resource controllers. Keep route registration explicit in `routes/web.php`.

## Limitations

- These endpoints are public; this tutorial does not add authentication or authorization.
- No validation layer is added. Validate request data explicitly when an endpoint accepts input.
- The starter does not provide resource-controller conventions.
- Content negotiation is not demonstrated; the handlers explicitly return JSON.
- Persistence, migrations, and database-backed resources are outside this tutorial.

## Next Steps

Review [Security](../security/index.md), [Testing](../testing/index.md), and [Deployment](../deployment/index.md) before exposing application endpoints publicly.
