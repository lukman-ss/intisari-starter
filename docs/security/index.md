# Security

Security is essential for any web application. This guide covers baseline security practices that every IntisariPHP application must follow to remain secure in production.

## Environment Files

The `.env` file contains sensitive configuration values that must never be exposed publicly.

### Never Commit `.env` to Version Control
Add `.env` to your [**.gitignore**](file:///d:/PHP%20PACKAGIST/intisari-starter/.gitignore) file immediately:

```gitignore
.env
```

The `.env` file contains database passwords, API keys, and other secrets. Committing it to version control exposes these values to anyone with repository access.

### Use `.env.example` as a Template
Commit [**.env.example**](file:///d:/PHP%20PACKAGIST/intisari-starter/.env.example) with safe default values. This tells other developers which environment variables are available:

```env
APP_NAME="Intisari App"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Create `.env` Manually for Each Environment
Every environment (local, staging, production) must have its own `.env` file created manually. Never copy a local `.env` to a production server.

### Never Store Secrets in Repository Files
Do not hardcode secrets in PHP files, configuration files, or any tracked files:

```php
// WRONG — Secret in source code
$password = 'my_database_password';
$apiKey = 'sk_live_abc123';

// CORRECT — Secret in environment variable
$password = $_ENV['DB_PASSWORD'] ?? '';
$apiKey = $_ENV['API_KEY'] ?? '';
```

---

## Debug Mode

Debug mode is a critical setting that must be tightly controlled.

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

---

## Public Directory

The web server document root controls which files are accessible via HTTP.

### Document Root Must Be `public/`
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
- `tests/` — reveals testing patterns and business logic

### Verify Document Root
Check your web server configuration to verify it points to `public/`:

```nginx
# Nginx Configuration
root /var/www/my-app/public;    # Correct
root /var/www/my-app;           # WRONG
```

```text
# Apache Configuration
DocumentRoot /var/www/my-app/public    # Correct
DocumentRoot /var/www/my-app           # WRONG
```

---

## Hidden Files

Ensure that your web server blocks access to hidden files (`.env`, `.git`, `.htaccess`):

```nginx
# Nginx block rules
location ~ /\.(?!well-known).* {
    deny all;
}
```

---

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
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
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
The IntisariPHP core provides a validator factory. You can run validations inside your controllers like this:
```php
$validatedData = $app->validate($request->all(), [
    'email' => 'required|email',
    'password' => 'required|min:8',
]);
```

---

## Output Escaping

All dynamic output must be escaped to prevent Cross-Site Scripting (XSS) attacks.

### Raw vs Escaped Output Examples

```php
// WRONG: Vulnerable to XSS injection
<p>Welcome, <?= $username ?></p>
<input type="text" name="email" value="<?= $email ?>">

// CORRECT: Escaped using htmlspecialchars()
<p>Welcome, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></p>
<input type="text" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
```

### Using the `$e()` Closure Helper
Within the starter's template files (which are evaluated by `Lukman\View\PhpEngine`), a local `$e()` closure is automatically injected as a shortcut to escape values:

```php
// CORRECT: Escaped using the local view $e() helper
<p>Welcome, <?= $e($username) ?></p>
<input type="text" name="email" value="<?= $e($email) ?>">
```

### Escape Context Matters
Different contexts require different escaping strategies:
* **HTML Body**: Use `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` or the local template `$e($value)` helper.
* **HTML Attributes**: Use `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` to ensure double and single quotes are escaped.
* **JavaScript**: Use `json_encode($value)` to safely embed variables.
* **URLs**: Use `urlencode($value)` for query string parameters.

---

## Database Credentials

Database credentials are highly sensitive.

### Store in `.env`, Not Source Code
```env
DB_CONNECTION=mysql
DB_DATABASE=your_database
DB_USERNAME=app_user
DB_PASSWORD=strong_secure_password
```

Read credentials from the environment in [config/database.php](file:///d:/PHP%20PACKAGIST/intisari-starter/config/database.php):
```php
'username' => $env('DB_USERNAME', 'root'),
'password' => $env('DB_PASSWORD', ''),
```

### Use Different Credentials Per Environment
Each environment must have its own database user with minimal required privileges.
* **Local**: Can use `root` with full access.
* **Production**: A dedicated database user with access limited only to the application database.
* **Testing**: A separate testing database (e.g. SQLite in-memory or a dedicated SQLite file) to isolate testing operations.

---

## Dependency Updates

Outdated dependencies may contain known security vulnerabilities.

### Check for Outdated Packages
```bash
composer outdated
```

### Monitor and Apply Security Updates
Regularly audit your dependencies for security vulnerabilities:
```bash
composer audit
```

### Run Tests After Updating
Always run your PHPUnit tests after updates to verify no regression:
```bash
composer update
composer test
```

---

## File Permissions

Incorrect file permissions can allow unauthorized modification of your codebase.

### Only `storage/` Should Be Writable
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

Set permissions:
```bash
# Set ownership
sudo chown -R www-data:www-data storage/

# Set permissions
sudo chmod -R 775 storage/
```

---

## Features Not Included

The IntisariPHP Starter does **not** package built-in components for:
* **Authentication & Authorization** (e.g. login, RBAC)
* **CSRF (Cross-Site Request Forgery) protection**
* **Encryption and Hashing utilities** (use PHP's native `password_hash()` and `password_verify()`)
* **Rate Limiter**

> [!NOTE]
> *Future or core-dependent integration*: If authentication, authorization, or rate limiting are required, they must be manually integrated using third-party composer libraries (e.g., Firebase JWT, PHP-JWT) or handled at the web server layer (e.g. Nginx rate limiting rules).

---

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
- [ ] Dependencies are up to date (`composer audit` passes)
- [ ] `storage/` is writable, everything else is read-only
- [ ] HTTPS is enabled
- [ ] `composer.lock` is committed

---

## Next

Continue to the [Build Your First App Tutorial](../tutorials/build-your-first-app.md).
