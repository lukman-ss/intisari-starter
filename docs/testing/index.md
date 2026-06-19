# Testing

IntisariPHP Starter uses **PHPUnit** for automated testing. PHPUnit is included as a development dependency and is configured out of the box.

## Running Tests

Run all tests with Composer:

```bash
composer test
```

This executes `phpunit` using the configuration in `phpunit.xml`.

Expected output:

```text
PHPUnit 10.x by Sebastian Bergmann and contributors.

.............                                               13 / 13 (100%)

Time: 00:00.123, Memory: 10.00 MB

OK (13 tests, 25 assertions)
```

### Run Specific Test Suites

Run only unit tests:

```bash
vendor/bin/phpunit --testsuite Unit
```

Run only feature tests:

```bash
vendor/bin/phpunit --testsuite Feature
```

### Run a Single Test File

```bash
vendor/bin/phpunit tests/Unit/ExampleTest.php
```

### Run Tests Matching a Filter

```bash
vendor/bin/phpunit --filter testTrueIsTrue
```

## Test Structure

```text
tests/
  Unit/               Isolated unit tests
  Feature/            Tests that exercise multiple components
  Support/            Test helpers and fixtures
  TestCase.php        Base test class with createApplication() helper
```

### phpunit.xml

The `phpunit.xml` file at the project root defines three test suites:

```xml
<testsuites>
    <testsuite name="Unit">
        <directory>tests/Unit</directory>
    </testsuite>
    <testsuite name="Feature">
        <directory>tests/Feature</directory>
    </testsuite>
    <testsuite name="Application">
        <directory>tests</directory>
        <exclude>tests/Unit</exclude>
        <exclude>tests/Feature</exclude>
    </testsuite>
</testsuites>
```

It also sets environment variables for the test environment:

```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="APP_DEBUG" value="true"/>
</php>
```

## Writing a Simple Test

Create a test class under `tests/Unit/`:

```php
<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function test_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
```

Save this as `tests/Unit/ExampleTest.php` and run:

```bash
composer test
```

## Organizing Tests

### Unit Tests

Unit tests verify individual classes or methods in isolation. Place them in `tests/Unit/`.

```php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function test_add_returns_sum(): void
    {
        $calculator = new Calculator();
        $result = $calculator->add(2, 3);
        
        $this->assertEquals(5, $result);
    }
}
```

### Feature Tests

Feature tests verify that multiple components work together correctly. Place them in `tests/Feature/`.

```php
namespace Tests\Feature;

use Tests\TestCase;

final class HomePageTest extends TestCase
{
    public function test_home_controller_returns_string(): void
    {
        $controller = new \App\Controllers\HomeController();
        $result = $controller->index();
        
        $this->assertIsString($result);
    }
}
```

### Application Tests

Tests that don't fit into Unit or Feature go directly in `tests/` and are picked up by the `Application` test suite.

## Testing Controllers Conceptually

Controllers can be tested as plain PHP classes without booting the full application:

```php
<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Controllers\HomeController;
use App\Controllers\StatusController;
use PHPUnit\Framework\TestCase;

final class ControllerTest extends TestCase
{
    public function test_home_returns_string(): void
    {
        $controller = new HomeController();
        $result = $controller->index();
        
        $this->assertIsString($result);
    }
    
    public function test_status_returns_json(): void
    {
        $controller = new StatusController();
        $response = $controller->index();
        
        $this->assertEquals('application/json', $response->headers()->get('Content-Type'));
    }
}
```

This approach tests controller methods directly without HTTP overhead.

## Base TestCase

`tests/TestCase.php` provides a `createApplication()` helper that bootstraps the application with routes loaded:

```php
<?php

declare(strict_types=1);

namespace Tests;

use Intisari\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function createApplication(): Application
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        $app->loadRoutes($app->routesPath('web.php'));
        
        return $app;
    }
}
```

Extend this class when your tests need the full application instance.

## Common Failures

### Test class not found

**Symptom:** `Class "Tests\Unit\ExampleTest" not found`

**Solution:** Run Composer dump to regenerate autoloading:

```bash
composer dump-autoload
```

### PHPUnit not installed

**Symptom:** `phpunit: command not found`

**Solution:** Install dependencies first:

```bash
composer install
```

### Assertion failure

**Symptom:** `Failed asserting that false is true.`

**Solution:** Check the test logic and the value being tested. Use `var_dump()` or `$this->assertSame()` with a message:

```php
$this->assertSame($expected, $actual, 'Expected and actual values differ');
```

### Storage directory not writable

**Symptom:** `Permission denied` when writing to `storage/`

**Solution:** Ensure storage directories exist and are writable:

```bash
mkdir -p storage/cache storage/logs storage/framework
chmod -R 775 storage/
```

On Windows PowerShell:

```powershell
New-Item -ItemType Directory -Force -Path storage/cache, storage/logs, storage/framework
```

### Tests pass locally but fail in CI

**Symptom:** Tests work on your machine but fail in GitHub Actions or other CI.

**Solution:** Ensure `composer install` runs before tests in your CI pipeline:

```yaml
- run: composer install --no-interaction --prefer-dist
- run: composer test
```

## Best Practices

### Keep Tests Focused

Each test should verify one behavior. If a test has multiple assertions testing different things, split it into separate tests.

```php
// Good — one test per behavior
public function test_add_returns_sum(): void { }
public function test_add_with_negative_numbers(): void { }

// Bad — multiple unrelated assertions
public function test_calculator(): void
{
    $this->assertEquals(5, $calculator->add(2, 3));
    $this->assertEquals(10, $calculator->multiply(2, 5));
    $this->assertEquals(2, $calculator->subtract(5, 3));
}
```

### Use Descriptive Test Names

Test names should describe the behavior being tested:

```php
// Good
public function test_user_name_cannot_be_empty(): void { }
public function test_order_total_includes_tax(): void { }

// Bad
public function test_user(): void { }
public function test_order(): void { }
```

### Don't Test Implementation Details

Test what the code does, not how it does it:

```php
// Good — tests the result
public function test_user_is_created(): void
{
    $result = $service->createUser('Alice');
    $this->assertEquals('Alice', $result->name);
}

// Bad — tests internal implementation
public function test_user_service_calls_repository(): void { }
```

### Run composer install Before Testing

In a fresh checkout or CI environment:

```bash
composer install --no-interaction --prefer-dist
composer test
```

### Ensure Storage Directories Are Writable

Tests that write files (logs, cache) need writable storage directories:

```bash
mkdir -p storage/cache storage/logs storage/framework
```

### Test Both Success and Failure

Always test the happy path and error conditions:

```php
public function test_valid_input_returns_result(): void { }
public function test_invalid_input_throws_exception(): void { }
public function test_empty_input_returns_default(): void { }
```

## HTTP Testing

The starter does not include HTTP testing helpers (request/response testing utilities). For HTTP-level testing, use test utilities provided by the IntisariPHP core if available, or test controllers as plain PHP classes as shown above.

## Next

Continue to [Deployment](../deployment/index.md).
