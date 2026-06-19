# Views

Views are HTML templates that render output to the browser. They live in `resources/views/` and use plain PHP mixed with HTML to display data.

## The resources/views/ Directory

This directory stores all your view templates. Each view is a PHP file that outputs HTML.

```text
resources/views/
  home.php                Home page
  layouts/app.php         Base layout
  partials/header.php     Shared header partial
  errors/404.php          404 error page
  errors/500.php          500 error page
```

## Views in MVC

In the MVC (Model-View-Controller) pattern, views are responsible only for **displaying data**. They should not contain business logic, database queries, or complex calculations.

The flow works like this:

1. **Controller** receives the request and prepares the data
2. **View** receives the data from the controller and renders HTML
3. **HTML** is sent back to the browser

Views should be simple enough that a front-end developer can read and modify them without understanding PHP deeply.

## Simple View Example

Create a view file at `resources/views/home.php`:

```php
<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome</title>
</head>
<body>
    <h1>Hello Intisari</h1>
    <p>A lightweight PHP application starter.</p>
</body>
</html>
```

## Controller Returning a View

A controller can render a view and return the HTML:

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

If the `view()` helper is available from IntisariPHP core, you can use it instead:

```php
public function index(): string
{
    return view('home');
}
```

The `view('home')` function renders `resources/views/home.php` and returns the HTML string.

### Passing Data to Views

Pass an array of data as the second argument:

```php
public function index(): string
{
    return view('home', [
        'title' => 'Welcome',
        'description' => 'A lightweight PHP application starter.',
    ]);
}
```

In the view, access data as PHP variables:

```php
<h1><?= $title ?></h1>
<p><?= $description ?></p>
```

## Controller Returning HTML Directly

For simple pages, a controller can return an HTML string without a view file:

```php
public function health(): string
{
    return '<html><body><h1>OK</h1></body></html>';
}
```

For structured pages with data, use view files to keep controllers clean.

## Escaping Output

Always escape dynamic values before printing them in HTML to prevent XSS (Cross-Site Scripting) attacks.

### Using htmlspecialchars

```php
<h1><?= htmlspecialchars($title ?? 'Welcome', ENT_QUOTES, 'UTF-8') ?></h1>
<p><?= htmlspecialchars($description ?? '', ENT_QUOTES, 'UTF-8') ?></p>
```

### Using the $e() Helper

If the `$e()` helper is available from IntisariPHP core:

```php
<h1><?= $e($title ?? 'Welcome') ?></h1>
<p><?= $e($description ?? '') ?></p>
```

**Never output raw user input without escaping:**

```php
<!-- WRONG — XSS vulnerability -->
<p>Hello, <?= $_GET['name'] ?></p>

<!-- CORRECT — escaped output -->
<p>Hello, <?= htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
```

## Best Practices

### Keep Logic Out of Views

Views should only display data. Move business logic to controllers or service classes.

**Wrong — logic in view:**

```php
<?php
// Complex calculation inside view
$total = 0;
foreach ($orders as $order) {
    $discount = $order['price'] * 0.1;
    $total += $order['price'] - $discount;
}
?>
<p>Total: <?= $total ?></p>
```

**Correct — logic in controller:**

```php
// Controller
public function summary(): string
{
    $total = $this->calculateTotal($orders);
    return view('summary', ['total' => $total]);
}
```

```php
<!-- View -->
<p>Total: <?= htmlspecialchars($total, ENT_QUOTES, 'UTF-8') ?></p>
```

### Escape All Dynamic Output

Every value that comes from user input, a database, or an external source must be escaped before rendering:

```php
<p><?= htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8') ?></p>
```

### Split Reusable Partials

If the same HTML appears on multiple pages, extract it into a partial file:

```text
resources/views/
  partials/
    header.php
    footer.php
```

Then include the partial in each page:

```php
<?php include __DIR__ . '/../partials/header.php'; ?>

<main>
    <h1>Page Content</h1>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
```

### Avoid Database Queries Inside Views

Never run SQL queries or fetch data inside a view. Prepare all data in the controller.

**Wrong — query in view:**

```php
<?php
$db = new PDO('sqlite:database/database.sqlite');
$users = $db->query('SELECT * FROM users')->fetchAll();
?>
<ul>
    <?php foreach ($users as $user): ?>
        <li><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>
```

**Correct — data prepared in controller:**

```php
// Controller
public function index(): string
{
    $users = $this->db->query('SELECT * FROM users')->fetchAll();
    return view('users', ['users' => $users]);
}
```

```php
<!-- View -->
<ul>
    <?php foreach ($users as $user): ?>
        <li><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>
```

## Next

Continue to [Middleware](middleware.md).
