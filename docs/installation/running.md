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

## Troubleshooting

### Port already in use

If port 8000 is occupied, start the server on a different port:

```bash
php intisari serve --port=8080
```

Or find and stop the process using port 8000:

**macOS/Linux:**

```bash
lsof -i :8000
kill -9 <PID>
```

**Windows PowerShell:**

```powershell
Get-NetTCPConnection -LocalPort 8000 | Select-Object OwningProcess
Stop-Process -Id <PID>
```

### Missing `.env`

If the application fails to load configuration, copy the environment template:

```bash
cp .env.example .env
```

Or on Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### Blank page

If you see a blank page, enable debug mode to see error details:

```env
APP_ENV=local
APP_DEBUG=true
```

Then check the terminal output for error messages.

### Wrong APP_URL

If links or redirects are not working, ensure `APP_URL` matches your actual server address:

```env
APP_URL=http://127.0.0.1:8000
```

Do not use `localhost` if your server is bound to `127.0.0.1`, or vice versa.

### Permission issues

The application needs write access to these directories:

- `storage/cache` — configuration and route cache files
- `storage/logs` — application log files
- `storage/framework` — session and view cache files

Create these directories if they don't exist:

```bash
mkdir -p storage/cache storage/logs storage/framework
```

On Windows PowerShell:

```powershell
New-Item -ItemType Directory -Force -Path storage/cache, storage/logs, storage/framework
```

### Database connection error

If you see a database error, ensure SQLite is configured:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

And create the database file:

```bash
touch database/database.sqlite
```

Or on Windows PowerShell:

```powershell
New-Item -ItemType File -Force -Path database/database.sqlite
```

## Production Deployment

The development server is not suitable for production. For production deployment, use a proper web server such as Nginx or Apache with PHP-FPM.

See [Deployment](../deployment/index.md) for production configuration guidance.

## Next

Continue to [Application Overview](../overview/index.md).
