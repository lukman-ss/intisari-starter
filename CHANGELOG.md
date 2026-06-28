# Changelog

All notable changes to **IntisariPHP Starter** will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added

- Session configuration and documentation templates.
- Custom console command registration through `routes/console.php`.
- Intisari CLI commands for configuration, route listing, class generation, application information, and testing.
- Tests for generator commands, default routes, configuration, public directory boundaries, and CLI behavior.

### Changed

- Reduced README content to a project summary with links to detailed documentation.
- Reorganized documentation into introduction, installation, overview, basics, database, CLI, testing, deployment, security, and tutorial sections.
- Hardened `AGENTS.md` and `CONTRIBUTING.md` with repository boundaries and required verification commands.
- Updated `.github/workflows/tests.yml` to run `composer docs:check`.

### Fixed

- Removed unsupported feature claims and replaced them with verified or explicitly core-dependent behavior.
- Corrected internal documentation links, code fences, heading structure, terminology, and output escaping examples.
- Corrected documentation for config cache, middleware registration, debug behavior, and logging limitations.

### Documentation

- Added user guides for routing, controllers, views, middleware, configuration, error handling, logging, database access, CLI usage, and testing.
- Added tutorials for building a first application page and minimal JSON API endpoints.
- Added deployment, security, release-readiness, and application architecture guidance.

### Internal

- Added the `docs:check` Composer script.
- Hardened `scripts/check-docs.php` to check all repository Markdown files, relative links, H1 headings, code fences, empty blocks, and unsupported claims.

---

## [1.2.1] - 2026-06-28

### Added
- Added source integrity checker (`scripts/check-source-integrity.php`) to prevent codebase structure loss.

### Fixed
- Restored corrupted PHP source files, PHPUnit XML, GitHub Actions workflow YAML, and docs quality check scripts.
- Fixed route registration consistency in `routes/web.php`.
- Fixed security documentation output escaping examples in `docs/security/index.md`.
- Removed all local machine links and absolute paths from public documentation.
- Improved CI validation by integrating the source integrity check.

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
