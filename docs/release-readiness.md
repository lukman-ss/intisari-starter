# Release Readiness Checklist

Complete this checklist before tagging a new IntisariPHP Starter release. This page does not create or publish a release.

## Composer and Test Checks

- [ ] Validate Composer metadata:

  ```bash
  composer validate --strict
  ```

- [ ] Install the versions recorded in `composer.lock`:

  ```bash
  composer install
  ```

- [ ] Regenerate Composer autoload files:

  ```bash
  composer dump-autoload
  ```

- [ ] Run the complete test suite:

  ```bash
  composer test
  ```

- [ ] Run the Markdown quality checker:

  ```bash
  composer docs:check
  ```

## Intisari CLI Checks

- [ ] Confirm application information renders:

  ```bash
  php intisari about
  ```

- [ ] Confirm the expected environment renders:

  ```bash
  php intisari env
  ```

- [ ] Confirm `/`, `/health`, and `/status` appear in the route list:

  ```bash
  php intisari route:list
  ```

- [ ] Confirm both config commands complete and leave no stale cache:

  ```bash
  php intisari config:cache
  php intisari config:clear
  ```

  The current bootstrap does not load the generated cache file; this check verifies command behavior only.

## Generator Smoke Tests

- [ ] Run `make:controller`, `make:middleware`, `make:provider`, and `make:command` in a disposable project copy or with unique temporary class names.
- [ ] Confirm each generated path, namespace, class name, and PHP syntax.
- [ ] Confirm existing files are preserved without `--force`.
- [ ] Confirm `--force` replaces files only when explicitly supplied.
- [ ] Remove every generated smoke-test file and empty generated directory.

Automated generator coverage should also pass through `composer test`.

## Documentation Checks

- [ ] Verify every relative link in [`README.md`](../README.md).
- [ ] Verify every documentation link and `Next` link; `composer docs:check` must pass.
- [ ] Confirm [`docs/index.md`](index.md) links to every main documentation page.
- [ ] Confirm route examples match [`routes/web.php`](../routes/web.php).
- [ ] Confirm CLI commands and options match [`routes/console.php`](../routes/console.php).
- [ ] Confirm environment variables in [`.env.example`](../.env.example) match `config/*.php` usage and contain safe defaults.
- [ ] Confirm no unsupported feature is presented as available; uncertain runtime behavior must be marked core-dependent or planned.
- [ ] Confirm security examples render correctly and use valid escaping, input validation, and secret-handling patterns.
- [ ] Confirm deployment and security docs state that `public/` is the document root and the project root must remain private.

## Release Metadata

- [ ] Update [`CHANGELOG.md`](../CHANGELOG.md) with factual Unreleased entries for the release scope.
- [ ] Confirm the intended version follows semantic versioning.
- [ ] Use the recommended tag format `vMAJOR.MINOR.PATCH`, such as `v1.2.3`.
- [ ] Review the final diff and working tree before creating the tag outside this checklist.

## Next

Return to the [Documentation Index](index.md).
