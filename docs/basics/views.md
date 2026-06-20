# Views

## What Is a View?

A view is a PHP file that produces HTML. Views keep presentation markup separate from route and controller logic.

IntisariPHP Starter uses plain PHP view files. It does not use Blade or Twig.

## `resources/views/` Directory

The core view service searches `resources/views/` by default.

```text
resources/views/
├── home.php
├── errors/
│   ├── 404.php
│   └── 500.php
├── layouts/
│   └── app.php
└── partials/
    └── header.php
```

Add or edit files here when changing rendered HTML. View files are application source and must not be directly web-accessible.

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

Data passed to the renderer is available as local variables. The view engine provides `$e()` for escaped output.

## Rendering a View

The `view()` helper is defined by IntisariPHP core and loaded through Composer. It returns the rendered HTML string:

```php
public function index(): string
{
    return view('example', [
        'title' => 'Example',
        'heading' => 'Rendered by IntisariPHP',
    ]);
}
```

The name `example` resolves to `resources/views/example.php`.

The application also exposes the verified renderer method:

```php
$html = $app->render('example', ['title' => 'Example']);
```

Use `view()` inside controllers when the global application has been initialized. Use `$app->render()` when working directly with an application instance.

## Returning HTML from a Controller

The router accepts a string return value, so a controller can return a small HTML response directly:

```php
public function index(): string
{
    return '<h1>Example page</h1>';
}
```

Use a view file when the markup is more than a small response.

## Escaping Output

Escape dynamic values before placing them in HTML:

```php
<h1><?= $e($title) ?></h1>
```

The `$e()` helper is provided inside rendered view files by the installed view engine. It escapes output for HTML. Do not print untrusted values directly:

```php
<!-- Unsafe for untrusted data -->
<h1><?= $title ?></h1>
```

Escaping is for output safety. Validate input separately before using it in application logic.

## Partials

The installed view engine supports partial rendering through `$include()` inside a view:

```php
<?= $include('partials.header', ['appName' => 'Intisari App']) ?>
```

This resolves `resources/views/partials/header.php` and merges the supplied data with the current view data.

For plain PHP files rendered outside the core view engine, use normal PHP `include` or `require` and handle data and escaping explicitly.

## Common Mistakes

### Wrong View Name

`view('example')` expects `resources/views/example.php`. Nested names use dot notation, such as `view('partials.header')`.

### Missing Application Initialization

The global `view()` helper requires the IntisariPHP application instance. Normal web requests initialize it through `bootstrap/app.php`.

### Unescaped Dynamic Output

Use `$e()` for values from requests, databases, or external services before printing them into HTML.

### Direct Web Access

Do not expose `resources/views/` as a web root. Only `public/` should be web-accessible.

### Returning a View Object

The verified `view()` and `$app->render()` APIs return strings. Return that string from the controller.

## Next

Continue to [Middleware](middleware.md).
