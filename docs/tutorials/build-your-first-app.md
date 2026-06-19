# Build Your First Intisari Application

This tutorial builds a simple static IntisariPHP Starter application with two pages.

You will create a project, run the local server, add routes, create a controller, return simple HTML strings, add an `/about` page, and run the test command. This tutorial does not use a database.

## 1. Create Project

Create a new starter project with Composer:

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
```

Copy the environment template:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

## 2. Run Local Server

Start the local development server:

```bash
composer serve
```

Open:

```text
http://127.0.0.1:8000
```

The `composer serve` script runs:

```bash
php intisari serve
```

## 3. Create Route

Open `routes/web.php`.

The starter already has a home route:

```php
$app->get('/', [HomeController::class, 'index']);
```

This route maps `/` to the `index` method on `HomeController`.

## 4. Create Controller

Create or update `app/Controllers/HomeController.php`:

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

The controller uses the `App\Controllers` namespace, which matches the starter PSR-4 autoload configuration.

## 5. Return Simple HTML/String Response

The `index` method returns a string:

```php
public function index(): string
{
    return '<h1>Hello Intisari</h1>';
}
```

This is the smallest useful response for a static page. View rendering can be added later using IntisariPHP core view features available in your installed version.

Reload:

```text
http://127.0.0.1:8000
```

## 6. Add Second Page `/about`

Add an `about` method to `HomeController`:

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

    public function about(): string
    {
        return '<h1>About</h1><p>This is a simple IntisariPHP Starter page.</p>';
    }
}
```

Then update `routes/web.php`:

```php
use App\Controllers\HomeController;
use App\Controllers\StatusController;
use Intisari\Application;
use Lukman\Router\Router;

assert($app instanceof Application);
assert($router instanceof Router);

$app->get('/', [HomeController::class, 'index']);
$app->get('/about', [HomeController::class, 'about']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);
```

Open:

```text
http://127.0.0.1:8000/about
```

## 7. Run Tests

Run the starter test command:

```bash
composer test
```

The starter uses PHPUnit. Existing tests may need updates if you changed default route behavior expected by the repository tests.

## Final Project Structure

```text
my-app/
  app/
    Controllers/
      HomeController.php
      StatusController.php
  routes/
    web.php
  public/
    index.php
  composer.json
```

## Common Mistakes

### Wrong Namespace

Controllers should use:

```php
namespace App\Controllers;
```

### Missing Route Import

Import the controller before using `HomeController::class`.

```php
use App\Controllers\HomeController;
```

### Typo in URL

`/about` only works if the route path is also `/about`.

### Server Running from Wrong Directory

Run the server from the project root:

```bash
composer serve
```

### Test Expectations Changed

If existing tests assert the default home page content, update your application or test expectations intentionally.

## Related Documentation

- [Routing](../basics/routing.md)
- [Controllers](../basics/controllers.md)
- [Views](../basics/views.md)
- [Testing](../testing/index.md)

## Next Steps

Continue with [Routing](../basics/routing.md).
