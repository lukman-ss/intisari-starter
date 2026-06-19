# Routing

Routes map HTTP requests to your application code. When a user visits a URL, the router determines which controller or closure handles the request. All web routes are defined in `routes/web.php`.

## The routes/web.php File

The `routes/web.php` file is loaded by `public/index.php` for every web request. It registers routes using the `$app` object (an instance of `Intisari\Application`):

```php
<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\StatusController;
use Intisari\Application;
use Lukman\Router\Router;

assert($app instanceof Application);
assert($router instanceof Router);

$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);
```

## Defining Routes

Each route has three parts:

1. **HTTP method** — `get`, `post`, `put`, `patch`, `delete`
2. **URI path** — the URL path to match
3. **Handler** — a controller method or closure

```php
$app->get('/users', [UserController::class, 'index']);
```

## Closure Routes

For simple responses, use a closure:

```php
$app->get('/health', static fn (): string => 'OK');

$app->get('/time', function () {
    return 'Current time: ' . date('H:i:s');
});

$app->post('/contact', function () {
    return 'Message received';
});
```

Closures are useful for:

- Health check endpoints
- Simple API responses
- Prototyping before creating controllers

## Controller Routes

For more complex logic, point to a controller class and method:

```php
use App\Controllers\HomeController;
use App\Controllers\UserController;

$app->get('/', [HomeController::class, 'index']);
$app->get('/users', [UserController::class, 'index']);
$app->post('/users', [UserController::class, 'store']);
```

Controllers must be imported with `use` statements at the top of the file.

See [Controllers](controllers.md) for creating controllers.

## HTTP Methods

The router supports all standard HTTP methods:

### GET

Retrieve data or display pages:

```php
$app->get('/', [HomeController::class, 'index']);
$app->get('/users', [UserController::class, 'index']);
$app->get('/users/{id}', [UserController::class, 'show']);
```

### POST

Create new resources or submit forms:

```php
$app->post('/users', [UserController::class, 'store']);
$app->post('/contact', [ContactController::class, 'submit']);
```

### PUT

Update entire resources:

```php
$app->put('/users/{id}', [UserController::class, 'update']);
```

### PATCH

Partial updates to resources:

```php
$app->patch('/users/{id}', [UserController::class, 'patch']);
```

### DELETE

Remove resources:

```php
$app->delete('/users/{id}', [UserController::class, 'destroy']);
```

## Route Parameters

Routes can capture dynamic segments from the URL:

```php
$app->get('/users/{id}', [UserController::class, 'show']);
$app->get('/posts/{slug}', [PostController::class, 'show']);
$app->get('/users/{userId}/posts/{postId}', [UserPostController::class, 'show']);
```

Parameters are passed to your handler:

```php
// app/Controllers/UserController.php
public function show($request, $id)
{
    return "User ID: $id";
}
```

### Parameter Constraints

Add constraints to validate parameters:

```php
$app->get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');

$app->get('/posts/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+');
```

## Testing Routes in the Browser

Start the development server:

```bash
composer serve
```

Open your browser and navigate to:

```
http://127.0.0.1:8000/
http://127.0.0.1:8000/health
http://127.0.0.1:8000/status
```

### Testing POST Routes

Browsers cannot send POST requests directly. Use one of these tools:

**cURL:**

```bash
curl -X POST http://127.0.0.1:8000/users
```

**Postman or Insomnia:**

Create a POST request to `http://127.0.0.1:8000/users`.

**HTML Form:**

```html
<form method="POST" action="/users">
    <input type="text" name="name">
    <button type="submit">Submit</button>
</form>
```

## Listing Routes

View all registered routes:

```bash
php intisari route:list
```

This displays a table with:

- HTTP method
- URI path
- Route name (if set)
- Handler (controller or closure)

## Common Mistakes

### Forgetting to Import Controller

**Wrong:**

```php
$app->get('/', [HomeController::class, 'index']);
// Error: Class "HomeController" not found
```

**Correct:**

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
```

### Wrong HTTP Method

**Wrong:**

```php
$app->get('/users', [UserController::class, 'store']);
// Error: Method not allowed when submitting POST form
```

**Correct:**

```php
$app->post('/users', [UserController::class, 'store']);
```

### Missing Route Registration

**Wrong:**

```php
// User tries to access /profile but it's not in routes/web.php
// Result: 404 Not Found
```

**Correct:**

```php
$app->get('/profile', [ProfileController::class, 'show']);
```

### Route Order Matters

Routes are matched in the order they are defined. Place specific routes before general ones:

**Wrong:**

```php
$app->get('/users/{id}', [UserController::class, 'show']);
$app->get('/users/create', [UserController::class, 'create']);
// /users/create matches {id} first
```

**Correct:**

```php
$app->get('/users/create', [UserController::class, 'create']);
$app->get('/users/{id}', [UserController::class, 'show']);
// /users/create matches the specific route
```

### Server Not Running

**Symptom:** Browser shows "Connection refused" or "Cannot connect"

**Solution:**

```bash
composer serve
```

### Typo in Route Path

**Wrong:**

```php
$app->get('/uesrs', [UserController::class, 'index']);
// User navigates to /users and gets 404
```

**Correct:**

```php
$app->get('/users', [UserController::class, 'index']);
```

## Advanced Features

The IntisariPHP core router also supports:

- **Named routes** — assign names to routes for URL generation
- **Route groups** — group routes with shared attributes (prefix, middleware)
- **Route middleware** — apply middleware to specific routes

These features are available through the core router but are not used in the default starter routes.

See the [IntisariPHP core documentation](https://github.com/lukman-ss/router) for advanced routing features.

## Next

Continue to [Controllers](controllers.md).
