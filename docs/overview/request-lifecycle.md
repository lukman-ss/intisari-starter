# Request Lifecycle

Understanding how an HTTP request flows through an IntisariPHP application helps you know where to intervene and how your code gets executed.

## Overview

Every HTTP request follows the same path from browser to response. This lifecycle applies to all routes defined in `routes/web.php`.

```text
┌─────────────────────────────────────────────────────────────┐
│  Browser                                                     │
│  ↓                                                           │
│  public/index.php          ← Front controller                │
│  ↓                                                           │
│  bootstrap/app.php         ← Create app, load env, config   │
│  ↓                                                           │
│  routes/web.php            ← Register routes                 │
│  ↓                                                           │
│  Middleware Pipeline       ← Process request                 │
│  ↓                                                           │
│  Router                      ← Match route                   │
│  ↓                                                           │
│  Controller or Closure     ← Execute handler                 │
│  ↓                                                           │
│  Response                  ← Send to browser                 │
└─────────────────────────────────────────────────────────────┘
```

## Step 1 — Browser Sends Request

A user navigates to a URL or submits a form. The browser sends an HTTP request to your server.

```
GET /users HTTP/1.1
Host: 127.0.0.1:8000
```

## Step 2 — Request Enters Front Controller

All web requests enter through `public/index.php`. This file is the single entry point configured in your web server (Nginx, Apache).

```php
// public/index.php (simplified)
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require dirname(__DIR__) . '/bootstrap/app.php';
```

The front controller:

- Loads Composer autoloading
- Bootstraps the application
- Delegates to the router

## Step 3 — Bootstrap Runs

`bootstrap/app.php` creates the application instance and initializes the framework:

```php
// bootstrap/app.php (simplified)
$app = new Application(dirname(__DIR__));
$app->loadEnvironment($app->basePath('.env'));
$app->loadConfiguration($app->configPath());
$app->register(AppServiceProvider::class);
return $app;
```

The bootstrap process:

1. Creates the `Application` instance
2. Loads environment variables from `.env`
3. Loads configuration files from `config/`
4. Registers service providers

## Step 4 — Configuration Is Loaded

Configuration files in `config/` are loaded and merged with environment variables:

```php
// config/app.php
return [
    'name' => $env('APP_NAME', 'IntisariPHP'),
    'env' => $env('APP_ENV', 'production'),
    'debug' => $env('APP_DEBUG', false),
];
```

Configuration values are cached in production for faster loading.

## Step 5 — Routes Are Loaded

Back in `public/index.php`, the web route file is loaded:

```php
$app->loadRoutes($app->routesPath('web.php'));
```

`routes/web.php` registers routes:

```php
$app->get('/', [HomeController::class, 'index']);
$app->get('/users', [UserController::class, 'index']);
$app->post('/users', [UserController::class, 'store']);
```

Routes are stored in the router but not yet matched.

## Step 6 — Middleware Is Processed

Middleware classes registered in `config/app.php` wrap the request handling:

```php
// config/app.php
'middleware' => [
    App\Middleware\ExampleMiddleware::class,
],
```

Middleware can:

- Inspect the incoming request
- Modify the request before it reaches the controller
- Short-circuit the request (return a response early)
- Modify the response after the controller runs

Example middleware flow:

```text
Request → Middleware 1 → Middleware 2 → Controller → Middleware 2 → Middleware 1 → Response
```

See [Middleware](../basics/middleware.md) for creating and registering middleware.

## Step 7 — Route Is Matched

The router matches the incoming request method and URI to a registered route:

```
Request: GET /users
Matches: $app->get('/users', [UserController::class, 'index']);
```

If no route matches, the router returns a 404 response.

## Step 8 — Controller or Closure Runs

The matched route's handler is executed. This can be a controller method:

```php
// app/Controllers/UserController.php
class UserController
{
    public function index()
    {
        $users = [
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob'],
        ];
        
        return Response::json($users);
    }
}
```

Or a closure:

```php
$app->get('/health', function () {
    return Response::json(['status' => 'OK']);
});
```

Controllers can return:

- Plain strings
- `Response` objects
- `JsonResponse` objects
- Rendered view strings

See [Controllers](../basics/controllers.md) for creating controllers.

## Step 9 — Response Is Sent

The handler's return value is converted to an HTTP response and sent back to the browser:

```
HTTP/1.1 200 OK
Content-Type: application/json

[{"id":1,"name":"Alice"},{"id":2,"name":"Bob"}]
```

The browser renders the response or processes the JSON data.

## Complete Flow Example

Let's trace a request to `GET /users`:

```text
1. Browser: GET /users HTTP/1.1
2. public/index.php: Load autoloader, bootstrap app
3. bootstrap/app.php: Create app, load .env, load config
4. config/app.php: Read APP_NAME, APP_ENV, middleware list
5. routes/web.php: Register all routes including /users
6. Middleware: ExampleMiddleware processes request
7. Router: Match GET /users to UserController@index
8. UserController: Execute index() method, return JSON
9. Response: Send HTTP 200 with JSON body to browser
```

## Console Requests

CLI commands (run via `php intisari` or `composer console`) follow a different lifecycle:

1. `intisari` entry point loads the application
2. `routes/console.php` registers commands
3. The console application parses arguments
4. The matched command handler runs
5. Output is printed to the terminal

See [CLI Commands](../cli/index.md) for available commands.

## Related Topics

- [Routing](../basics/routing.md) — defining routes and parameters
- [Controllers](../basics/controllers.md) — organizing request handlers
- [Middleware](../basics/middleware.md) — intercepting requests and responses
- [Configuration](../basics/configuration.md) — environment and config files

## Next

Continue to [Routing](../basics/routing.md).
