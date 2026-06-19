# Database

IntisariPHP Starter provides a `database/` folder and database configuration through `.env` and `config/database.php`.

The starter defaults to SQLite in `.env.example`, which is useful for local development and simple application prototypes.

## Database Configuration

Database settings are read from environment variables.

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

```powershell
New-Item database -ItemType Directory -Force
New-Item database/database.sqlite -ItemType File
```

Do not commit a production database file to version control. Local SQLite files may contain real application data.

## Using MySQL or PostgreSQL

The starter's `config/database.php` includes MySQL and PostgreSQL connection arrays. These are configuration examples, not the default connection.

Example MySQL environment:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=intisari
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

Example PostgreSQL environment:

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

Running queries depends on IntisariPHP core database support and the database services available in your installed version. Check the core documentation before using database manager, connection, or query builder APIs.

## Schema Changes

The starter does not include database schema change files or a schema command.

Schema change workflow is planned or depends on IntisariPHP core database support. If your installed version provides one, follow that package documentation.

## Next Steps

Continue with [Command Line](../cli/index.md).
