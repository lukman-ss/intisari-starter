# Requirements

IntisariPHP Starter requires PHP, Composer, and basic terminal access.

The project declares PHP `>=8.2` in `composer.json`. Composer installs IntisariPHP core, development dependencies, and autoload configuration.

## Required Software

- PHP 8.2 or newer
- Composer
- Basic terminal or command prompt usage
- A local web server for development

## Recommended PHP Extensions

The exact extension requirements depend on installed IntisariPHP core features and your application code. For a typical local PHP setup, these extensions are recommended:

- `mbstring`
- `openssl`
- `pdo`
- `tokenizer`
- `json`

## Local Development Server

The starter includes a command line command that runs PHP's built-in development server with `public/` as the document root.

```bash
composer serve
```

This is enough for local development. Production deployment should use a real web server configured to serve the `public/` directory.

## Check Your Environment

```bash
php -v
composer -V
php -m
```

Use `php -m` to confirm that recommended extensions are enabled.

## Next Steps

Continue with [Installation](../installation/index.md).
