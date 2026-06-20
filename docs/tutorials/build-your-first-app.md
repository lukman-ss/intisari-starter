# Build Your First App

This tutorial walks you through building a two-page IntisariPHP application from scratch. We will add a new "About Us" page with a custom controller, dynamic view layout extension, and a feature test to verify its behavior.

No database, no authentication, and no middleware are required — just routes, controllers, views, and tests.

---

## 1. Create Project

First, create a new project using Composer:

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
```

This commands pulls down the starter application, installs all required framework packages, and sets up autoloading.

---

## 2. Copy Env

Initialize your local environment configuration file:

**macOS / Linux:**
```bash
cp .env.example .env
```

**Windows PowerShell:**
```powershell
Copy-Item .env.example .env
```

The defaults configured in `.env` are suitable for local development.

---

## 3. Run Server

Start the PHP built-in development server using the Composer script:

```bash
composer serve
```

You should see:
```text
Intisari development server started
http://127.0.0.1:8000
```

---

## 4. Open Default Page

Open your browser and navigate to [http://127.0.0.1:8000](http://127.0.0.1:8000). You will see the default welcome screen.

Let's look at [routes/web.php](file:///d:/PHP%20PACKAGIST/intisari-starter/routes/web.php) to see how this page is served:

```php
$app->get('/', [HomeController::class, 'index']);
```

This route matches `GET /` and executes the `index` method on `HomeController`.

---

## 5. Create Controller

Let's build a new controller for our About page. Create the file `app/Controllers/AboutController.php` with the following content:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

final class AboutController
{
    public function index(): string
    {
        return view('about', [
            'title' => 'About Us',
            'description' => 'This is the about page of our first IntisariPHP application.',
        ]);
    }
}
```

---

## 6. Register Route

Next, register the route in [routes/web.php](file:///d:/PHP%20PACKAGIST/intisari-starter/routes/web.php).

Open the file and import the controller at the top, then register a new `GET /about` route pointing to `AboutController@index`:

```php
<?php

declare(strict_types=1);

use App\Controllers\AboutController;  // 1. Import the new controller
use App\Controllers\HomeController;
use App\Controllers\StatusController;
use Intisari\Application;

assert($app instanceof Application);

$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);

// 2. Register the new route
$app->get('/about', [AboutController::class, 'index']);
```

---

## 7. Add About Page View

IntisariPHP features a native template engine with layout extensions. Create a new view file at `resources/views/about.php`:

```php
<?php

declare(strict_types=1);

// Extend the core app layout
$extend('layouts.app');

// Fill the title section
$start('title');
echo $e($title ?? 'About Us');
$end();

// Fill the content section
$start('content');
?>
<main>
    <h1><?= $e($title ?? 'About Us') ?></h1>
    <p><?= $e($description ?? 'Welcome to the about page.') ?></p>
</main>
<?php
$end();
```

Go to [http://127.0.0.1:8000/about](http://127.0.0.1:8000/about) in your browser. You will see the new page rendered with the app layout header and your custom dynamic variables.

---

## 8. Add a Simple Test

Let's write a test to make sure our new page is stable. Create the file `tests/Feature/AboutPageTest.php`:

```php
<?php

declare(strict_types=1);

namespace Tests\Feature;

use Lukman\Http\Request;
use Tests\TestCase;

final class AboutPageTest extends TestCase
{
    public function testAboutPageReturns200AndExpectedContent(): void
    {
        $app = $this->createApplication();
        $response = $app->handle(new Request('GET', '/about'));

        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('About Us', $response->content());
        $this->assertStringContainsString('first IntisariPHP application', $response->content());
    }
}
```

---

## 9. Run Composer Test

Run the test suite using the Composer shortcut command:

```bash
composer test
```

You will see:
```text
PHPUnit 10.x by Sebastian Bergmann and contributors.

OK (136 tests, 416 assertions)
```

Your new test has run and passed alongside the starter's default tests!

---

## Troubleshooting

### Class Not Found / Autoload Error
* **Symptom**: `Class "App\Controllers\AboutController" not found`.
* **Solution**: Ensure your namespace is `App\Controllers` (pluralized with an `s`) and that the file is located at `app/Controllers/AboutController.php` (case-sensitive). If everything looks correct, try regenerating the autoload files:
  ```bash
  composer dump-autoload
  ```

### View Template Not Found
* **Symptom**: `View file [...] not found`.
* **Solution**: Ensure the template is named exactly `about.php` (all lowercase) and resides under `resources/views/`.

### Route Typo or 404
* **Symptom**: Page displays `404 Not Found`.
* **Solution**: Check that the route registered in `routes/web.php` exactly matches the URL path (paths are case-sensitive). Ensure the web server is running.

---

## Next Steps

Now that you have built your first page:
- Read about [Routing](../basics/routing.md) to understand route groups and parameters.
- Learn how [Controllers](../basics/controllers.md) manage request logic.
- Look into [Views](../basics/views.md) to build more complex template structures.

---

## Next

Continue to the [REST API Basics Tutorial](rest-api.md).
