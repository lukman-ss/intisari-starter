# Starter vs. Core Boundary

This guide explains the architectural boundaries between the **IntisariPHP Starter** application repository (`lukman-ss/intisari-starter`) and the **IntisariPHP core** package (`lukman-ss/intisari`).

---

## 1. Ownership Division

The application architecture separates your custom codebase from the framework runtime engine:

* **IntisariPHP Starter (Application Space)**: Owns the application scaffolding, directory structure, configurations, application bootstrap files, routes, controllers, views, custom console commands, custom middleware, database assets, and unit tests.
* **IntisariPHP core (Framework Space)**: Installed as a Composer package under `vendor/lukman-ss/intisari/`. It owns the HTTP foundation, router engine, DI container, base session drivers, view engines, configuration loaders, and console runner.

---

## 2. Directory Mappings

### Application Files (Starter)
Modify these directories to build your application:
* **`app/`**: Put all your custom domain code here, including controllers (`app/Controllers/`), custom middleware, console commands, and service providers.
* **`config/`**: Customize configuration values (e.g. database connections, session lifetimes).
* **`routes/`**: Map HTTP endpoints in `routes/web.php` and CLI tools in `routes/console.php`.
* **`resources/`**: Store user-facing views and template layouts.
* **`storage/`**: Contains runtime-generated log files, configuration caches, and sessions.

### Framework Engine (Core)
The core package provides the runtime classes (located in the `Lukman\` namespace under `vendor/lukman-ss/intisari`):
* `Lukman\Http\Request` and `Lukman\Http\Response` (HTTP cycle)
* `Lukman\Router\Router` (routing mechanism)
* `Lukman\Container\Container` (dependency injection)
* `Lukman\Session\Session` (session handling)
* `Lukman\View\View` (view rendering loader)
* `Lukman\Console\Application` (command line runner)

---

## 3. Maintenance Rules

### Do Not Edit `vendor/`
Never modify files inside the `vendor/` directory. Direct modifications to vendor files are temporary and will be overwritten or deleted the next time you run `composer install` or `composer update`.

### Updating the Core Framework
To update the core framework features and bug fixes, update the composer dependency package:
```bash
composer update lukman-ss/intisari
```

### Reporting and Fixing Issues
* **Starter issues**: If you find issues related to default configuration templates, starter routing, bootstrap setups, or instructions, report them in the `intisari-starter` repository.
* **Core issues**: If you find issues related to the routing runtime, dependency injection container, HTTP request/response parsing, or console bootstrapping, report them or submit pull requests directly to the framework core repository at [github.com/lukman-ss/intisari](https://github.com/lukman-ss/intisari).
