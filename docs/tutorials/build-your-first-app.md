# Build Your First App

This tutorial walks you through building a small two-page IntisariPHP application from scratch. No database, no authentication, no middleware — just routes, controllers, and a test.

## What You Will Build

- A home page at `/` that returns "Hello Intisari"
- An about page at `/about` that returns "About Intisari"
- A PHPUnit test that verifies the home page works

**Estimated time:** 10 minutes

## 1. Create the Project

Create a new project using Composer:

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
```

This downloads the starter, installs all dependencies, and sets up autoloading.

## 2. Copy the Environment File

Copy the example environment file to create your local configuration:

**macOS / Linux:**

```bash
cp .env.example .env
```

**Windows PowerShell:**

```powershell
Copy-Item .env.example .env
```

The `.env` file stores your local settings. The defaults work for this tutorial without changes.

## 3. Run the Development Server

Start the built-in development server:

```bash
composer serve
```

You should see:

```text
Intisari development server started
http://127.0.0.1:8000
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser. You should see the default IntisariPHP welcome page.

## 4. Edit the Home Route

Open `routes/web.php`. The home route already exists:

```php
$app->get('/', [HomeController::class, 'index']);
```

This route maps `GET /` to the `index()` method of `HomeController`. No changes needed here.

## 5. Create (or Edit) the HomeController

Open `app/Controllers/HomeController.php`. Replace the content with:

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

Save the file and reload [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser. You should now see "Hello Intisari" instead of the welcome page.

## 6. Add an About Route

Add a second method to `HomeController`:

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

Now register the new route in `routes/web.php`:

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
$app->get('/about', [HomeController::class, 'about']);
```

Open [http://127.0.0.1:8000/about](http://127.0.0.1:8000/about) in your browser. You should see "About Intisari".

## 7. Add a PHPUnit Test

Create a test file at `tests/Unit/HomeControllerTest.php`:

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

    public function test_about_returns_string(): void
    {
        $controller = new HomeController();

        $this->assertSame('About Intisari', $controller->about());
    }
}
```

This test creates a `HomeController` instance and verifies both methods return the expected strings.

## 8. Run the Tests

```bash
composer test
```

Expected output:

```
PHPUnit 10.x by Sebastian Bergmann and contributors.

..                                                          2 / 2 (100%)

Time: 00:00.050, Memory: 8.00 MB

OK (2 tests, 2 assertions)
```

All tests should pass.

## Final Project Structure

The files you modified or created:

```text
my-app/
  app/Controllers/HomeController.php   ← Edited: added about() method
  routes/web.php                       ← Edited: added /about route
  tests/Unit/HomeControllerTest.php    ← Created: new test file
```

Other important files that were already in place:

```text
my-app/
  public/index.php                     ← Front controller
  bootstrap/app.php                    ← Application bootstrap
  config/app.php                       ← Application configuration
  .env                                 ← Local environment settings
  composer.json                        ← Dependencies and scripts
  phpunit.xml                          ← Test configuration
```

## Common Mistakes

### Wrong Namespace

Controllers must use `namespace App\Controllers;` (with an "s").

```php
// WRONG
namespace App\Controller;  // missing "s"

// CORRECT
namespace App\Controllers;
```

### Missing Import in routes/web.php

Always import the controller class at the top of `routes/web.php`:

```php
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
```

Without the `use` statement, PHP cannot find the `HomeController` class.

### Wrong Method Name in Route

The route must reference a method that exists in the controller:

```php
// WRONG — "home" method does not exist
$app->get('/', [HomeController::class, 'home']);

// CORRECT — "index" method exists
$app->get('/', [HomeController::class, 'index']);
```

### URL Typo

The route path must match exactly:

```php
$app->get('/about', [HomeController::class, 'about']);
```

- `http://127.0.0.1:8000/about` ✓ works
- `http://127.0.0.1:8000/About` ✗ case-sensitive, returns 404
- `http://127.0.0.1:8000/about/` ✗ trailing slash may return 404

### Server Not Running

If you see "Connection refused" in your browser, the development server is not running:

```bash
composer serve
```

### Missing declare(strict_types=1)

Always include `declare(strict_types=1);` at the top of PHP files:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;
```

### Forgetting to Save the File

If your changes don't appear in the browser, make sure you saved the file. The development server reloads files on each request, but unsaved changes won't be picked up.

## What's Next

You now have a working IntisariPHP application with routes, controllers, and tests. Continue learning:

- [Routing](../basics/routing.md) — learn about HTTP methods, parameters, and route organization
- [Controllers](../basics/controllers.md) — learn about return values, naming conventions, and best practices
- [Views](../basics/views.md) — learn how to render HTML templates
- [REST API Basics](rest-api.md) — build a JSON API with IntisariPHP
