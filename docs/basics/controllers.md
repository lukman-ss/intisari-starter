# Controllers

Controllers are PHP classes that handle application actions for matched routes.

In IntisariPHP Starter, controllers live in `app/Controllers/` and use the `App\Controllers` namespace.

Routes decide which controller method should run. The controller then returns a string, view, or supported response object.

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

The file path should be:

```text
app/Controllers/HomeController.php
```

You can create this file manually or use the starter command line generator if you want the default class template.

## Connecting a Controller to a Route

Register the controller method in `routes/web.php`.

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
```

The route handler array contains the controller class and method name.

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

## Return Values

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

- Call application logic.
- Return a string, view, or response object.
- Keep route files small.

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

The starter does not include a `BaseController` class. Do not extend one unless your application adds it or your installed IntisariPHP core package clearly provides it.

### File Not Autoloaded

After creating files manually, make sure the class path and namespace match PSR-4 autoloading. If needed, refresh Composer autoload files:

```bash
composer dump-autoload
```

## Next Steps

Continue with [Views](views.md).
