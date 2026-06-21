# Views

## What Is a View?

A view is a plain PHP file that produces HTML. It keeps presentation markup separate from route and controller logic.

## `resources/views/` Directory

The configured view service resolves templates from `resources/views/`:

```text
resources/views/
|-- home.php
|-- errors/
|   |-- 404.php
|   `-- 500.php
|-- layouts/
|   `-- app.php
`-- partials/
    `-- header.php
```

These files are application source and must not be directly web-accessible.

## Simple PHP View File

Create `resources/views/example.php`:

```php
<?php

declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $e($title ?? 'Example') ?></title>
</head>
<body>
    <h1><?= $e($heading ?? 'Example page') ?></h1>
</body>
</html>
```

Data passed to the renderer becomes local variables. The installed PHP view engine provides `$e()` for HTML escaping.

## Rendering Views with Supported Methods

The installed core defines the `view()` helper. `bootstrap/app.php` initializes the global application used by the helper:

```php
public function index(): string
{
    return view('example', [
        'title' => 'Example',
        'heading' => 'Rendered view',
    ]);
}
```

`view('example')` resolves `resources/views/example.php` and returns the rendered HTML string.

When an application instance is available directly, its verified render method can be used:

```php
$html = $app->render('example', ['title' => 'Example']);
```

## Returning HTML from a Controller

The router accepts strings, so a controller can safely return a small HTML response without the view service:

```php
public function index(): string
{
    return '<h1>Example page</h1>';
}
```

Use a view file when the markup becomes substantial. A plain PHP `include` with output buffering is also possible, but the verified `view()` or `$app->render()` APIs provide the configured view path and data handling.

## Escaping Output

Escape dynamic values inside rendered templates:

```php
<h1><?= $e($title) ?></h1>
```

The `$e()` callable escapes values for HTML output. Do not print request, database, or external values directly. Escaping output does not replace input validation.

## Partials

The installed engine supports `$include()` inside a rendered view:

```php
<?= $include('partials.header', ['appName' => 'Intisari App']) ?>
```

This resolves `resources/views/partials/header.php`. The supplied data overrides matching values inherited from the parent view.

The existing `home.php` also uses the implemented `$extend()`, `$start()`, `$end()`, and `$section()` layout APIs with `layouts/app.php`. These helpers are available only within templates rendered by the configured view engine.

## Common Mistakes

- Placing templates outside `resources/views/` without changing the configured view path.
- Using a filename that does not match the view name.
- Calling `view()` before the application is initialized.
- Printing untrusted dynamic values without `$e()`.
- Calling `$include()` or layout helpers from a PHP file that was not rendered by the view engine.
- Returning an unsupported object instead of the rendered string.
- Exposing `resources/views/` through the web server; only `public/` should be public.

## Next

Continue to [Middleware](middleware.md).
