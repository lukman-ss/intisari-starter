# Running the Application

IntisariPHP Starter uses PHP's built-in server for local development.

## Start the Development Server

Use the Composer script:

```bash
composer serve
```

Or run the project CLI directly:

```bash
php intisari serve
```

Both commands serve the application at `http://127.0.0.1:8000` by default.

## Custom Host and Port

The `serve` command supports `--host` and `--port`:

```bash
php intisari serve --host=127.0.0.1 --port=8080
```

Open `http://127.0.0.1:8080` for this example. Use only a valid host and a port from `1` through `65535`.

## Stop the Server

Press `Ctrl+C` in the terminal running the server.

## Run Tests

After stopping the server, or from another terminal, run:

```bash
composer test
```

The built-in development server is not intended for production. See [Deployment](../deployment/index.md) for deployment guidance.

## Next

Continue to [Installation Troubleshooting](troubleshooting.md).
