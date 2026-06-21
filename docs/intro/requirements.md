# Requirements

IntisariPHP Starter requires PHP 8.2 or newer, Composer 2.x, and basic terminal access.

## Required Software

| Software | Minimum Version | Purpose |
| --- | --- | --- |
| PHP | 8.2 | Application runtime |
| Composer | 2.x | Dependency management |

## Terminal Usage

Development commands run in a terminal. You should be able to change directories, run PHP and Composer, copy `.env.example` to `.env`, and stop a foreground development server. Command syntax for copying files can differ between operating systems.

## Recommended PHP Extensions

- `mbstring` for multibyte string handling.
- `openssl` for secure communication used by common PHP tooling.
- `pdo` for database access.
- `tokenizer` for PHP token processing used by development tools.
- `json` for JSON encoding and decoding.

Database drivers and development dependencies may require additional extensions. Composer reports missing extensions required by the installed dependency set.

## Verify the Environment

Check the PHP version:

```bash
php -v
```

Check the Composer version:

```bash
composer -V
```

List enabled PHP extensions:

```bash
php -m
```

Confirm PHP is at least version 8.2, Composer is version 2.x, and the recommended extensions are listed.

## Next

Continue to [Installation](../installation/index.md).
