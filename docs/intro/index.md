# Introduction

IntisariPHP Starter is the default application skeleton for projects built with IntisariPHP core.

It provides the files needed to start an application: a front controller, bootstrap file, route files, controllers, middleware, configuration files, PHP views, storage directories, a command line entry point, and PHPUnit setup.

## Who Should Use It

Use IntisariPHP Starter if you are:

- A PHP developer starting a new IntisariPHP application.
- A framework learner who wants to understand the application structure.
- A developer building a small web application or JSON endpoint.
- A package user who wants the core framework already wired into a project layout.

## Starter vs IntisariPHP Core

IntisariPHP core provides framework behavior. IntisariPHP Starter provides the application structure around it.

The starter requires `lukman-ss/intisari` through Composer:

```text
{
  "require": {
    "lukman-ss/intisari": "^1.0"
  }
}
```

The starter contains project-level files:

```text
app/
bootstrap/
config/
public/
resources/
routes/
storage/
tests/
```

IntisariPHP core is installed in `vendor/` by Composer and provides the runtime features used by the starter.

## Design Goals

IntisariPHP Starter is designed to be:

- Simple: clear files and minimal defaults.
- Lightweight: no large application modules included by default.
- Composer-based: dependencies and autoloading are managed by Composer.
- PHP 8.2+: modern PHP syntax and runtime baseline.
- Clean: application code, configuration, routes, views, storage, and tests have separate locations.

## When to Use This Starter

Use this starter when:

- You want to create a new IntisariPHP application.
- You need a working project layout with HTTP and command line entry points.
- You want simple PHP views, route files, configuration files, and PHPUnit setup already in place.
- You are learning how an IntisariPHP application is structured.

## When Not to Use This Starter

Do not use this starter when:

- You only need IntisariPHP core for another integration.
- You are maintaining an existing application with its own project structure.
- You need features that are not present in this repository unless they are provided by installed IntisariPHP core features.
- You want a full application template with complete application modules already implemented.

## Next Steps

Continue with [Requirements](requirements.md).
