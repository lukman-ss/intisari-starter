# IntisariPHP Starter Documentation

IntisariPHP Starter is a ready-to-run application skeleton for building PHP web applications with IntisariPHP.

The starter provides the project layout around IntisariPHP core, `lukman-ss/intisari`. IntisariPHP core supplies the framework behavior, while this repository provides the application entry points, routes, configuration files, views, storage directories, command line entry point, and testing setup needed to start a real project.

This guide is written for PHP developers, framework learners, and small web app builders who want a simple structure before adding application-specific code.

## Quick Commands

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
composer test
```

## Documentation Map

### Getting Started

- [Introduction](intro/index.md)
- [Requirements](intro/requirements.md)
- [Installation](installation/index.md)
- [Composer Installation](installation/composer.md)
- [Running the Application](installation/running.md)

### Tutorials

- [Build Your First Intisari Application](tutorials/build-your-first-app.md)
- [Getting Started with REST APIs](tutorials/rest-api.md)

### Overview

- [Application Overview](overview/index.md)
- [Application Structure](overview/application-structure.md)
- [Application Lifecycle](overview/lifecycle.md)

### Basic Concepts

- [Routing](basics/routing.md)
- [Controllers](basics/controllers.md)
- [Views](basics/views.md)
- [Configuration](basics/configuration.md)

### General Topics

- [Error Handling and Debugging](general/error-handling.md)
- [Security Guidelines](general/security.md)

### Database

- [Database](database/index.md)

### Command Line

- [Command Line](cli/index.md)

### Testing

- [Testing](testing/index.md)

### Deployment

- [Deployment](deployment/index.md)

## How to Read This Guide

Start with the installation pages if you are creating a new project. Read the overview pages if you want to understand how the application boots and where files belong. Use the basic concept pages when you begin editing routes, controllers, views, and configuration.

## Next Steps

Continue with [Introduction](intro/index.md).
