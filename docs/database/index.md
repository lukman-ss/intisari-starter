# Database

IntisariPHP Starter provides a `database/` folder and database configuration through `.env` and `config/database.php`.

The starter defaults to SQLite in `.env.example`, which is useful for local development.

## Database Configuration

Database configuration is read from `.env` values.

```php
return [
    'default' => $env('DB_CONNECTION', 'sqlite'),
    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => $env('DB_DATABASE', 'database/database.sqlite'),
        ],
    ],
];
```

The default `.env.example` database values are:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

## SQLite for Local Development

SQLite stores the database in a local file.

Use this configuration for local development:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Create the directory and SQLite file when needed:

```bash
mkdir -p database
touch database/database.sqlite
```

On Windows PowerShell:

```text
New-Item database -ItemType Directory -Force
New-Item database/database.sqlite -ItemType File
```

Do not commit a production database file to version control. Local SQLite files may contain real application data.

## MySQL Environment Example

The starter's `config/database.php` includes a MySQL connection array. This is an example configuration, not the default connection.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=intisari
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

## PostgreSQL Environment Example

The starter's `config/database.php` includes a PostgreSQL connection array. This is an example configuration, not the default connection.

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=intisari
DB_USERNAME=postgres
DB_PASSWORD=
DB_CHARSET=utf8
```

Make sure the required PHP PDO driver is enabled for the database you choose.

## Running Queries

The starter does not define an application-level query API in its own files.

Running queries depends on IntisariPHP database package support and the database services available in your installed version.

Do not assume an ORM or query builder API unless it exists in the installed package documentation.

## Migrations

The starter does not include migration files or a migration command.

Migrations depend on IntisariPHP database package support. If your installed version provides migration features, follow that package documentation.

## Next Steps

Continue with [Command Line](../cli/index.md).
