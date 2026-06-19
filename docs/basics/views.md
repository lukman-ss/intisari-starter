# Views

Views contain presentation code for HTML output.

In an MVC-style application, controllers handle the request and views render the response body shown to the user. IntisariPHP Starter stores view files in `resources/views/`.

## View Folder

The starter includes:

```text
resources/views/
  home.php
  layouts/app.php
  partials/header.php
  errors/404.php
  errors/500.php
```

Use this folder for PHP templates, layouts, partials, and error pages.

## Simple PHP View

Example file: `resources/views/home.php`

```php
<?php

declare(strict_types=1);
?>
<main>
    <h1>Hello Intisari</h1>
    <p>Welcome to the application.</p>
</main>
```

The default starter view uses PHP template helpers such as `$e`, `$extend`, `$start`, and `$end` when the installed view feature provides them.

## Controller Returning HTML

A controller can return a plain HTML string for a minimal example:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

final class HomeController
{
    public function index(): string
    {
        return '<h1>Hello Intisari</h1>';
    }
}
```

This is useful as a fallback when view rendering is not configured or while testing a route.

## Rendering Views

If the `view()` helper is available from installed IntisariPHP core view features, a controller can return a rendered view:

```php
public function index(): string
{
    return view('home');
}
```

Check the installed IntisariPHP core documentation for the exact supported view features and helper behavior.

If view rendering is not available, return an HTML string or a supported response object from the controller.

## Layout and Escaping

The starter's default view uses a layout and escaped output:

```php
$extend('layouts.app');

$start('content');
?>
<main>
    <h1><?= $e('Hello') ?></h1>
</main>
<?php
$end();
```

Escape dynamic output before printing it in a template. The starter examples use `$e()` when that helper is available.

## Best Practices

- Keep business logic out of views.
- Escape output before rendering dynamic values.
- Split reusable markup into partials.
- Keep controllers responsible for choosing the view and preparing data.

## Troubleshooting

### File View Not Found

Confirm the view file exists under `resources/views/`.

For `view('home')`, the expected starter file is:

```text
resources/views/home.php
```

### Wrong Path

Use the view name expected by the installed view feature. Do not include a filesystem path unless the core documentation requires it.

### PHP Error Inside Template

A PHP syntax error or undefined variable inside a view can break rendering.

Check the template first, then verify that the controller passes or defines the values used by the view.

## Next Steps

Continue with [Configuration](configuration.md).
