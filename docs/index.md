# IntisariPHP Starter Documentation

IntisariPHP Starter is a minimal project skeleton for building PHP web applications. It provides the application structure, bootstrap, configuration, routes, views, CLI entry point, and PHPUnit setup.

## IntisariPHP Starter and IntisariPHP core

IntisariPHP Starter is the project you install and modify. It contains your application code and project-level configuration.

IntisariPHP core is the `lukman-ss/intisari` Composer dependency. It provides the framework runtime used by the application. See [IntisariPHP Starter vs. IntisariPHP core](overview/starter-vs-core.md) for the ownership boundary.

## Quick Start

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
```

Open `http://127.0.0.1:8000` after the development server starts.

## Recommended Reading Path

Begin with [Getting Started](getting-started.md), check the [Requirements](intro/requirements.md), complete [Installation](installation/index.md), then review the [Application Overview](overview/index.md) and [Basic Concepts](basics/routing.md).

## Documentation Map

### Introduction

- [Introduction](intro/index.md)
- [Requirements](intro/requirements.md)
- [Getting Started](getting-started.md)

### Installation

- [Installation](installation/index.md)
- [Composer Installation](installation/composer.md)
- [Running the Application](installation/running.md)
- [Troubleshooting](installation/troubleshooting.md)

### Application Overview

- [Overview](overview/index.md)
- [Application Structure](overview/application-structure.md)
- [Request Lifecycle](overview/request-lifecycle.md)
- [IntisariPHP Starter vs. IntisariPHP core](overview/starter-vs-core.md)

### Basic Concepts

- [Routing](basics/routing.md)
- [Controllers](basics/controllers.md)
- [Views](basics/views.md)
- [Middleware](basics/middleware.md)
- [Configuration](basics/configuration.md)
- [Error Handling](basics/error-handling.md)
- [Logging](basics/logging.md)

### Database

- [Database](database/index.md)

### CLI

- [Command Line](cli/index.md)

### Testing

- [Testing](testing/index.md)

### Deployment

- [Deployment](deployment/index.md)

### Security

- [Security](security/index.md)

### Tutorials

- [Build Your First App](tutorials/build-your-first-app.md)
- [REST API Basics](tutorials/rest-api.md)

### Release Readiness

- [Release Readiness Checklist](release-readiness.md)

### Postmortems

- [v1.2.0 Source Formatting Corruption](postmortems/v1.2.0-source-corruption.md)

## Next

Continue to [Getting Started](getting-started.md).
