# Database

## Database Overview

IntisariPHP Starter depends on `lukman-ss/intisari`, which integrates the installed `lukman-ss/database` package. `config/database.php` defines SQLite, MySQL, and PostgreSQL connections.

The database package uses PDO. Install the PDO driver required by the selected connection.

The starter does not include an ORM or model persistence layer.

## SQLite Default

SQLite is the default local connection:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

It does not use `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`, or `DB_CHARSET`.

## Creating `database/database.sqlite`

The `database/` directory is included in the starter. Create the SQLite file from the project root:

```bash
touch database/database.sqlite
```

On Windows PowerShell:

```powershell
New-Item -ItemType File -Force -Path database/database.sqlite
```

Confirm that the PHP `pdo_sqlite` extension is enabled before connecting.

## MySQL Configuration

Set the MySQL connection values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=intisari
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

The selected database must exist and the configured user must have the permissions required by the application. Enable the PHP `pdo_mysql` extension.

## PostgreSQL Configuration

Set PostgreSQL values appropriate for the server:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=intisari
DB_USERNAME=postgres
DB_PASSWORD=
DB_CHARSET=utf8
```

Enable the PHP `pdo_pgsql` extension.

## Database Credentials and `.env`

Store server database credentials in `.env`, not in `config/database.php` or committed documentation.

- Commit `.env.example` with safe example values.
- Do not commit `.env`.
- Use a dedicated production database user with only required privileges.
- Use different credentials for local, testing, and production environments.

## Running Queries

The installed core exposes `Application::db()`, which returns a `Lukman\Database\Connection`. This is core/database-package-dependent behavior rather than code implemented by the starter.

The installed connection supports parameterized `select`, `selectOne`, `statement`, and `affectingStatement` methods.

Example using an application instance:

```php
$users = $app->db()->select(
    'SELECT id, name FROM users WHERE status = ?',
    ['active']
);
```

Use bindings rather than concatenating untrusted values into SQL.

The dependency also contains `Lukman\Database\QueryBuilder`, but the starter does not provide a dedicated query-builder helper or application-level wrapper. Treat query-builder usage as database-package dependent and verify the installed package version before using it.

## Migrations Status

The starter does not register a migration command and does not include a migrations directory or migration runner.

Commands such as `php intisari migrate` and `php intisari make:migration` are not implemented by this starter.

## Seeders Status

The starter does not register a database seeding command and does not include a seeder structure.

Commands such as `php intisari db:seed` are not implemented.

## Common Mistakes

### Missing PDO Driver

Enable `pdo_sqlite`, `pdo_mysql`, or `pdo_pgsql` for the selected connection.

### Missing SQLite File

Create `database/database.sqlite` before opening the default connection.

### Reusing MySQL Settings for PostgreSQL

Update the port, username, and charset when changing database engines.

### Committing Credentials

Keep passwords in `.env` and ensure `.env` remains ignored by version control.

### Assuming ORM Features

The database package provides connections and lower-level database utilities. The starter does not provide ORM models, automatic persistence, or model relationships.

### Assuming Migration or Seeder Commands

Check `php intisari` for registered commands. Migration and seeding commands are not part of the current starter.

## Next

Continue to [Intisari CLI](../cli/index.md).
