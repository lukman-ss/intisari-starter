# REST API Basics

This tutorial walks you through building REST API endpoints in an IntisariPHP application, returning JSON responses, and testing them using `curl`.

No authentication, validation layers, or resource controllers are used here — this guide focuses purely on routing, response generation, and core JSON capabilities.

---

## API Route Concept

In IntisariPHP, API routes are defined alongside web routes in [routes/web.php](file:///d:/PHP%20PACKAGIST/intisari-starter/routes/web.php). The framework HTTP kernel processes incoming requests and dispatches them to closures or controller actions. 

By convention, all API endpoints are prefixed with `/api/` (e.g., `/api/ping`, `/api/status`) to distinguish them from standard web pages.

---

## Add `/api/ping`

Let's register a simple route closure that returns a hardcoded JSON response. Open [routes/web.php](file:///d:/PHP%20PACKAGIST/intisari-starter/routes/web.php) and add the following:

```php
$app->get('/api/ping', static fn (): string => json_encode(['message' => 'pong'], JSON_THROW_ON_ERROR));
```

This endpoint returns a raw JSON string. It uses PHP's native `json_encode()` with `JSON_THROW_ON_ERROR` for safety.

### Test with `curl`

Start the development server:
```bash
composer serve
```

Run a `curl` request in another terminal window to verify:
```bash
curl http://127.0.0.1:8000/api/ping
```

Expected output:
```json
{"message":"pong"}
```

---

## Add `/api/status`

For more complex JSON endpoints, map the route to a controller method. Let's create `app/Controllers/ApiStatusController.php`:

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
            'php_version' => PHP_VERSION,
        ]);
    }
}
```

Now register the route in [routes/web.php](file:///d:/PHP%20PACKAGIST/intisari-starter/routes/web.php):

```php
use App\Controllers\ApiStatusController;

$app->get('/api/status', [ApiStatusController::class, 'index']);
```

---

## Return JSON Using `Response::json()`

The `Lukman\Http\Response` class includes a verified `json()` factory helper:

```php
return Response::json($data);
```

Using `Response::json()` is the preferred method for building APIs because it:
1. Automatically sets the `Content-Type: application/json` HTTP header.
2. Formats and encodes the PHP array/object into JSON.
3. Returns a structured `Lukman\Http\Response` instance that integrates with the kernel.

### Fallback/Alternative Method
If you do not return a `Response` object and return a string instead, you must manually set headers if you want the client to receive the correct Content-Type:

```php
public function index(): string
{
    header('Content-Type: application/json');
    return json_encode(['status' => 'ok'], JSON_THROW_ON_ERROR);
}
```

> [!NOTE]
> Modifying PHP headers directly using `header()` can fail if any output has already started. Returning a `Response` object from your controller is much safer.

---

## `curl` Examples

You can interact with and debug your endpoints using the command line:

### 1. View Response Headers
Use the `-i` flag to inspect response headers (to verify that the `Content-Type` is set to `application/json`):
```bash
curl -i http://127.0.0.1:8000/api/status
```

Example header output:
```text
HTTP/1.1 200 OK
Host: 127.0.0.1:8000
Content-Type: application/json
Connection: close

{"status":"ok","service":"intisari-starter","php_version":"8.2.x"}
```

### 2. View Only Response Headers
Use `-I` to print only the headers (suppressing the JSON body):
```bash
curl -I http://127.0.0.1:8000/api/status
```

### 3. Pretty-Print JSON Output
Pipe the JSON output to Python's built-in tool to format it nicely in the terminal:
```bash
curl -s http://127.0.0.1:8000/api/status | python -m json.tool
```

---

## HTTP Status Code Basics

HTTP status codes communicate the result of request processing to clients.

Common API status codes:
* **`200 OK`**: The request succeeded. Default for standard GET/PUT operations.
* **`201 Created`**: The resource was created. Typically returned after successful POST requests.
* **`400 Bad Request`**: The client sent invalid data or a malformed request.
* **`404 Not Found`**: The requested URL path or database resource does not exist.
* **`500 Internal Server Error`**: An unhandled exception occurred on the server.

You can set custom status codes on the `Response` object:
```php
use Lukman\Http\Response;

return new Response(json_encode(['error' => 'Not Found']), 404, [
    'Content-Type' => 'application/json'
]);
```

---

## Organizing API Controllers

### Standard Projects
For smaller projects, keep all controllers under `app/Controllers/` using an `Api` prefix to differentiate them from web views:
```text
app/Controllers/
  ├── HomeController.php
  ├── ApiStatusController.php
  └── ApiUserController.php
```

### Subdirectories
For larger applications, group them inside a nested folder (e.g. `app/Controllers/Api/`):
```text
app/Controllers/
  ├── HomeController.php
  └── Api/
        ├── StatusController.php
        └── UserController.php
```
Ensure the namespace matches the directory structure:
```php
namespace App\Controllers\Api;
```

---

## Limitations

Be aware of the following architectural constraints when designing APIs with the default starter kit:
* **No Validation Middleware**: Input validation must be handled manually inside the controller (e.g., using `filter_var()` or the core validator factory).
* **No Authentication Layer**: Out of the box, there is no session token auth or OAuth token parsing. Token verification must be manually integrated.
* **No Automated Content Negotiation**: The framework does not automatically negotiate media types (e.g. XML/JSON). You must explicitly return a JSON response object.

---

## Next Steps

Now that you know how to build API endpoints:
- Check out the [Security Documentation](../security/index.md) to learn how to keep credentials secure.
- Learn about [Testing](../testing/index.md) to write automated checks for your API controllers.
- Proceed to [Deployment](../deployment/index.md) to go live.

---

## Next

Return to the [Documentation Index](../index.md).
