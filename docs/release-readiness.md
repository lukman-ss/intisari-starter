# Release Readiness Checklist

This document outlines the mandatory verification steps and checklist items that must be completed before tagging and publishing a new release of **IntisariPHP Starter** (e.g. `lukman-ss/intisari-starter`).

---

## 1. Automated Quality Checks

Run the following commands locally to verify codebase health and integrity:

* **Validate composer.json**: Ensure the composer config is valid.
  ```bash
  composer validate --strict
  ```
* **Dependency Check**: Re-install dependencies and ensure everything builds correctly.
  ```bash
  composer install
  ```
* **Run Tests**: Execute all unit and feature tests to ensure no regressions.
  ```bash
  composer test
  ```
* **Documentation Quality Check**: If available, run the documentation validator to verify H1 count, empty code blocks, and internal relative links.
  ```bash
  composer docs:check
  ```

---

## 2. Documentation & Links Check

* **README Links**: Review [README.md](../README.md) and verify all links are correct, functional, and point to the right sections or files.
* **Docs Links**: Verify all internal relative links in the `docs/` directory. Ensure that [docs/index.md](index.md) links to every main documentation page.
* **No Unsupported Feature Claims**: Ensure documentation does not claim support for features not present in the current starter or core (e.g., automatic JSON response helpers, ORM, queues, CSRF middleware unless explicitly implemented).
* **No Broken Security Examples**: Verify that code samples illustrating security, escaping (using the `$e()` view helper or `htmlspecialchars`), and input validation are accurate and safe to copy.
* **Public Root Warning**: Check that the deployment and security documentation contains clear, highlighted warnings instructing users to set the web server document root to `public/` and never to the project root.

---

## 3. Configuration & CLI Verification

* **`.env.example` Consistency**: Check that any new configuration options introduced in `config/*.php` are also declared in [`.env.example`](../.env.example) with safe default values.
* **Route List CLI Check**: Run the route listing command to ensure there are no syntax or runtime errors during boot:
  ```bash
  php intisari route:list
  ```
* **CLI Generator Smoke Test**: Run the generator commands (e.g., generating controllers) to verify that generated templates are syntax-valid and correctly registered.

---

## 4. Release Registry & Versioning

* **CHANGELOG**: If a `CHANGELOG.md` file exists in the project root, update it with the release date, version, and bullet points detailing the added features, fixed bugs, and breaking changes.
* **Git Tagging Format**: Recommend tagging releases using semantic versioning. Use the standard prefix `v` followed by major, minor, and patch version numbers:
  ```bash
  git tag -a v1.0.0 -m "Release version 1.0.0"
  git push origin v1.0.0
  ```
