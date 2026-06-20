# Request Lifecycle

An HTTP request enters the starter through one public entry point and is passed to the IntisariPHP application for routing and response handling.

## Lifecycle Diagram

```text
Browser
  |
  v
Web server document root: public/
  |
  v
public/index.php
  |
  +--> vendor/autoload.php
  |
  +--> bootstrap/app.php
          |
          +--> create Application
          +--> load .env when present
          +--> load config/*.php
          +--> register AppServiceProvider
  |
  +--> load routes/web.php
  |
  v
Application::run()
  |
  +--> bootstrap and boot core service providers
  +--> middleware pipeline, when runtime middleware is registered
  +--> match and dispatch route handler
  |
  v
HTTP response
```

## 1. Browser Sends an HTTP Request

The browser sends a request containing an HTTP method, URI, headers, and optional body.

## 2. Web Server Uses `public/` as the Web Root

The web server document root must point to `public/`. Requests are directed to `public/index.php`, the starter's front controller.

## 3. `public/index.php` Loads the Application

`public/index.php` loads Composer autoloading and requires `bootstrap/app.php`:

```php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require dirname(__DIR__) . '/bootstrap/app.php';
```

## 4. `bootstrap/app.php` Creates and Configures the Application

The bootstrap file creates an `Intisari\Application` with the project root as its base path. It also sets the application as the global instance when that API is available.

## 5. Environment and Configuration Are Loaded

When `.env` exists, `bootstrap/app.php` loads it. Configuration files are then loaded from `config/`.

See [Configuration](../basics/configuration.md) for the available starter configuration.

## 6. Service Providers Are Registered and Booted

`bootstrap/app.php` explicitly registers `App\Providers\AppServiceProvider`. Later, `Application::run()` bootstraps and boots the core service providers supplied by IntisariPHP.

## 7. `routes/web.php` Is Loaded

Before running the application, `public/index.php` loads `routes/web.php`:

```php
$app->loadRoutes($app->routesPath('web.php'));
```

The route file registers handlers with `$app->get(...)` and the other supported HTTP method APIs. See [Routing](../basics/routing.md).

## 8. Middleware Is Applied When Registered

Conceptually, registered runtime middleware wraps routing and can inspect a request before dispatch or modify the returned response.

```text
Request -> Middleware -> Route handler -> Middleware -> Response
```

Only middleware added to the application's runtime middleware stack participates in this pipeline. See [Middleware](../basics/middleware.md).

## 9. The Matching Route Handler Runs

The router matches the request method and URI to a registered route. The handler can be a closure or a controller method.

See [Controllers](../basics/controllers.md) for controller organization and return values.

## 10. The Response Is Returned

The handler result is normalized into an HTTP response. `Application::run()` sends the response and completes the HTTP lifecycle.

## Next

Continue to [Routing](../basics/routing.md).
