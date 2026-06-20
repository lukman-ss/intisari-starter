# Introduction

## What Is IntisariPHP Starter?

IntisariPHP Starter is a minimal project skeleton for building PHP web applications. It provides the application structure, front controller, bootstrap file, routes, controllers, middleware, views, configuration, CLI entry point, storage directories, and test setup.

The starter is application code. You modify it to implement your own routes, views, and business logic.

## What Is IntisariPHP Core?

IntisariPHP core is the `lukman-ss/intisari` Composer dependency used by the starter. It supplies the framework runtime and integrates the component packages used by the application.

Core code is installed under `vendor/` and should be updated through Composer rather than edited directly.

## Starter vs Core

| Part | Responsibility |
| --- | --- |
| IntisariPHP Starter | Project structure and application-owned code |
| IntisariPHP core | Framework runtime installed as a dependency |

Each application has its own starter files. The core dependency is versioned separately and can be shared by multiple applications.

## Design Goals

- **Simple** — keep the default application easy to inspect.
- **Lightweight** — include a small starting structure without application-specific modules.
- **Composer-based** — install and manage dependencies through Composer.
- **PHP 8.2+** — use a supported modern PHP baseline.
- **Clean structure** — separate application, configuration, routes, resources, storage, and tests.
- **CLI-friendly** — provide a project CLI for common development tasks.
- **Testable** — include PHPUnit configuration and example tests.

## When to Use It

Use the starter when you need:

- A small PHP web application or API foundation.
- Explicit routes, controllers, views, and configuration.
- An application that is easy to inspect and extend.
- Control over which application features are added.

## When Not to Use It

The starter may not fit when you need:

- A complete CMS or e-commerce application.
- Built-in application modules such as authentication, queues, or scheduling.
- A large ecosystem of first-party application packages.
- Features that are not present in the installed IntisariPHP packages.

## Next

Continue to [Requirements](requirements.md).
