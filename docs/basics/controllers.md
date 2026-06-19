# Controllers

Controllers are PHP classes that handle HTTP requests and return responses. Instead of writing all your route logic in closures inside `routes/web.php`, you can organize related request handlers into controller classes.

## What Is a Controller?

A controller is a plain PHP class with public methods that correspond to route handlers. Each method typically handles one specific action: listing items, showing a single item, creating a resource, and so on.

Controllers live in the `app/Controllers/` directory and use the `App\Controllers` namespace.

## Location and Namespace

| Property | Value |
|----------|-------|
| Directory | `app/Controllers/` |
| Namespace | `App\Controllers` |
| Autoloading | PSR-4 (configured in `composer.json`) |

## Creating a Controller

A minimal controller looks like this:

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

Save this file as `app/Controllers/HomeController.php`.

### Using the Generator

Create a controller using the CLI:

```bash
php intisari make:controller HomeController
```

This generates a new controller file in `app/Controllers/`.

## Connecting a Controller to a Route

Register the controller method in `routes/web.php`:

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
```

The array syntax `[ClassName::class, 'methodName']` tells the router which controller method to call when the route is matched.

### Multiple Methods

A single controller can handle multiple routes:

```php
use App\Controllers\UserController;

$app->get('/users', [UserController::class, 'index']);
$app->get('/users/{id}', [UserController::class, 'show']);
$app->post('/users', [UserController::class, 'store']);
$app->put('/users/{id}', [UserController::class, 'update']);
$app->delete('/users/{id}', [UserController::class, 'destroy']);
```

## Return Values

A controller method can return:

### String

Returned as plain text response body:

```php
public function index(): string
{
    return 'Hello Intisari';
}
```

### Array

Automatically converted to a JSON response:

```php
public function index(): array
{
    return ['status' => 'ok', 'users' => []];
}
```

### Response Object

For more control over headers, status codes, and content type:

```php
use Lukman\Http\Response;

public function index(): Response
{
    return Response::json(['status' => 'ok']);
}
```

### Rendered View

Return the output of the `view()` helper:

```php
public function index(): string
{
    return view('home');
}
```

See [Views](views.md) for working with view templates.

## Naming Convention

Follow these conventions for consistency:

| Convention | Example |
|------------|---------|
| Suffix with `Controller` | `HomeController`, `UserController`, `StatusController` |
| PascalCase class name | `OrderController`, not `ordercontroller` |
| One controller per file | `UserController.php` contains only `UserController` |
| Namespace matches directory | `namespace App\Controllers;` |
| camelCase method names | `index()`, `show()`, `store()`, `destroy()` |

### Common Method Names

| Method | Purpose |
|--------|---------|
| `index()` | List all resources |
| `show()` | Display a single resource |
| `create()` | Show form to create a resource |
| `store()` | Save a new resource |
| `edit()` | Show form to edit a resource |
| `update()` | Update an existing resource |
| `destroy()` | Delete a resource |

## Keep Controllers Thin

Controllers should only coordinate the request and response. Move complex logic into separate classes or services.

**Fat controller (avoid):**

```php
public function store(): string
{
    // 50 lines of validation
    // 30 lines of database queries
    // 20 lines of email sending
    // 10 lines of logging
    return 'Done';
}
```

**Thin controller (preferred):**

```php
public function store(): string
{
    $data = $this->validate($request);
    $user = $this->users->create($data);
    $this->mailer->send($user);
    
    return 'User created';
}
```

Guidelines:

- A controller method should be short and readable.
- Business logic belongs in service classes.
- Database queries belong in repository or model classes.
- Validation logic belongs in dedicated validator classes.

## Common Mistakes

### Missing Import in routes/web.php

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

### Wrong Method Name

**Wrong:**

```php
$app->get('/', [HomeController::class, 'home']);
// Error: Method "home" does not exist on HomeController
```

**Correct:**

```php
$app->get('/', [HomeController::class, 'index']);
```

### Wrong Namespace

**Wrong:**

```php
namespace App\Controller; // missing "s"

class HomeController { }
```

**Correct:**

```php
namespace App\Controllers;

class HomeController { }
```

### Missing declare(strict_types=1)

Always include `declare(strict_types=1)` at the top of controller files for consistency:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;
```

### Extending a Base Controller That Doesn't Exist

The starter does not include a `BaseController`. Do not extend one unless you create it yourself:

**Wrong:**

```php
class HomeController extends BaseController
{
    // Error: BaseController not found
}
```

**Correct:**

```php
final class HomeController
{
    // Works without extending anything
}
```

If you need shared behavior across controllers, create your own `BaseController` in `app/Controllers/` and extend it.

### Putting Business Logic in Controllers

Controllers should delegate work to other classes. If a controller method exceeds 10–15 lines, consider extracting the logic.

## Next

Continue to [Views](views.md).
