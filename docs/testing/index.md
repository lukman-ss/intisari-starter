# Testing

IntisariPHP Starter is pre-configured for automated testing using **PHPUnit**. 

## Running Tests

You can execute the entire test suite using the Composer shortcut:

```bash
composer test
```

This runs the vendor binary `phpunit` using the configuration defined in [phpunit.xml](file:///d:/PHP%20PACKAGIST/intisari-starter/phpunit.xml).

To run tests matching a specific pattern:
```bash
vendor/bin/phpunit --filter testName
```

To run a single test file:
```bash
vendor/bin/phpunit tests/Feature/GeneratorTest.php
```

---

## PHPUnit Version

The project is configured for **PHPUnit 10.x** (defined in [composer.json](file:///d:/PHP%20PACKAGIST/intisari-starter/composer.json) as `^10.5`). 

---

## Test Directory Structure

The structure under the [tests/](file:///d:/PHP%20PACKAGIST/intisari-starter/tests/) directory aligns with the test suites defined in [phpunit.xml](file:///d:/PHP%20PACKAGIST/intisari-starter/phpunit.xml):

```text
tests/
  ├── Feature/            Integration/feature tests (e.g. CLI generators)
  ├── Unit/               Isolated component tests
  ├── TestCase.php        Base test class containing the bootstrap helper
  └── *.php               Root-level tests targeting application initialization, config, and routes
```

### Test Suites in `phpunit.xml`

Three test suites are configured:
1. **Unit**: Runs tests located under `tests/Unit/`.
2. **Feature**: Runs tests located under `tests/Feature/`.
3. **Application**: Runs all root-level tests directly under `tests/` (excluding `Unit` and `Feature` subdirectories).

To run a specific suite:
```bash
vendor/bin/phpunit --testsuite Unit
vendor/bin/phpunit --testsuite Feature
vendor/bin/phpunit --testsuite Application
```

---

## Writing a Simple Test

You can write basic unit tests by extending the standard `PHPUnit\Framework\TestCase`.

Create `tests/Unit/SampleTest.php`:

```php
<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class SampleTest extends TestCase
{
    public function testTrueIsTrue(): void
    {
        $this->assertTrue(true);
    }
}
```

---

## Testing Controllers as Plain PHP Classes

Since controllers in Intisari PHP are plain PHP classes, you can test them without booting the full application framework:

```php
<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Controllers\HomeController;
use PHPUnit\Framework\TestCase;

final class HomeControllerTest extends TestCase
{
    public function testHomeControllerIndexReturnsString(): void
    {
        $controller = new HomeController();
        $response = $controller->index();

        $this->assertIsString($response);
        $this->assertStringContainsString('Welcome to IntisariPHP', $response);
    }
}
```

---

## Testing CLI Commands

You can test CLI commands by building an application instance, registering console routes, and capturing the memory output stream of the console application runner:

```php
<?php

declare(strict_types=1);

namespace Tests;

use Lukman\Console\Input;
use Lukman\Console\Output;
use PHPUnit\Framework\TestCase;

final class ConsoleCommandTest extends TestCase
{
    public function testHelloCommandExecutes(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';
        require dirname(__DIR__) . '/routes/console.php';

        $stream = fopen('php://memory', 'w+');
        $output = new Output($stream, $stream);
        $input = new Input(['intisari', 'hello']);

        $exitCode = $app->runConsole($input, $output);

        rewind($stream);
        $consoleOutput = stream_get_contents($stream);
        fclose($stream);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Hello Intisari', $consoleOutput);
    }
}
```

---

## Testing Application Bootstrap

The starter contains tests to ensure the bootstrapping process completes without side effects and loads the correct configuration and providers:

```php
<?php

declare(strict_types=1);

namespace Tests;

use Intisari\Application;
use PHPUnit\Framework\TestCase;

final class BootstrapVerificationTest extends TestCase
{
    public function testBootstrapReturnsApplicationInstance(): void
    {
        $app = require dirname(__DIR__) . '/bootstrap/app.php';

        $this->assertInstanceOf(Application::class, $app);
        $this->assertSame(dirname(__DIR__), $app->basePath());
    }
}
```

---

## Common Failures

### Class Not Found / Autoloading Errors
* **Symptom**: `Class "Tests\..." not found`.
* **Solution**: Ensure PSR-4 namespaces match the class structure. Run composer dump-autoload to refresh the cache:
  ```bash
  composer dump-autoload
  ```

### Missing Setup
* **Symptom**: `phpunit: command not found`.
* **Solution**: Install dependencies first before running tests:
  ```bash
  composer install
  ```

### Storage Folder Not Writable
* **Symptom**: Cache or log writing fails during testing.
* **Solution**: Make sure `storage/` directory and its subfolders are writable.
  ```bash
  mkdir -p storage/cache storage/logs storage/framework
  ```

---

## CI Note

A GitHub Actions workflow is defined in [.github/workflows/tests.yml](file:///d:/PHP%20PACKAGIST/intisari-starter/.github/workflows/tests.yml). It executes `composer test` on every push and pull request targeting the `main` branch across PHP versions 8.2 and 8.3.

---

## Next

Continue to the [Deployment Documentation](../deployment/index.md).
