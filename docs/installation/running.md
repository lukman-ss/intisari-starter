# Running the Application

IntisariPHP Starter includes a built-in development server and test runner. Both are accessible through Composer scripts.

## Start the Development Server

```bash
composer serve
```

This command runs `php intisari serve`, which starts PHP's built-in web server.

The application will be available at:

```text
http://127.0.0.1:8000
```

Open this URL in your browser. You should see the IntisariPHP welcome page.

### Custom Host and Port

To run the server on a different host or port:

```bash
php intisari serve --host=0.0.0.0 --port=8080
```

Useful scenarios:

- `--host=0.0.0.0` — make the server accessible from other devices on your network
- `--port=8080` — avoid conflicts when port 8000 is already in use

### Stopping the Server

Press `Ctrl + C` in the terminal to stop the development server.

## Run Tests

```bash
composer test
```

This command runs the PHPUnit test suite defined in `phpunit.xml`.

Expected output:

```text
PHPUnit 10.x by Sebastian Bergmann and contributors.

OK (X tests, Y assertions)
```

The test suite includes:

- Unit tests for application logic
- Feature tests for HTTP endpoints
- Example tests to demonstrate testing patterns

### Run Specific Tests

To run a single test file:

```bash
vendor/bin/phpunit tests/Feature/ExampleTest.php
```

To run tests matching a filter:

```bash
vendor/bin/phpunit --filter testExample
```

## Environment Configuration

The application reads configuration from your `.env` file. Common settings:

```env
APP_NAME=IntisariPHP
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
```

If you change `APP_URL`, ensure it matches the actual URL where your application is running.

## Production Deployment

The development server is not suitable for production. For production deployment, use a proper web server such as Nginx or Apache with PHP-FPM.

See [Deployment](../deployment/index.md) for production configuration guidance.

## Next

Continue to [Installation Troubleshooting](troubleshooting.md).
