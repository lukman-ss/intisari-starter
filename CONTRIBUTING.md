# Contributing to IntisariPHP Starter

Thank you for your interest in contributing to **IntisariPHP Starter**! This guide outlines the setup, style conventions, and guidelines for adding code, tests, documentation, and console commands.

---

## 1. Project Purpose

IntisariPHP Starter is a lightweight application skeleton designed to be a clean, minimal, and secure starting point for IntisariPHP applications. It provides the directories, configurations, routing examples, front controller, and test suites.

---

## 2. Local Setup & Coding Style

### Local Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/lukman-ss/intisari-starter.git
   cd intisari-starter
   ```
2. Install local development dependencies:
   ```bash
   composer install
   ```
3. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```
4. Start the local server:
   ```bash
   composer serve
   ```

### Coding Style
* Follow standard PSR-12 coding guidelines.
* Ensure code, variable names, documentation, and comments are written in standard English.
* Declare `strict_types=1` at the beginning of all PHP files.

---

## 3. Core Development & CLI Commands

### CLI Command Rule
* All command-line interface utilities must be registered via [routes/console.php](routes/console.php).
* Avoid creating complex sub-commands or third-party console dependencies. Keep commands straightforward and direct.

#### How to Add Console Commands
1. Define your command logic inside a class or closure.
2. Register the command in [routes/console.php](routes/console.php):
   ```php
   $app->command('app:my-command', function ($input, $output) {
       $output->writeln('My custom command ran successfully!');
       return 0;
   });
   ```

---

## 4. Running & Adding Tests

### Test Rule
* Never push code without verifying that all existing tests pass.
* Write focused unit or feature tests for any new features or configurations.

### Running Tests
Run the test suite using PHPUnit:
```bash
composer test
```

### How to Add Tests
1. Create a new test case file under the `tests/` directory (e.g. `tests/Feature/MyNewTest.php`).
2. Extend PHPUnit's TestCase and write assertion methods:
   ```php
   <?php

   declare(strict_types=1);

   namespace Tests\Feature;

   use PHPUnit\Framework\TestCase;

   final class MyNewTest extends TestCase
   {
       public function testExampleFeature(): void
       {
           $this->assertTrue(true);
       }
   }
   ```

---

## 5. Documentation Rules

### Documentation & Feature Claim Rules
* **No Unsupported Claims**: Do not claim or document features that are not natively supported by the starter or the core dependency (`lukman-ss/intisari`). Examples must use verified minimal alternatives.
* **No Unsecured Examples**: All example code must demonstrate output escaping and secure input validation. Always warn against exposing the project root and instruct pointing the web server to `public/`.
* **Standard Naming**: Refer to the starter as **IntisariPHP Starter** and the framework core as **IntisariPHP core**.

### How to Add Docs
1. Create or update your Markdown file inside `docs/` or its subdirectories.
2. Link the new page inside [docs/index.md](docs/index.md).
3. Run the documentation linter to verify formatting, headings, and link integrity:
   ```bash
   composer docs:check
   ```

---

## 6. Pull Request Checklist

Before submitting a Pull Request, ensure that:
- [ ] `composer validate --strict` passes.
- [ ] `composer test` passes without any failures.
- [ ] `composer docs:check` passes with no H1 or link issues.
- [ ] Code follows PSR-12 and uses `declare(strict_types=1)`.
- [ ] No database/auth/middleware or complex dependencies are added unless requested.
- [ ] Any configuration options in code are represented in `.env.example`.
