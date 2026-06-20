# IntisariPHP Starter Documentation

IntisariPHP Starter is a minimal project skeleton for building PHP web applications. It provides the application directories, entry points, configuration, routes, views, CLI commands, and test setup used by a new project.

## Starter and Core

**IntisariPHP Starter** is the project you install and modify. It contains your application code, configuration, routes, views, storage directories, and tests.

**IntisariPHP core** is the `lukman-ss/intisari` Composer dependency. It provides the framework runtime used by the starter and is installed under `vendor/`.

## Quick Start

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
composer test
```

## Documentation Map

### Introduction

- [Introduction](intro/index.md)
- [Requirements](intro/requirements.md)

### Installation

- [Installation Overview](installation/index.md)
- [Composer Installation](installation/composer.md)
- [Running the Application](installation/running.md)
- [Installation Troubleshooting](installation/troubleshooting.md)

### Application Overview

- [Application Overview](overview/index.md)
- [Application Structure](overview/application-structure.md)
- [Request Lifecycle](overview/request-lifecycle.md)
- [Starter vs. Core](overview/starter-vs-core.md)

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

### Command Line

- [Command Line Usage](cli/index.md)

### Testing

- [Testing](testing/index.md)

### Deployment

- [Deployment](deployment/index.md)

### Security

- [Security](security/index.md)

### Release Management

- [Release Readiness Checklist](release-readiness.md)

### Tutorials

- [Build Your First App](tutorials/build-your-first-app.md)
- [REST API Basics](tutorials/rest-api.md)

## Recommended Reading Path

New users should begin with [Getting Started](getting-started.md), continue through [Introduction](intro/index.md) and [Installation](installation/index.md), then read [Application Overview](overview/index.md) before moving to [Basic Concepts](basics/routing.md).

## Next

Continue to [Getting Started](getting-started.md).
