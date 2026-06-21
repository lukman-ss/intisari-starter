# Introduction

## What Is IntisariPHP Starter?

IntisariPHP Starter is a minimal project skeleton for building PHP web applications. It provides application directories, bootstrap files, routes, controllers, views, configuration, a CLI entry point, storage paths, and a test setup.

The starter is application-owned code. You modify it to implement your routes, views, and business logic.

## What Is IntisariPHP core?

IntisariPHP core is the `lukman-ss/intisari` Composer dependency used by the starter. It supplies the framework runtime and connects the component packages used by the application.

IntisariPHP core code is installed under `vendor/`. Update it through Composer instead of editing vendor files.

## IntisariPHP Starter vs. IntisariPHP core

| Part | Responsibility |
| --- | --- |
| IntisariPHP Starter | Project structure, configuration, and application code |
| IntisariPHP core | Framework runtime installed as a dependency |

The starter and core are versioned separately. Each application owns its starter files while using the installed core version.

## Design Goals

- **Lightweight** - keep the starting project small and straightforward to inspect.
- **Composer-based** - install the starter and manage dependencies through Composer.
- **PHP 8.2+** - use PHP 8.2 or newer as the runtime baseline.
- **Clean structure** - separate application code, configuration, routes, resources, storage, and tests.
- **CLI-friendly** - provide a project CLI for common local tasks and file generation.
- **Testable** - include PHPUnit configuration and a working test structure.
- **Minimal by default** - add application-specific capabilities only when needed.

## When to Use It

Use the starter for a small PHP web application or API where explicit routes, controllers, views, and configuration are useful. It is suitable when you want a compact base that can be extended deliberately.

## When Not to Use It

The starter is not a complete CMS, e-commerce system, or prebuilt application. It may not fit projects that require application modules such as authentication, queues, or scheduling to be included by default.

## Next

Continue to [Requirements](requirements.md).
