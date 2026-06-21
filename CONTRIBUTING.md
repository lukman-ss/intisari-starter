# Contributing to IntisariPHP Starter

## Project Purpose

IntisariPHP Starter is the lightweight application template for IntisariPHP. It contains application structure, configuration, routes, views, CLI commands, documentation, and tests. Reusable framework behavior belongs in IntisariPHP core, not this repository.

## Local Setup

```bash
git clone https://github.com/lukman-ss/intisari-starter.git
cd intisari-starter
composer install
cp .env.example .env
```

On Windows PowerShell, copy the environment file with:

```powershell
Copy-Item .env.example .env
```

Start the local server with `composer serve` when needed.

## Coding Style

- Follow the existing PSR-12-compatible project style.
- Add `declare(strict_types=1);` to PHP source files.
- Use English for identifiers, filenames, comments, documentation, and examples.
- Prefer focused changes and existing dependencies.
- Do not edit `vendor/`.

## Running Tests

```bash
composer test
```

Run the complete suite before submitting changes.

## Running the Docs Check

```bash
composer docs:check
```

This checks Markdown headings, links, code fences, empty blocks, and unsupported claims.

## Documentation Rules

- Use exactly one H1 per Markdown file.
- Add a language identifier to every code fence.
- Use relative internal links and verify every target.
- Keep examples copy-pasteable and consistent with the current source.
- Add new documentation pages to `docs/index.md`.
- Use `IntisariPHP Starter`, `IntisariPHP core`, and `Intisari CLI` consistently.

## Feature Claim Rules

- Verify features against starter source and installed packages before documenting them.
- Mark uncertain package behavior as core-dependent or planned.
- Do not present absent commands, middleware, persistence, security, or background-processing features as available.
- Use a verified minimal alternative when a convenience API is unavailable.

## CLI Command Rules

- Register application commands in [`routes/console.php`](routes/console.php).
- Document only registered commands, arguments, and options.
- Keep command handlers small and return an integer exit code.
- A generated command class is not active until it is registered.

## Environment and Configuration Rules

- Treat `.env.example` and `config/*.php` as the source of truth.
- Add safe placeholders to `.env.example` when a config file reads a new environment variable.
- Never commit `.env` or real credentials.
- Document only variables consumed by configuration source.

## How to Add Console Commands

Add a closure command to `routes/console.php`:

```php
$app->command('report:status', static function ($input, $output): int {
    $output->writeln('Report ready');

    return 0;
}, 'Display report status');
```

Run `php intisari` to confirm registration, then add focused command tests.

## How to Add Documentation

1. Create or update an English Markdown file under `docs/`.
2. Link a new page from `docs/index.md` and from a relevant `Next` section.
3. Run `composer docs:check`.

## How to Add Tests

- Put isolated tests under `tests/Unit/`.
- Put generator or application-flow tests under `tests/Feature/`.
- Use root-level `tests/*.php` for repository and application contract checks, matching `phpunit.xml`.
- Extend `Tests\TestCase` when the application bootstrap or web routes are required.
- Keep generated files in a temporary path and clean them after the test.

## Pull Request Checklist

- [ ] The change is focused and does not edit `vendor/`.
- [ ] Code and documentation use English and existing naming conventions.
- [ ] Route examples match `routes/web.php`.
- [ ] CLI documentation matches `routes/console.php`.
- [ ] Environment documentation matches `.env.example` and `config/*.php`.
- [ ] New behavior has appropriate tests.
- [ ] `composer validate --strict` passes.
- [ ] `composer test` passes.
- [ ] `composer docs:check` passes.
