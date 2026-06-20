# Controllers

## What Is a Controller?

A controller is a PHP class whose public methods handle routed requests. Controllers keep route registration small and group related request-handling code.

## Location and Namespace

Application controllers live in `app/Controllers/` and use the `App\Controllers` namespace defined by Composer PSR-4 autoloading.

```text
app/Controllers/
├── HomeController.php
└── StatusController.php
```

## Creating a Controller Manually

Create `app/Controllers/ExampleController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

final class ExampleController
{
    public function index(): string
    {
        return 'Example response';
    }
}
```

The class name, filename, and namespace must match.

## Creating a Controller with the CLI

The starter registers a working `make:controller` command:

```bash
php intisari make:controller ExampleController
```

This creates `app/Controllers/ExampleController.php`. The generated method is a skeleton; replace its placeholder return value before connecting it to a route.

Use `--force` only when intentionally replacing an existing generated file:

```bash
php intisari make:controller ExampleController --force
```

## Connecting a Controller to a Route

Import the controller in `routes/web.php` and register its method through `$app`:

```php
use App\Controllers\ExampleController;

$app->get('/example', [ExampleController::class, 'index']);
```

The router creates the controller class and calls the referenced public method.

## Verified Return Values

The installed router accepts three handler return types.

### String

A string becomes an HTTP response body:

```php
public function index(): string
{
    return 'Example response';
}
```

Rendered views also return strings:

```php
public function index(): string
{
    return view('home');
}
```

### Array

The router converts an array to a JSON response:

```php
public function index(): array
{
    return ['status' => 'ok'];
}
```

### `Response`

`Lukman\Http\Response` exists in the installed HTTP package and provides `Response::json()`:

```php
use Lukman\Http\Response;

public function index(): Response
{
    return Response::json(['status' => 'ok']);
}
```

Other return types are rejected by the router.

## Naming Conventions

- Use the `Controller` suffix, such as `HomeController`.
- Match the class name to the filename.
- Use `App\Controllers` for controllers directly under `app/Controllers/`.
- Use short method names that describe the handled action, such as `index` or `show`.
- Keep routed methods public.

These are project conventions rather than automatic resource-controller behavior.

## Keep Controllers Thin

Controllers should coordinate request handling and produce a response. Move substantial business rules or database operations into application classes when that separation improves clarity.

The starter does not provide `$this->validate`, `$this->users`, `$this->mailer`, or a base controller API. Do not use those members unless your application defines them.

## Common Mistakes

### Wrong Namespace

Use `namespace App\Controllers;`, not `App\Controller`.

### Missing Route Import

Add the controller `use` statement to `routes/web.php` before using `ExampleController::class`.

### Class, File, or Method Mismatch

Confirm that the filename matches the class and that the routed public method exists.

### Unsupported Return Type

Return a string, array, or `Response`. A generated controller returning `null` is only a skeleton and is not a valid routed response.

### Assuming Dependency Injection

The installed route resolver directly creates controller classes without constructor arguments. Constructor injection is not established by the starter's controller routing path.

### Extending an Undefined Base Controller

The starter does not include a `BaseController`. Use a plain class unless the application explicitly defines a base class.

## Next

Continue to [Views](views.md).
