# Introduction

IntisariPHP Starter is the default project skeleton for applications built with IntisariPHP core.

It provides the application files around IntisariPHP core: a front controller, bootstrap file, route files, configuration files, PHP views, storage directories, a command line entry point, and PHPUnit setup.

## What Problem It Solves

IntisariPHP core contains framework behavior. A real application still needs a directory layout, entry points, route files, configuration files, and runtime directories.

The starter solves that setup work by giving you a small project that can be installed, configured, served locally, and extended immediately.

## Relationship with IntisariPHP Core

The starter requires `lukman-ss/intisari` through Composer.

```json
{
  "require": {
    "lukman-ss/intisari": "^1.0"
  }
}
```

The starter does not replace IntisariPHP core. It uses IntisariPHP core to create and run the application.

## Starter vs IntisariPHP Core

The starter contains application-level files:

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

## Folder Documentation Map

- [Requirements](requirements.md)
- [Installation](../installation/index.md)
- [Composer Installation](../installation/composer.md)
- [Running the Application](../installation/running.md)

## Next Steps

Continue with [Requirements](requirements.md).
