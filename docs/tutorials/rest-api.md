# REST API Basics

This tutorial builds simple JSON API endpoints in an IntisariPHP application and tests them with `curl`. No authentication, no validation, no middleware — just plain JSON responses.

## What You Will Build

- A `GET /api/ping` endpoint that returns `{"message":"pong"}`
- A `GET /api/status` endpoint that returns application status
- Tests using `curl` to verify responses

**Estimated time:** 10 minutes

## 1. Add a Ping Endpoint

Open `routes/web.php` and add a closure route:

```php
$app->get('/api/ping', static fn (): string => '{"message":"pong"}');
```

This route returns a JSON string directly. No controller needed for simple responses.

### Test with curl

Start the server and test:

```bash
composer serve
```

In another terminal:

```bash
curl http://127.0.0.1:8000/api/ping
```

Expected response:

```json
{"message":"pong"}
```

### Test with Browser

Open [http://127.0.0.1:8000/api/ping](http://127.0.0.1:8000/api/ping) in your browser. You should see the JSON string.

## 2. Add a Status Controller

For more complex endpoints, create a dedicated controller.

### Create the Controller

Create `app/Controllers/ApiStatusController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

final class ApiStatusController
{
    public function index(): string
    {
        return json_encode([
            'status' => 'ok',
            'service' => 'intisari-starter',
            'php_version' => PHP_VERSION,
        ]);
    }
}
```

This controller returns a JSON-encoded string with application information.

### Register the Route

Add the route in `routes/web.php`:

```php
use App\Controllers\ApiStatusController;

$app->get('/api/status', [ApiStatusController::class, 'index']);
```

### Test with curl

```bash
curl http://127.0.0.1:8000/api/status
```

Expected response:

```json
{"status":"ok","service":"intisari-starter","php_version":"8.2.x"}
```

## JSON Response Helpers

The examples above use plain JSON strings. If the IntisariPHP core HTTP package provides a `Response::json()` helper, you can use it instead:

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

**Advantages of `Response::json()`:**

- Automatically sets `Content-Type: application/json` header
- Handles JSON encoding internally
- May support pretty printing and encoding options

**If `Response::json()` is not available**, use `json_encode()` with a manual header:

```php
public function index(): string
{
    header('Content-Type: application/json');
    return json_encode(['status' => 'ok']);
}
```

**Note:** Setting headers directly may not work if output has already started. Using a response object from the core HTTP package is preferred.

## HTTP Status Codes

HTTP status codes tell the client what happened with their request.

### Common Status Codes for APIs

| Code | Meaning | When to Use |
|------|---------|-------------|
| `200 OK` | Success | Successful GET or PUT request |
| `201 Created` | Resource created | Successful POST that creates a resource |
| `204 No Content` | Success, no body | Successful DELETE |
| `400 Bad Request` | Invalid input | Validation failed or malformed request |
| `401 Unauthorized` | Not authenticated | Missing or invalid credentials |
| `403 Forbidden` | Not authorized | Authenticated but lacks permission |
| `404 Not Found` | Resource not found | Requested resource does not exist |
| `500 Internal Server Error` | Server error | Unexpected error in your code |

### Setting Status Codes

If the IntisariPHP core HTTP package supports custom status codes:

```php
use Lukman\Http\Response;

public function show(): Response
{
    $user = $this->findUser($id);
    
    if ($user === null) {
        return new Response('{"error":"User not found"}', 404);
    }
    
    return Response::json($user);
}
```

If custom status codes are not available, the response defaults to `200 OK`. You can still include error information in the JSON body:

```php
public function show(): string
{
    $user = $this->findUser($id);
    
    if ($user === null) {
        return json_encode([
            'error' => 'User not found',
            'code' => 404,
        ]);
    }
    
    return json_encode($user);
}
```

## Debugging with curl

### View Response Headers

Include headers in the output with `-i`:

```bash
curl -i http://127.0.0.1:8000/api/status
```

Example output:

```
HTTP/1.1 200 OK
Host: 127.0.0.1:8000
Date: Fri, 19 Jun 2026 10:00:00 GMT
Connection: close
Content-Type: text/html; charset=UTF-8

{"status":"ok","service":"intisari-starter","php_version":"8.2.x"}
```

### View Only Headers

Use `-I` (uppercase i) to see headers without the body:

```bash
curl -I http://127.0.0.1:8000/api/status
```

### Pretty-Print JSON

Use `jq` (install separately) to format JSON output:

```bash
curl http://127.0.0.1:8000/api/status | jq
```

Or use Python's built-in JSON formatter:

```bash
curl http://127.0.0.1:8000/api/status | python -m json.tool
```

### Send a POST Request

Test POST endpoints with `-d`:

```bash
curl -X POST http://127.0.0.1:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Alice","email":"alice@example.com"}'
```

## Organizing API Controllers

### Small Projects

For small projects, keep all controllers in `app/Controllers/`:

```text
app/Controllers/
  HomeController.php          ← Web controller
  ApiStatusController.php     ← API controller
  ApiUserController.php       ← API controller
```

Use a naming convention (`Api` prefix) to distinguish API controllers.

### Larger Projects

For larger projects, use a subdirectory:

```text
app/Controllers/
  HomeController.php          ← Web controller
  AboutController.php         ← Web controller
  Api/
    StatusController.php      ← API controller
    UserController.php        ← API controller
```

Update the namespace:

```php
namespace App\Controllers\Api;

final class StatusController
{
    public function index(): string
    {
        return json_encode(['status' => 'ok']);
    }
}
```

Update the route:

```php
use App\Controllers\Api\StatusController;

$app->get('/api/status', [StatusController::class, 'index']);
```

### Route Prefixing

Group API routes under `/api/` for clarity:

```php
$app->get('/api/ping', static fn (): string => '{"message":"pong"}');
$app->get('/api/status', [ApiStatusController::class, 'index']);
$app->get('/api/users', [ApiUserController::class, 'index']);
$app->get('/api/users/{id}', [ApiUserController::class, 'show']);
```

If the IntisariPHP core router supports route groups, you can use a prefix:

```php
$app->group(['prefix' => '/api'], function ($app) {
    $app->get('/ping', static fn (): string => '{"message":"pong"}');
    $app->get('/status', [ApiStatusController::class, 'index']);
    $app->get('/users', [ApiUserController::class, 'index']);
});
```

## Writing API Tests

Test your API controllers with PHPUnit:

```php
<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controllers\ApiStatusController;
use PHPUnit\Framework\TestCase;

final class ApiStatusControllerTest extends TestCase
{
    public function test_status_returns_json(): void
    {
        $controller = new ApiStatusController();
        $response = $controller->index();
        
        $data = json_decode($response, true);
        
        $this->assertIsArray($data);
        $this->assertEquals('ok', $data['status']);
        $this->assertEquals('intisari-starter', $data['service']);
    }
}
```

Save as `tests/Unit/ApiStatusControllerTest.php` and run:

```bash
composer test
```

## Common Mistakes

### Forgetting json_encode

Returning an array directly may not work in all cases:

```php
// May not work — depends on router support
public function index(): array
{
    return ['status' => 'ok'];
}

// Always works — explicit JSON string
public function index(): string
{
    return json_encode(['status' => 'ok']);
}
```

### Missing Content-Type Header

Without the `Content-Type: application/json` header, clients may not parse the response as JSON. Use `Response::json()` if available, or set the header manually.

### Wrong Namespace for Api Subdirectory

If you use `app/Controllers/Api/`, update the namespace:

```php
// WRONG
namespace App\Controllers;

// CORRECT
namespace App\Controllers\Api;
```

### Route Path Mismatch

Ensure the route path matches what clients expect:

```php
// /api/users ✓
// /api/user ✗ (singular)
// /api/getUsers ✗ (not RESTful)
$app->get('/api/users', [ApiUserController::class, 'index']);
```

## Next Steps

You now have working JSON API endpoints. Continue learning:

- [Routing](../basics/routing.md) — learn about HTTP methods, parameters, and route organization
- [Controllers](../basics/controllers.md) — learn about controller best practices
- [Testing](../testing/index.md) — write more comprehensive tests
- [Deployment](../deployment/index.md) — deploy your API to production
