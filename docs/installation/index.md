# Installation

This section explains how to create and run a new IntisariPHP Starter application.

The recommended installation method is Composer's `create-project` command. It creates a new application directory, installs IntisariPHP core, and prepares Composer autoloading for the `App\` namespace.

## Recommended Method

```bash
composer create-project lukman-ss/intisari-starter my-app
cd my-app
```

After installation, copy the environment file:

```bash
cp .env.example .env
```

## Folder Result

A new application contains the starter project structure:

```text
my-app/
  app/
  bootstrap/
  config/
  database/
  public/
  resources/
  routes/
  storage/
  tests/
  .env.example
  composer.json
  intisari
```

The `vendor/` directory is created by Composer and contains installed dependencies, including the IntisariPHP core package.

## Installation Topics

- [Composer Installation](composer.md)
- [Running the Application](running.md)

## Next Steps

Continue with [Composer Installation](composer.md).
