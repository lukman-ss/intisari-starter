# IntisariPHP Starter Documentation

IntisariPHP Starter is a ready-to-run application skeleton for building PHP web applications with IntisariPHP.

IntisariPHP core, provided by `lukman-ss/intisari`, supplies the framework behavior. IntisariPHP Starter provides the application project layout: entry points, routes, controllers, views, configuration, storage directories, command line entry point, and testing setup.

Use this documentation hub to install the starter, understand the application structure, learn the basic concepts, and build small applications step by step.

## Quick Start

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
composer test
```

## Documentation Sections

### Introduction

- [Introduction](intro/index.md)
- [Requirements](intro/requirements.md)

### Installation

- [Installation](installation/index.md)
- [Composer Installation](installation/composer.md)
- [Running the Application](installation/running.md)

### Application Overview

- [Application Overview](overview/index.md)
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

### Command Line

- [Command Line Usage](cli/index.md)

### Testing

- [Testing](testing/index.md)

### Deployment

- [Deployment](deployment/index.md)

### Security

- [Security](security/index.md)
- [Error Handling and Debugging](general/error-handling.md)
- [Security Guidelines](general/security.md)

### Tutorials

- [Build Your First Intisari Application](tutorials/build-your-first-app.md)
- [Getting Started with REST APIs](tutorials/rest-api.md)
