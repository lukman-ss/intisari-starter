# Build Your First Intisari Application

This tutorial builds a simple static IntisariPHP Starter application with two pages.

You will create a project, run the local server, add routes, create a controller, return simple strings, add an `/about` page, write a small test, and run the test command. This tutorial does not use a database.

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

```text
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

## 3. Add Route `/`

Open `routes/web.php`.

Add or confirm the home route:

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
        return 'Hello Intisari';
    }
}
```

The controller uses the `App\Controllers` namespace, which matches the starter PSR-4 autoload configuration.

## 5. Return Simple HTML/String Response

The `index` method returns a string:

```php
public function index(): string
{
    return 'Hello Intisari';
}
```

This is the smallest useful response for a static page. HTML strings can also be returned, but plain strings are enough for the first application.

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
        return 'Hello Intisari';
    }

    public function about(): string
    {
        return 'About Intisari';
    }
}
```

Then update `routes/web.php`:

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
$app->get('/about', [HomeController::class, 'about']);
```

Open:

```text
http://127.0.0.1:8000/about
```

## 7. Add Simple Test

Create `tests/Unit/HomeControllerTest.php`:

```php
<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controllers\HomeController;
use PHPUnit\Framework\TestCase;

final class HomeControllerTest extends TestCase
{
    public function test_home_returns_string(): void
    {
        $controller = new HomeController();

        $this->assertSame('Hello Intisari', $controller->index());
    }
}
```

## 8. Run `composer test`

Run the starter test command:

```bash
composer test
```

The starter uses PHPUnit.

## Final Project Structure

```text
my-app/
  app/
    Controllers/
      HomeController.php
  routes/
    web.php
  tests/
    Unit/
      HomeControllerTest.php
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

If existing tests assert old controller output, update the test or controller intentionally.

## Related Documentation

- [Routing](../basics/routing.md)
- [Controllers](../basics/controllers.md)
- [Views](../basics/views.md)
- [Testing](../testing/index.md)

## Next Steps

Continue with [Routing](../basics/routing.md).
