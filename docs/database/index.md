# Database

IntisariPHP Starter configures database connections through environment variables in `.env` and the configuration file `config/database.php`. The starter supports SQLite, MySQL, and PostgreSQL via PHP's PDO extension.

## Database Configuration

Database settings are stored in `.env` and read by `config/database.php` at runtime.

```php
// config/database.php (excerpt)
return [
    'default' => $env('DB_CONNECTION', 'sqlite'),
    'connections' => [
        'sqlite' => [...],
        'mysql' => [...],
        'pgsql' => [...],
    ],
];
```

The `DB_CONNECTION` variable determines which connection is used by default.

## SQLite for Local Development

SQLite is the default database driver. It stores data in a local file, making it ideal for development and testing without needing a separate database server.

### Configuration

Set these values in `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Creating the SQLite File

Before using SQLite, create the database file:

**macOS / Linux:**

```bash
mkdir -p database
touch database/database.sqlite
```

**Windows PowerShell:**

```powershell
New-Item -ItemType Directory -Force -Path database
New-Item -ItemType File -Force -Path database/database.sqlite
```

**Windows Command Prompt:**

```cmd
mkdir database
echo. > database\database.sqlite
```

### Advantages of SQLite

- No database server required
- Database is a single file, easy to back up
- Ideal for prototyping and small applications
- Included with the starter by default

## MySQL Configuration

For applications that need a full-featured relational database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=intisari
DB_USERNAME=root
DB_PASSWORD=your_secure_password
DB_CHARSET=utf8mb4
```

These variables map to the connection settings in `config/database.php`:

```php
'mysql' => [
    'driver' => 'mysql',
    'host' => $env('DB_HOST', '127.0.0.1'),
    'port' => (int) $env('DB_PORT', 3306),
    'database' => $env('DB_DATABASE', 'intisari'),
    'username' => $env('DB_USERNAME', 'root'),
    'password' => $env('DB_PASSWORD', ''),
    'charset' => $env('DB_CHARSET', 'utf8mb4'),
],
```

Ensure the `pdo_mysql` PHP extension is enabled:

```bash
php -m | grep pdo_mysql
```

## PostgreSQL Configuration

For applications using PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=intisari
DB_USERNAME=postgres
DB_PASSWORD=your_secure_password
DB_CHARSET=utf8
```

These variables map to the connection settings in `config/database.php`:

```php
'pgsql' => [
    'driver' => 'pgsql',
    'host' => $env('DB_HOST', '127.0.0.1'),
    'port' => (int) $env('DB_PORT', 5432),
    'database' => $env('DB_DATABASE', 'intisari'),
    'username' => $env('DB_USERNAME', 'postgres'),
    'password' => $env('DB_PASSWORD', ''),
    'charset' => $env('DB_CHARSET', 'utf8'),
],
```

Ensure the `pdo_pgsql` PHP extension is enabled:

```bash
php -m | grep pdo_pgsql
```

## Running Queries

The IntisariPHP core database package provides database connectivity through PDO. How you execute queries depends on the features available in the installed IntisariPHP core database package.

### Using PDO Directly

If no query builder is available, you can use PDO directly:

```php
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Select
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Insert
$stmt = $pdo->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
$stmt->execute(['Alice', 'alice@example.com']);
```

The IntisariPHP core database package may provide a query builder or database manager on top of PDO. Refer to the IntisariPHP core documentation for available query methods.

## Migrations

The starter does not include migration files or a migration command. Migration support depends on the IntisariPHP database package.

If migration support is available from the core, you would typically:

1. Define table schemas in migration files
2. Run a command to apply migrations
3. Roll back migrations when needed

If migration commands are not available, manage your database schema manually using SQL or a database GUI tool.

## Environment Variables Summary

| Variable | Purpose | SQLite Default | MySQL Default | PostgreSQL Default |
|----------|---------|:---:|:---:|:---:|
| `DB_CONNECTION` | Default driver | `sqlite` | `mysql` | `pgsql` |
| `DB_HOST` | Database host | — | `127.0.0.1` | `127.0.0.1` |
| `DB_PORT` | Database port | — | `3306` | `5432` |
| `DB_DATABASE` | Database name/path | `database/database.sqlite` | `intisari` | `intisari` |
| `DB_USERNAME` | Database user | — | `root` | `postgres` |
| `DB_PASSWORD` | Database password | — | (empty) | (empty) |
| `DB_CHARSET` | Character set | — | `utf8mb4` | `utf8` |

## Rules

- **Never commit database files** — add `database/*.sqlite` to `.gitignore`
- **Never store credentials in source code** — use `.env` for all database passwords
- **Use different databases** — separate databases for development, testing, and production
- **Back up production data** — database files are not tracked in version control

## Next

Continue to [Command Line Usage](../cli/index.md).
