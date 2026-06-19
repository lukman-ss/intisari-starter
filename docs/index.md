# IntisariPHP Starter Documentation

IntisariPHP Starter is a minimal PHP web application skeleton. It provides the project structure you need to start developing: a front controller, routes, controllers, middleware, views, configuration, storage, a CLI, and PHPUnit testing.

## What's Included

**IntisariPHP core** ([`lukman-ss/intisari`](https://packagist.org/packages/lukman-ss/intisari)) is the framework. It provides routing, HTTP handling, dependency injection, configuration, console commands, and view rendering. The core is installed via Composer into `vendor/` and is not modified directly.

**IntisariPHP Starter** is your application. You modify the starter's files — controllers, routes, views, config — to build your project. The starter depends on the core but remains a separate, lightweight skeleton.

## Quick Start

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
composer test
```

The application will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Documentation Map

### Introduction

- [Introduction](intro/index.md)
- [Requirements](intro/requirements.md)

### Installation

- [Installation Overview](installation/index.md)
- [Composer Installation](installation/composer.md)
- [Running the Application](installation/running.md)

### Application Overview

- [Overview](overview/index.md)
- [Application Structure](overview/application-structure.md)
- [Request Lifecycle](overview/request-lifecycle.md)

### Basic Concepts

- [Routing](basics/routing.md)
- [Controllers](basics/controllers.md)
- [Views](basics/views.md)
- [Middleware](basics/middleware.md)
- [Configuration](basics/configuration.md)

### Database

- [Database](database/index.md)

### CLI

- [Command Line Usage](cli/index.md)

### Testing

- [Testing](testing/index.md)

### Deployment

- [Deployment](deployment/index.md)

### Security

- [Security](security/index.md)

### Tutorials

- [Build Your First App](tutorials/build-your-first-app.md)
- [REST API Basics](tutorials/rest-api.md)
