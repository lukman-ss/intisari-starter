# Requirements

IntisariPHP Starter requires PHP 8.2 or newer and Composer.

## Required Software

| Software | Minimum Version | Purpose |
|----------|----------------|---------|
| PHP | 8.2 | Runtime |
| Composer | 2.x | Dependency management |

## PHP Extensions

The IntisariPHP core depends on these extensions. Most are enabled by default in standard PHP installations.

| Extension | Purpose |
|-----------|---------|
| `mbstring` | Multibyte string handling for proper character encoding |
| `openssl` | Encryption and secure communication |
| `pdo` | Database access via PHP Data Objects |
| `tokenizer` | PHP code tokenization for route and command parsing |
| `json` | JSON encoding and decoding for API responses and configuration |

## Terminal Access

You will use the terminal (command line) throughout development. Common tasks include:

- Running Composer commands to install dependencies.
- Starting the development server with `composer serve`.
- Running tests with `composer test`.
- Generating controllers, middleware, and commands with `php intisari make:*`.

Make sure you can open a terminal and run PHP and Composer commands before continuing with the installation.

**Windows users:** PowerShell, Command Prompt, and Windows Terminal all work. The commands in this guide use Unix-style syntax (`cp`, `ls`). On Windows PowerShell, use `Copy-Item` instead of `cp`.

## Verify Your Setup

Run these commands to confirm your environment is ready:

```bash
php -v
```

This should print PHP 8.2 or newer:

```
PHP 8.2.x (cli) (built: ...)
```

```bash
composer -V
```

This should print Composer 2.x:

```
Composer version 2.x.x ...
```

```bash
php -m
```

This lists all enabled PHP extensions. Confirm that `mbstring`, `openssl`, `pdo`, `tokenizer`, and `json` appear in the output.

## Next

Continue to [Installation](../installation/index.md).
