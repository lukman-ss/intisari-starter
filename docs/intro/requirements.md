# Requirements

IntisariPHP Starter requires PHP 8.2 or newer and Composer.

## Required Software

| Software | Minimum Version | Purpose |
| --- | --- | --- |
| PHP | 8.2 | Application runtime |
| Composer | 2.x recommended | Dependency management |

## Recommended PHP Extensions

The following extensions are recommended for the starter, its database configuration, and development tooling:

| Extension | Purpose |
| --- | --- |
| `mbstring` | Multibyte string handling |
| `openssl` | Secure communication used by common PHP tooling |
| `pdo` | Database access |
| `tokenizer` | PHP token processing used by development tools |
| `json` | JSON encoding and decoding |

Extension requirements can vary by database driver and development dependency. Composer reports any missing extension required by the installed dependency set.

## Basic Terminal Usage

Development tasks are run from a terminal. You should be able to:

- Change directories with `cd`.
- Run PHP and Composer commands.
- Copy `.env.example` to `.env`.
- Start and stop a foreground development process.

PowerShell, Command Prompt, Windows Terminal, and common Unix shells can run the commands used by the starter. File-copy syntax may differ between platforms.

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

Confirm that PHP is at least version 8.2 and review the output for the recommended extensions.

## Next

Continue to [Installation](../installation/index.md).
