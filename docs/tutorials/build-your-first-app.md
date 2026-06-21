# Build Your First App

This tutorial builds an `/about` page with a controller, a PHP view, a route, and a feature test. It does not require a database, authentication, or middleware.

## 1. Create Project

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
```

## 2. Copy Environment File

On macOS or Linux:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

The example values are suitable for this local tutorial.

## 3. Run Server

```bash
composer serve
```

The local server listens on `http://127.0.0.1:8000`. Keep this terminal running and use a second terminal for later commands. Stop the server with `Ctrl+C`.

## 4. Open Default Page

Open `http://127.0.0.1:8000/` in a browser. The default route is registered in `routes/web.php`:

```php
$app->get('/', [HomeController::class, 'index']);
```

This maps `GET /` to the public `index()` method on `HomeController`. The same route file also registers `/health` and `/status`.

## 5. Create Controller

Generate the controller:

```bash
php intisari make:controller AboutController
```

Replace the generated content in `app/Controllers/AboutController.php` with:

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

The verified `view()` helper renders a file from `resources/views/` and returns its HTML as a string.

## 6. Register Route

Replace `routes/web.php` with:

```php
<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\StatusController;
use Intisari\Application;

assert($app instanceof Application);

$app->get('/', [HomeController::class, 'index']);
$app->get('/health', static fn (): string => 'OK');
$app->get('/status', [StatusController::class, 'index']);
$app->get('/about', [AboutController::class, 'index']);
```

Confirm the new route is registered:

```bash
php intisari route:list
```

## 7. Add About Page

Create `resources/views/about.php`:

```php
<?php

declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title ?? 'About Us') ?></title>
</head>
<body>
    <main>
        <h1><?= $e($title ?? 'About Us') ?></h1>
        <p><?= $e($description ?? '') ?></p>
    </main>
</body>
</html>
```

The view engine provides `$e()` for escaped HTML output. Open `http://127.0.0.1:8000/about` and confirm the heading and description appear.

## 8. Add a Simple Test

Create `tests/Feature/AboutPageTest.php`:

```php
<?php

declare(strict_types=1);

namespace Tests\Feature;

use Lukman\Http\Request;
use Tests\TestCase;

final class AboutPageTest extends TestCase
{
    public function testAboutPageReturnsExpectedContent(): void
    {
        $response = $this->createApplication()->handle(
            new Request('GET', '/about')
        );

        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('About Us', $response->content());
        $this->assertStringContainsString(
            'first IntisariPHP application',
            $response->content()
        );
    }
}
```

`createApplication()` loads the normal bootstrap and `routes/web.php`; it does not start a development server.

## 9. Run Composer Test

From a second terminal, or after stopping the server, run:

```bash
composer test
```

The complete suite should finish with an `OK` result.

## 10. Troubleshooting

- **Controller class not found:** confirm the file is `app/Controllers/AboutController.php`, the namespace is `App\Controllers`, and the class name matches. Run `composer dump-autoload` if needed.
- **View not found:** confirm the file is exactly `resources/views/about.php` and the controller calls `view('about', ...)`.
- **404 response:** confirm `$app->get('/about', ...)` is in `routes/web.php`, then run `php intisari route:list`.
- **Blank page or 500 response:** inspect the terminal running `composer serve` and check both edited PHP files with `php -l`.
- **Test does not discover the route:** ensure the test extends `Tests\TestCase` and calls `createApplication()`.
- **Port 8000 is busy:** run `php intisari serve --port=8080` and open the matching URL.

## 11. Next Steps

Continue to [REST API Basics](rest-api.md), or review [Routing](../basics/routing.md), [Controllers](../basics/controllers.md), and [Views](../basics/views.md).
