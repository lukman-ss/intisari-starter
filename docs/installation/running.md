# Running the Application

The starter includes a local development server command.

The Composer script uses the `intisari` command line entry point:

```bash
php intisari serve
```

## Start the Server

```bash
composer serve
```

This starts the application at:

```text
http://127.0.0.1:8000
```

You can also run the command directly:

```bash
php intisari serve
```

You can set the host and port:

```bash
php intisari serve --host=0.0.0.0 --port=8080
```

## Run Tests

The starter includes a Composer test script:

```bash
composer test
```

This runs the PHPUnit setup included in the repository.

## Troubleshooting

### PHP Version Too Old

Check the installed PHP version:

```bash
php -v
```

IntisariPHP Starter requires PHP `>=8.2`.

### Composer Not Found

Check that Composer is installed and available in your terminal:

```bash
composer -V
```

### Port Already in Use

If port `8000` is already used by another process, start the server on another port:

```bash
php intisari serve --port=8080
```

Then open:

```text
http://127.0.0.1:8080
```

### Blank Page

A blank page usually means an error is hidden or the environment is misconfigured.

Check `.env`, confirm dependencies are installed, and run the server from the project root.

For local debugging, use:

```env
APP_ENV=local
APP_DEBUG=true
```

### Missing `.env`

If `.env` does not exist, copy it from the template:

```bash
cp .env.example .env
```

### Permission Issue

Permission issues usually mean the current user cannot read project files or write runtime files.

Check access to:

```text
storage/cache
storage/logs
storage/framework
```

### Invalid `.env`

Invalid or malformed `.env` values can prevent expected configuration from loading.

Check common values:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

If configuration has been cached, clear it after changing `.env`:

```bash
php intisari config:clear
```

## Next Steps

Continue with [Application Overview](../overview/index.md).
