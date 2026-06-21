# Controllers

## What Is a Controller?

A controller is a PHP class whose public methods handle routed requests. Controllers keep route registration small and group related request-handling code.

## Location and Namespace

Controllers live in `app/Controllers/` and use the Composer-autoloaded `App\Controllers` namespace.

```text
app/Controllers/
|-- HomeController.php
`-- StatusController.php
```

## Existing Controllers

- `HomeController` returns the home view output when available, with a welcome string as its fallback.
- `StatusController` returns a `Lukman\Http\Response` created with `Response::json(['status' => 'ok'])`.

Neither controller extends a base controller.

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

The namespace, filename, and class name must match.

## Creating a Controller with the CLI

The starter provides a working generator:

```bash
php intisari make:controller ExampleController
```

This creates `app/Controllers/ExampleController.php`. Its generated `index()` method returns `null` as a placeholder, so replace that result before routing requests to it.

The command supports intentional replacement with `--force`:

```bash
php intisari make:controller ExampleController --force
```

## Connecting a Controller to a Route

Import the class in `routes/web.php` and register a public method:

```php
use App\Controllers\ExampleController;

$app->get('/example', [ExampleController::class, 'index']);
```

The installed handler resolver creates this controller without constructor arguments and calls `index()`.

## Return Values Verified by Source

The installed router accepts `string`, `array`, or `Lukman\Http\Response` results.

### String

```php
public function index(): string
{
    return 'Example response';
}
```

A string becomes the response body. The existing `HomeController` also returns rendered view output as a string when the `view` helper is available.

### Array

```php
public function index(): array
{
    return ['status' => 'ok'];
}
```

The installed router converts an array to a JSON response.

### Response

`Response::json()` exists in the installed HTTP package and is used by `StatusController`:

```php
use Lukman\Http\Response;

public function index(): Response
{
    return Response::json(['status' => 'ok']);
}
```

Other return types are rejected by the installed router.

## Naming Conventions

- Use the `Controller` suffix.
- Match the class name to the filename.
- Use `App\Controllers` for files directly under `app/Controllers/`.
- Use short public action names such as `index` or `show`.

These are project conventions, not automatic resource-controller behavior.

## Keep Controllers Thin

Controllers should coordinate request handling and return a supported value. Move substantial business rules or database work into application classes when that separation improves clarity.

The starter does not provide a `BaseController` or automatic constructor dependency injection for routed controller classes.

## Common Mistakes

- Using `App\Controller` instead of `App\Controllers`.
- Forgetting the controller `use` statement in `routes/web.php`.
- Mismatching the filename, class name, or routed method.
- Routing to a private, protected, or missing method.
- Leaving the generated `null` placeholder in a routed controller.
- Returning an unsupported value.
- Extending a `BaseController` that the application has not defined.
- Adding required constructor arguments even though the verified resolver instantiates controllers without arguments.

## Next

Continue to [Views](views.md).
