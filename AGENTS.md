# Instructions for AI Coding Agents

## Project Overview

`intisari-starter` is the lightweight application template for IntisariPHP. Application code, routes, configuration, views, documentation, CLI commands, and tests belong here. Reusable framework runtime behavior belongs in the installed `lukman-ss/intisari` packages.

Keep the starter small. Prefer targeted changes and existing project patterns over new dependencies or abstractions.

## Repository Boundaries

- Never edit `vendor/`. Composer manages that directory, and updates overwrite local changes.
- Do not reimplement IntisariPHP core behavior in the starter.
- Do not invent or document framework features that are absent from the starter and installed source.
- Do not claim Laravel or CodeIgniter compatibility.
- Keep the web document root at `public/`; never expose the project root.

## Sources of Truth

- `composer.json`: dependencies, autoloading, and Composer scripts.
- `.env.example`: supported environment variables and safe example values.
- `config/*.php`: configuration keys, defaults, and environment usage.
- `routes/web.php`: supported HTTP route registration style and default routes.
- `routes/console.php`: available CLI commands, arguments, options, and generators.
- `docs/index.md`: documentation structure and navigation.

Route examples must match `routes/web.php`. CLI documentation must match `routes/console.php`. Environment documentation must match both `.env.example` and `config/*.php`.

## Language

- Write documentation in English.
- Use English for code identifiers, filenames, comments, examples, and technical terms.

## Required Verification

Always run these commands before finishing:

```bash
composer validate --strict
composer test
composer docs:check
```

Report failures directly. Do not describe work as complete while a required check is failing.
