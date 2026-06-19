# Security

Security is essential for any web application. This guide covers baseline security practices that every IntisariPHP application should follow.

## Environment Files

The `.env` file contains sensitive configuration values that must never be exposed publicly.

### Never Commit .env to Version Control

Add `.env` to your `.gitignore` file immediately:

```gitignore
.env
```

The `.env` file contains database passwords, API keys, and other secrets. Committing it to version control exposes these values to anyone with repository access.

### Use .env.example as a Template

Commit `.env.example` with safe default values. This tells other developers which environment variables are available:

```env
APP_NAME="Intisari App"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Create .env Manually for Each Environment

Every environment (local, staging, production) should have its own `.env` file created manually. Never copy a local `.env` to a production server.

### Never Store Secrets in Repository Files

Do not hardcode secrets in PHP files, configuration files, or any tracked file:

```php
// WRONG — secret in source code
$password = 'my_database_password';
$apiKey = 'sk_live_abc123';

// CORRECT — secret in .env
$password = $_ENV['DB_PASSWORD'] ?? '';
$apiKey = $_ENV['API_KEY'] ?? '';
```

## Debug Mode

Debug mode is one of the most dangerous settings in a web application.

### Enable Only in Local Development

```env
APP_ENV=local
APP_DEBUG=true
```

During development, debug mode shows detailed error messages, stack traces, and environment variables. This helps you find and fix bugs quickly.

### Disable in Production

```env
APP_ENV=production
APP_DEBUG=false
```

**This is the most critical security setting.** Debug mode in production exposes:

- Full file paths on the server
- Stack traces revealing application structure
- Environment variables (including database passwords)
- SQL queries and database schema
- Implementation details that aid attackers

### Verify Before Deployment

Always verify debug mode is disabled before deploying:

```bash
grep APP_DEBUG .env
# Must show: APP_DEBUG=false
```

## Public Directory

The web server document root controls which files are accessible via HTTP.

### Document Root Must Be public/

```text
/path/to/app/public    ← CORRECT
/path/to/app           ← WRONG — critical security risk
```

### Why This Matters

If the document root points to the project root, these files become web-accessible:

- `.env` — contains database passwords and secrets
- `config/` — reveals application configuration
- `storage/logs/` — contains error details and potentially user data
- `composer.json` — reveals dependency versions (aids targeted attacks)
- `vendor/` — contains all installed packages
- `tests/` — reveals testing patterns and possibly business logic

### Verify Document Root

Check your web server configuration:

```nginx
# Nginx
root /var/www/my-app/public;    # Correct
root /var/www/my-app;           # WRONG
```

```apache
# Apache
DocumentRoot /var/www/my-app/public    # Correct
DocumentRoot /var/www/my-app           # WRONG
```

### Block Hidden Files

Configure your web server to deny access to hidden files (`.env`, `.git`, `.htaccess`):

```nginx
location ~ /\.(?!well-known).* {
    deny all;
}
```

## Input Validation

All user input must be validated before use. Unvalidated input is the root cause of most security vulnerabilities.

### Validate Everything

Validate all sources of user input:

- Query string parameters (`$_GET`)
- Form data (`$_POST`)
- JSON request bodies
- HTTP headers
- Uploaded files
- Cookies

### Use Type Checking and Filtering

```php
// Validate integer
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    return 'Invalid ID';
}

// Validate email
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if ($email === false || $email === null) {
    return 'Invalid email address';
}

// Sanitize string
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
```

### Validate Against Expected Values

```php
$allowedSortFields = ['name', 'email', 'created_at'];
$sortField = $_GET['sort'] ?? 'name';

if (!in_array($sortField, $allowedSortFields, true)) {
    $sortField = 'name';  // Default to safe value
}
```

### Validation Package

The IntisariPHP core may provide a validation package with additional validation rules and helpers. Check your installed packages for available validation tools.

## Output Escaping

All dynamic output must be escaped to prevent Cross-Site Scripting (XSS) attacks.

### Escape in Views

Every value that comes from user input, a database, or an external source must be escaped before rendering:

```php
<h1><?= htmlspecialchars($title ?? 'Untitled', ENT_QUOTES, 'UTF-8') ?></h1>
<p><?= htmlspecialchars($description ?? '', ENT_QUOTES, 'UTF-8') ?></p>
```

### Using the $e() Helper

If the `$e()` helper is available from IntisariPHP core:

```php
<h1><?= $e($title ?? 'Untitled') ?></h1>
<p><?= $e($description ?? '') ?></p>
```

### Never Output Raw User Input

```php
<!-- WRONG — XSS vulnerability -->
<p>Hello, <?= $_GET['name'] ?></p>

