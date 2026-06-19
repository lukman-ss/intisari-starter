# Introduction

## What Is IntisariPHP Starter?

IntisariPHP Starter is a minimal project skeleton for building PHP web applications with the IntisariPHP framework. It provides the directory structure and starter files you need: a front controller, routes, controllers, middleware, views, configuration, storage, a CLI, and PHPUnit tests.

The starter is not a complete application. It is a clean starting point that you extend by adding your own routes, controllers, views, and business logic.

## Who Should Use It

IntisariPHP Starter is designed for:

- PHP developers who want a clean, minimal foundation for web applications
- Developers who prefer working with plain PHP files rather than heavy scaffolding
- Anyone building small-to-medium web applications, APIs, or prototypes
- Developers who want to understand how a PHP framework is structured by reading a small codebase

## Starter vs Framework Core

**IntisariPHP core** (`lukman-ss/intisari`) is the framework runtime. It provides routing, HTTP handling, dependency injection, configuration, console commands, and view rendering. The core is installed via Composer into `vendor/` and should not be modified directly.

**IntisariPHP Starter** is your application code. It includes your controllers, views, routes, configuration, and tests. You build your application by modifying these files.

The core framework is shared across all IntisariPHP projects. The starter is unique to your project.

## Design Goals

- **Simple** — clear directory layout, minimal defaults, no hidden configuration
- **Lightweight** — no bundled application modules, no ORM, no authentication layer
- **Composer-based** — all dependencies managed through Composer
- **PHP 8.2+** — uses modern PHP syntax and features
- **Testable** — PHPUnit configured and ready to use

## When to Use It

- You need a small PHP project with routing, views, and configuration but without heavy framework overhead
- You want full control over which components to add
- You are prototyping an idea and need something running quickly
- You are building a REST API or a simple web application

## When Not to Use It

- You need a large-scale enterprise framework with built-in queues, broadcasting, and scheduling
- You need a full-featured CMS or e-commerce platform out of the box
- You require extensive official packages and ecosystem tooling comparable to Laravel or Symfony

## Next Steps

Continue to [Requirements](requirements.md) to check your development environment.
