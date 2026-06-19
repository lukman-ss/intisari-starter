# Testing

IntisariPHP Starter uses PHPUnit for automated tests.

The starter includes a `phpunit.xml` file and a `tests/` directory. Composer provides a `test` script for running the test suite.

## Running Tests

```bash
composer test
```

Or:

```bash
php intisari test
```

## Writing Your First Test

Create a test class under `tests/`.

```php
<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function test_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
```

Run it with:

```bash
composer test
```

## Organizing Tests

The starter uses this testing structure:

```text
tests/
phpunit.xml
```

The repository also includes `tests/Feature/`, `tests/Unit/`, and `tests/TestCase.php`.

Use `tests/Unit/` for isolated logic and `tests/Feature/` for broader application behavior when that structure fits your project.

## Testing Routes and Controllers

The starter includes tests that cover routes, views, bootstrap behavior, public entry points, and console entry points.

For HTTP testing, use the test utilities available in installed IntisariPHP core packages if your version provides them. Do not assume a specific HTTP helper API unless it exists in your project.

At a minimum, controllers can be tested as regular PHP classes when their methods have simple inputs and outputs.

```php
$controller = new \App\Controllers\HomeController();

$this->assertIsString($controller->index());
```

## Common Test Failures

### Composer Dependencies Missing

Run Composer install before running tests in a fresh checkout.

```bash
composer install
```

### Autoload Error

Check that class namespaces match their file paths. If needed, rebuild Composer autoload files:

```bash
composer dump-autoload
```

### Environment Difference

Test behavior may change if `.env` values are different from expected test values.

Check `phpunit.xml` and any environment setup used by your tests.

### File Permission Issue

Tests that write cache, logs, or framework runtime files need writable storage directories.

## Best Practices

- Keep tests focused on one behavior.
- Use clear test method names.
- Test controllers and routes after changing request handling.
- Avoid relying on local machine state.
- Keep test data small and explicit.

## Next Steps

Continue with [Deployment](../deployment/index.md).
