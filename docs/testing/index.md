# Testing

IntisariPHP Starter uses PHPUnit for automated tests.

The starter includes a `tests/` directory and a `phpunit.xml` configuration file. Composer provides a `test` script for running PHPUnit.

## Running Tests

```bash
composer test
```

This command runs the PHPUnit configuration included with the starter.

## Writing Your First Test

Create a test class under `tests/`:

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

The required testing structure is:

```text
tests/
phpunit.xml
```

The repository also includes:

```text
tests/Feature/
tests/Unit/
tests/TestCase.php
```

Use `tests/Unit/` for isolated logic and `tests/Feature/` for broader application behavior when that structure fits your project.

## Testing Controllers Conceptually

Controllers can be tested as regular PHP classes when their methods have simple inputs and outputs.

Example:

```php
$controller = new \App\Controllers\HomeController();

$this->assertIsString($controller->index());
```

For HTTP testing, use test utilities only if they are available in your installed IntisariPHP core packages. Do not assume a specific HTTP testing helper exists.

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
