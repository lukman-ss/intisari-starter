# Running the Application

The starter includes a development server command for local work.

The Composer script uses the `intisari` command line entry point:

```bash
php intisari serve
```

## Start the Server

```bash
composer serve
```

Or run the console command directly:

```bash
php intisari serve
```

You can set the host and port:

```bash
php intisari serve --host=0.0.0.0 --port=8080
```

By default, the application is available at:

```text
http://127.0.0.1:8000
```

## Run Tests

The starter includes a Composer test script:

```bash
composer test
```

This runs the PHPUnit setup included in the repository.

## Troubleshooting

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

### `APP_DEBUG`

For local development, `APP_DEBUG=true` helps show useful error details.

```env
APP_DEBUG=true
```

Do not use debug mode for production deployments.

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
