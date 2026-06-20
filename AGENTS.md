# Instructions for AI Coding Agents

This repository is designed to be easily and safely editable by autonomous AI agents. Please follow these guidelines and technical boundaries when making changes to the codebase or documentation.

---

## 1. Project Overview & Scope

* **Purpose**: `intisari-starter` is a minimal, lightweight project template for bootstrapping web applications. It must remain small, fast, and free of heavy dependencies or unnecessary abstractions.
* **Core Runtime**: The framework engine is located in `lukman-ss/intisari` (installed as a vendor dependency). Do not attempt to re-implement framework features inside the starter.

---

## 2. Strict Technical Constraints

* **Do Not Edit `vendor/`**: Never make changes to code inside the `vendor/` directory. All core adjustments must be made in the upstream `lukman-ss/intisari` package.
* **No Unimplemented Feature Claims**: Do not write documentation or test cases claiming features that are not natively supported by the starter or the core framework (e.g. CSRF middleware, automatic JSON helpers, database ORM, mailers, or queue managers).
* **Language Requirements**: All code identifiers (variables, class names, functions), inline comments, and Markdown documentation files must be written in standard English.
* **Framework Independence**: Do not mention other frameworks (such as Laravel or CodeIgniter) as dependencies.

---

## 3. Configuration & Entry Points (Sources of Truth)

When adding or modifying components, align with the following source of truth files:
* **HTTP Routing**: `routes/web.php` (defines GET/POST handlers). Example routes must return a string or standard HTTP response.
* **CLI Routing**: `routes/console.php` (defines CLI commands). CLI examples must match this console router syntax.
* **Configuration**: Config schemas are defined under `config/*.php` (e.g. `config/database.php`). Any new configurations must also be added to `.env.example` with safe, default placeholders.
* **Front Controller**: `public/index.php` is the web entry point. The web server document root must always point to `public/` and never the project root.

---

## 4. Verification Checklist

Before ending your turn or committing changes, you must run:

1. **Strict Composer Validation**:
   ```bash
   composer validate --strict
   ```
2. **Run All Tests**:
   ```bash
   composer test
   ```
3. **Run Documentation Check**: Ensure that all modified or new Markdown files under `docs/` have exactly one H1 header, contain no empty code fences, and have fully resolvable internal relative links.
   ```bash
   composer docs:check
   ```
