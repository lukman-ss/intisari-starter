# Testing

IntisariPHP Starter includes PHPUnit configuration, a shared application test case, and tests for project behavior.

## Running Tests

Run all configured suites:

```bash
composer test
```

Run one suite, file, or matching test:

```bash
vendor/bin/phpunit --testsuite Unit
vendor/bin/phpunit tests/Feature/GeneratorTest.php
vendor/bin/phpunit --filter testHealthPageReturnsOk
```

## PHPUnit Version

[`composer.json`](../../composer.json) requires `phpunit/phpunit` with `^10.5`. [`phpunit.xml`](../../phpunit.xml) loads `vendor/autoload.php`, enables colors, and sets `APP_ENV=testing` and `APP_DEBUG=true` for tests.

## Test Directory Structure

```text
tests/
|-- Unit/            Unit suite
|-- Feature/         Feature suite
|-- Support/         Test fixtures and support files
|-- TestCase.php     Shared application bootstrap helper
`-- *.php            Application suite tests
```

`phpunit.xml` configures exactly three suites:

- `Unit` for `tests/Unit/`.
- `Feature` for `tests/Feature/`.
- `Application` for `tests/`, excluding the Unit and Feature directories.

`tests/TestCase.php` provides `createApplication()`, which loads `bootstrap/app.php` and `routes/web.php`.

## Writing a Simple Test

Create `tests/Unit/SampleTest.php`:

```php
<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class SampleTest extends TestCase
{
    public function testAddition(): void
    {
        $this->assertSame(4, 2 + 2);
    }
}
```

## Testing Controllers as Plain PHP Classes

Controllers can be instantiated directly when their method does not require request setup:

```php
<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controllers\StatusController;
use PHPUnit\Framework\TestCase;

final class StatusControllerTest extends TestCase
{
    public function testStatusControllerReturnsOk(): void
    {
        $response = (new StatusController())->index();

        $this->assertSame(200, $response->status());
        $this->assertSame(['status' => 'ok'], json_decode($response->content(), true));
    }
}
```

## Testing CLI Commands

Register `routes/console.php`, use the installed console input/output classes, and capture an in-memory stream:

```php
$app = require dirname(__DIR__) . '/bootstrap/app.php';
require dirname(__DIR__) . '/routes/console.php';

$stream = fopen('php://memory', 'w+');
$output = new Lukman\Console\Output($stream, $stream);
$exitCode = $app->runConsole(
    new Lukman\Console\Input(['intisari', 'hello']),
    $output
);

rewind($stream);
$content = stream_get_contents($stream);
fclose($stream);

$this->assertSame(0, $exitCode);
$this->assertStringContainsString('Hello Intisari', $content);
```

Generator tests should use a temporary application path and clean generated files in `tearDown()`.

## Testing Routes

The installed application provides an HTTP test response helper, and the starter's `createApplication()` method loads web routes:

```php
$response = $this->createApplication()
    ->test(new Lukman\Http\Request('GET', '/health'))
    ->assertStatus(200)
    ->assertSee('OK');

$this->assertSame('OK', $response->content());
```

Tests may also inspect `$app->router()->routes()->all()` or call `$app->handle(new Request(...))`, as existing application tests do. No development server is required.

## Common Failures

- **PHPUnit is unavailable:** run `composer install`.
- **Class not found:** match the `Tests\` or `App\` namespace to its PSR-4 path, then run `composer dump-autoload` when needed.
- **Route test returns 404:** use `createApplication()` or explicitly load `routes/web.php`.
- **CLI command is undefined:** require `routes/console.php` before `runConsole()`.
- **Generated files remain:** use a unique temporary path and remove it during teardown.
- **Storage write failure:** ensure the required paths under `storage/` exist and are writable.

## CI Note

`.github/workflows/tests.yml` runs on pushes, pull requests, and manual dispatch for PHP 8.2 and 8.3. It validates Composer metadata, installs dependencies, runs `composer test`, and runs `composer docs:check` without starting a development server.

Keep tests portable: avoid network dependencies, fixed machine paths, and persistent generated files.

## Next

Continue to [Deployment](../deployment/index.md).
