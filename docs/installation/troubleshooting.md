# Installation Troubleshooting

Use these checks when a new IntisariPHP Starter application does not run as expected.

## Port Already in Use

Start the development server on another port:

```bash
php intisari serve --port=8080
```

On Windows PowerShell, identify the process using port 8000:

```powershell
Get-NetTCPConnection -LocalPort 8000 | Select-Object OwningProcess
Stop-Process -Id <PID>
```

## Missing Environment File

Copy the environment template:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

## Blank Page

For local development, confirm these values and inspect the terminal output:

```env
APP_ENV=local
APP_DEBUG=true
```

## Storage Permissions

The application may need write access to its runtime directories:

```bash
mkdir -p storage/cache storage/logs storage/framework
```

On Windows PowerShell:

```powershell
New-Item -ItemType Directory -Force -Path storage/cache, storage/logs, storage/framework
```

## SQLite Connection

Keep SQLite as the default local connection:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Create the database file when needed:

```bash
touch database/database.sqlite
```

On Windows PowerShell:

```powershell
New-Item -ItemType File -Force -Path database/database.sqlite
```

## Next

Continue to [Application Overview](../overview/index.md).
