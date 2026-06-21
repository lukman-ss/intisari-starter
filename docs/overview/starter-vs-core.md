# IntisariPHP Starter vs. IntisariPHP core

IntisariPHP Starter is the application project. IntisariPHP core is the framework runtime installed through Composer as `lukman-ss/intisari`.

## Paths Owned by IntisariPHP Starter

Developers normally work in these starter paths:

- `app/` for controllers, middleware, providers, and generated commands.
- `bootstrap/` for application startup wiring.
- `config/` and `.env` for configuration.
- `database/` for local database files.
- `public/` for the web entry point; this directory is the web root.
- `resources/views/` for PHP view templates.
- `routes/` for HTTP routes and project CLI commands.
- `scripts/` for repository maintenance scripts.
- `storage/` for writable runtime data; it must not be public.
- `tests/` and `phpunit.xml` for project tests.
- `docs/`, `.env.example`, `composer.json`, and `intisari` for project documentation and tooling.

Changes to these files belong to the application repository.

## Code Owned by IntisariPHP core

Composer installs the core package under `vendor/`. It supplies the runtime used by the starter, including application, HTTP, routing, configuration, container, view, and console integration.

Do not edit `vendor/`. Composer can overwrite those changes during installation or updates. Change the starter when behavior is application-specific; change the relevant core package when the reusable runtime itself must change.

## Dependency Updates

Install versions recorded in `composer.lock` with:

```bash
composer install
```

Review dependency changes before deliberately updating the core package:

```bash
composer update lukman-ss/intisari
```

## Web Access Boundary

Only `public/` should be served by the web server. The project root, `vendor/`, application source, configuration, storage, tests, scripts, and documentation must not be directly web-accessible.

## Next

Continue to [Routing](../basics/routing.md).
