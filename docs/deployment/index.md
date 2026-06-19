# Deployment

Deploying IntisariPHP Starter follows the same basic rules as many PHP applications: install dependencies, configure the environment, point the web server to the public directory, and make runtime storage writable.

The starter is designed so only `public/index.php` is directly web-accessible. Do not expose the project root to the public web.

## Production Requirements

A production server should provide:

- PHP 8.2 or newer.
- Composer for dependency installation.
- A web server such as Nginx or Apache.
- PHP-FPM or another production PHP runtime.
- Required PHP extensions for installed IntisariPHP core features and application code.

## Environment Configuration

Create the production `.env` file manually on the server.

Do not copy local secrets blindly, and do not commit `.env` to version control.

Example production values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
```

After changing production environment values, clear and rebuild configuration cache if your deployment uses it.

## Web Server Document Root

The document root must point to:

```text
/path/to/app/public
```

Do not point the web server to:

```text
/path/to/app
```

The project root contains application code, configuration, routes, storage, and dependencies that should not be directly web-accessible.

## File Permissions

The web server user must be able to read application files and write to runtime storage paths.

Avoid making the entire project writable. Keep write access limited to directories that need runtime writes.

## Composer Install for Production

Install production dependencies without development packages:

```bash
composer install --no-dev --optimize-autoloader
```

Run this command from the project root after uploading or pulling the application code.

## Debug Mode

Production should disable debug mode:

```env
APP_DEBUG=false
```

Debug output may expose paths, configuration, or exception details.

## Storage Directory

The `storage/` directory must be writable where the application writes cache, logs, and framework runtime files.

Common writable paths include:

```text
storage/cache
storage/logs
storage/framework
```

## Example Nginx Server Block

This is a simple PHP-FPM example. Adjust paths, PHP-FPM socket/version, server name, TLS settings, and security rules for your server environment.

```nginx
server {
    listen 80;
    server_name example.com;

    root /path/to/app/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Security Checklist

- Point the document root to `public/`.
- Do not expose the project root to the public web.
- Create `.env` manually on the production server.
- Set `APP_DEBUG=false`.
- Keep `.env` out of version control.
- Install dependencies with `--no-dev`.
- Ensure `storage/` is writable by the runtime user.
- Restrict write permissions to required runtime directories.
- Use HTTPS in production when available.

## Next Steps

Return to the [Documentation Index](../index.md).
