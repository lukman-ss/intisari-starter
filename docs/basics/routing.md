# Routing

## What Is Routing?

Routing maps an HTTP method and URI to a handler. In this starter, routes are registered through the `Intisari\Application` instance using methods such as `$app->get(...)`.

## `routes/web.php`

Web routes belong in `routes/web.php`. The front controller loads this file before running the application:

```php
$app->loadRoutes($app->routesPath('web.php'));
```

Use `$app` for route registration. The application delegates registered routes to its router.

## Default Routes

The starter contains three default routes:

```php
use App\Controllers\HomeController;
use App\Controllers\StatusController;

$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);
```

| Method | URI | Handler |
| --- | --- | --- |
| `GET` | `/` | `HomeController::index` |
| `GET` | `/health` | Closure returning `OK` |
| `GET` | `/status` | `StatusController::index` |

## Closure Routes

A closure can handle a small route directly:

```php
$app->get('/health', static fn (): string => 'OK');
```

Use a controller when request handling grows beyond a small response.

## Controller Routes

A controller route uses a class and method pair:

```php
use App\Controllers\StatusController;

$app->get('/status', [StatusController::class, 'index']);
```

The controller class must be autoloadable and the referenced method must exist.

## HTTP Methods

The application exposes registration methods for the standard methods used by the starter's router:

```php
$app->get('/example', $handler);
$app->post('/example', $handler);
$app->put('/example', $handler);
$app->patch('/example', $handler);
$app->delete('/example', $handler);
```

These handlers are placeholders. Replace `$handler` with a valid closure or controller pair in application code.

## Testing Routes in a Browser

Start the development server:

```bash
composer serve
```

Open the default GET routes:

```text
http://127.0.0.1:8000/
http://127.0.0.1:8000/health
http://127.0.0.1:8000/status
```

A browser address bar is suitable for GET requests. Use an HTTP client for other methods.

## Testing Routes with `curl`

Request the health endpoint:

```bash
curl -i http://127.0.0.1:8000/health
```

Request the JSON status endpoint:

```bash
curl -i http://127.0.0.1:8000/status
```

## Listing Routes

List the routes registered by `routes/web.php`:

```bash
php intisari route:list
```

The command displays the HTTP method, URI, route name when present, and handler.

## Common Mistakes

### Using the Wrong Registration Variable

Use `$app->get(...)` in the starter's `routes/web.php`. Do not use an undefined global `get(...)` helper.

### Missing Controller Import

Import controller classes before referencing them:

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
```

### Wrong HTTP Method

A request must use the same HTTP method as its route registration. A POST request does not match a route registered only with `$app->get(...)`.

### Wrong URI or Method Name

Check route spelling, the leading slash, controller namespace, and controller method name.

### Routes Not Loaded

Web entry points must load `routes/web.php`. The included `public/index.php` already performs this step.

## Advanced Routing

The IntisariPHP router supports advanced routing capabilities like parameters, constraints, groups, names, and route middleware.

> [!NOTE]
> The examples below are illustrative fragments for `routes/web.php`. The controller and middleware classes referenced (`UserController`, `ProfileController`, `ApiUserController`, `DashboardController`, `AuthMiddleware`) do not exist in the starter by default — you must create them first.

### Route Parameters
Route parameters are defined inside curly braces `{}` and are automatically passed to your controller methods or closures:

```php
// routes/web.php
$app->get('/users/{id}', function ($request, $id) {
    return 'User ID: ' . $id;
});
```

### Route Constraints
You can restrict parameter formats using the `where()` method on a route:

```php
// routes/web.php — UserController must be created in app/Controllers/
$app->get('/users/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
```

### Named Routes
Name a route for reverse URL generation using the `name()` method:

```php
// routes/web.php — ProfileController must be created in app/Controllers/
$app->get('/profile', [ProfileController::class, 'show'])->name('profile.show');
```

Generate the URL using the router:
```php
$url = $app->router()->url('profile.show'); // Returns '/profile'
```

### Route Groups
Group routes with shared attributes such as prefixes or middleware:

```php
// routes/web.php — ApiUserController must be created in app/Controllers/
$app->group(['prefix' => '/api'], function ($router) {
    $router->get('/users', [ApiUserController::class, 'index']);
});
```

### Route Middleware
Apply middleware to specific routes using the `middleware()` method:

```php
// routes/web.php — DashboardController and AuthMiddleware must be created first
$app->get('/dashboard', [DashboardController::class, 'index'])->middleware(AuthMiddleware::class);
```

> [!NOTE]
> Route model binding is **not** supported natively. Database parameters must be manually resolved inside your controllers.

## Next

Continue to [Controllers](controllers.md).