<!-- CORRECT — escaped output -->
<p>Hello, <?= htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
```

### Escape Context Matters

Different contexts require different escaping:

- **HTML body** — use `htmlspecialchars()` or `$e()`
- **HTML attributes** — use `htmlspecialchars()` with `ENT_QUOTES`
- **JavaScript** — use `json_encode()` for safe embedding
- **URLs** — use `urlencode()` for query parameters

## Database Credentials

Database credentials are among the most sensitive values in your application.

### Store in .env, Not Source Code

```env
DB_CONNECTION=mysql
DB_DATABASE=your_database
DB_USERNAME=app_user
DB_PASSWORD=strong_secure_password
```

Read credentials from environment in config files:

```php
// config/database.php
'username' => $env('DB_USERNAME', 'root'),
'password' => $env('DB_PASSWORD', ''),
```

### Use Different Credentials Per Environment

Each environment should have its own database user with minimal required privileges:

- **Local** — can use `root` with full access
- **Production** — dedicated user with access only to the application database
- **Testing** — separate database or user to avoid affecting production data

### Use Strong Passwords

Generate strong database passwords (20+ characters, mixed case, numbers, symbols):

```bash
# Generate a random password
openssl rand -base64 32
```

### Restrict Database User Privileges

The production database user should only have access to the application database:

```sql
-- MySQL example
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON your_database.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
```

## Dependency Updates

Outdated dependencies may contain known security vulnerabilities.

### Check for Outdated Packages

```bash
composer outdated
```

### Review Before Updating

Read the changelog for breaking changes before updating in production:

```bash
composer update --dry-run
```

### Apply Security Updates Promptly

Monitor your dependencies for security advisories:

- [PHP Security Advisories](https://github.com/FriendsOfPHP/security-advisories)
- GitHub security alerts on your repository

### Update and Test

```bash
composer update
composer test
```

Run your test suite after every update to catch breaking changes.

### Lock Dependencies in Production

Use `composer.lock` to ensure consistent dependency versions:

```bash
composer install  # Uses composer.lock, not composer.json
```

## File Permissions

Incorrect file permissions can allow unauthorized access or modification.

### Only storage/ Should Be Writable

```text
storage/cache        ← Writable (web server user)
storage/logs         ← Writable (web server user)
storage/framework    ← Writable (web server user)
```

### Everything Else Should Be Read-Only

```text
app/                 ← Read-only
config/              ← Read-only
routes/              ← Read-only
public/              ← Read-only
vendor/              ← Read-only
```

### Set Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data storage/

# Set permissions (writable by owner only)
sudo chmod -R 775 storage/
```

### Never Make the Entire Project Writable

Making the entire project writable allows attackers to modify source code, configuration, and dependencies.

## Features Not Included

The IntisariPHP Starter does not include built-in security features for:

- **CSRF protection** — depends on IntisariPHP core HTTP package
- **Authentication** — depends on IntisariPHP core or third-party packages
- **Role-based permissions** — depends on third-party packages
- **Session security hardening** — depends on IntisariPHP core session package
- **Rate limiting** — depends on IntisariPHP core or third-party packages
- **Password hashing utilities** — use PHP's built-in `password_hash()` and `password_verify()`

If these features are available through the IntisariPHP core or installed packages, they will be documented in their respective sections.

## Security Checklist

Use this checklist before deploying to production:

- [ ] `.env` is not committed to version control
- [ ] `APP_DEBUG=false` in production
- [ ] Document root points to `public/`
- [ ] Hidden files (`.env`, `.git`) are blocked by web server
- [ ] All user input is validated
- [ ] All dynamic output is escaped
- [ ] Database credentials are in `.env`, not source code
- [ ] Production database user has minimal privileges
- [ ] Dependencies are up to date
- [ ] `storage/` is writable, everything else is read-only
- [ ] HTTPS is enabled
- [ ] `composer.lock` is committed

## Next

Continue to [Build Your First App](../tutorials/build-your-first-app.md).
