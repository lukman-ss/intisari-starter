# Changelog

All notable changes to **IntisariPHP Starter** will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added
* Session configuration and documentation templates.
* Support for custom console commands registered via `routes/console.php`.
* Intisari CLI utility with management commands (`config:cache`, `config:clear`, `route:list`, and code generation commands).
* Script `scripts/check-docs.php` to perform validation on documentation formatting, header counts, and link integrity.
* Feature and unit tests for routes, config caching, public directory boundaries, and CLI commands.

### Changed
* Updated GitHub Actions workflow `.github/workflows/tests.yml` to run the documentation quality check (`composer docs:check`).
* Added `docs:check` script execution shortcut under `composer.json`.

### Fixed
* Fixed false positive H1 counts and empty code fence reports in `check-docs.php` by ignoring lines inside code fences.

### Documentation
* Created comprehensive guides under `docs/` covering Routing, Controllers, Views, Middleware, Configuration, Error Handling, and Logging.
* Added beginner-friendly tutorials for creating a first app and building a REST API.
* Added deployment guidelines and security checklists warning against exposing the project root.
* Created a release readiness checklist (`docs/release-readiness.md`).
* Created a contributor guide (`CONTRIBUTING.md`).

---

## [1.0.1] - 2026-06-17

### Added
* Base storage directory configurations (cache, logs, framework).
* Controller status templates and endpoint tests.
* Basic GitHub Actions testing workflow.

---

## [1.0.0] - 2026-06-17

### Added
* Initial project skeleton setup.
