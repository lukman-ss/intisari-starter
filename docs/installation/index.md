# Installation

This section explains the standard installation flow for a new IntisariPHP Starter application.

The recommended method is Composer's `create-project` command. It creates a project directory, installs IntisariPHP core, installs development dependencies, and prepares Composer autoloading.

## Installation Overview

```text
create project
  -> enter project folder
  -> copy .env
  -> run local server
  -> run tests
```

## Quick Install

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
cp .env.example .env
composer serve
```

Open the local application:

```text
http://127.0.0.1:8000
```

Run the test suite:

```bash
composer test
```

## Read More

- [Composer Installation](composer.md)
- [Running the Application](running.md)

## Next Steps

Continue with [Composer Installation](composer.md).
