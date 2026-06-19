# Controllers

Controllers group request handling logic into PHP classes.

In an MVC-style application, routes receive the request, controllers handle the application action, and views render HTML when a page needs presentation. IntisariPHP Starter stores controllers in `app/Controllers/` using the `App\Controllers` namespace from Composer PSR-4 autoloading.

## Creating a Controller

A simple controller is a regular PHP class.

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

final class HomeController
{
    public function index(): string
    {
        return 'Hello Intisari';
    }
}
```

You can also generate a controller with the starter command line:

```bash
php intisari make:controller HomeController
```

The generated file is placed in `app/Controllers/`.

## Connecting a Controller to a Route

Register the controller method in `routes/web.php`.

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
```

The array syntax points to the class name and method name that should handle the route.

## Controller Naming

Controller classes should use the `Controller` suffix.

```text
HomeController
StatusController
UserController
```

The namespace should match the folder:

```php
namespace App\Controllers;
```

The file should be stored under:

```text
app/Controllers/HomeController.php
```

## Returning a Response

A controller method may return a simple string:

```php
public function index(): string
{
    return 'Hello Intisari';
}
```

The starter also shows a JSON response from `StatusController`:

```php
use Lukman\Http\Response;

public function index(): Response
{
    return Response::json(['status' => 'ok']);
}
```

Supported response types depend on installed IntisariPHP core and HTTP package features. Use strings or documented response objects that exist in your project.

## Keeping Controllers Thin

Controllers should stay focused on request handling.

Use controllers to:

- Read route input when supported by the core.
- Call application logic.
- Return a string, view, or response object.

Avoid placing unrelated setup, large data transformations, or reusable framework-style code directly inside controller methods.

## Common Mistakes

### Wrong Namespace

The namespace must match Composer autoloading.

```php
namespace App\Controllers;
```

### Missing Route Import

Import the controller class in `routes/web.php`.

```php
use App\Controllers\HomeController;
```

### Wrong Method Name

The method in the route must exist on the controller.

```php
$app->get('/', [HomeController::class, 'index']);
```

### Assuming a Base Controller

The starter does not include a base controller class. Extend one only if your application or installed IntisariPHP core package provides it.

### File Not Autoloaded

After creating files manually, make sure the class path and namespace match PSR-4 autoloading. If needed, refresh Composer autoload files:

```bash
composer dump-autoload
```

## Next Steps

Continue with [Views](views.md).
