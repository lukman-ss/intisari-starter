# Logging

## Verified Starter Behavior

The starter includes a private `storage/logs/` directory and preserves it with `.gitkeep`.

No application logger, logging facade, Monolog dependency, automatic log writer, log format, or log filename is configured by the current starter and core source. The presence of `storage/logs/` does not guarantee that an error will be written there.

## Inspecting Logs

When application code, PHP, or the web server is configured to write logs:

1. Check the terminal running `composer serve` for local runtime output.
2. Inspect `storage/logs/` for files created by application-owned code or installed packages.
3. Check the PHP or web-server log destination configured by the deployment environment.
4. Correlate timestamps with the failing request.

Do not assume a specific filename. List the directory contents and inspect only files created by the active logging configuration.

## Permissions

If application code writes under `storage/logs/`, the runtime user needs write permission for that directory.

Keep permissions limited to the runtime user or group. Do not make the project root writable, and do not place `storage/` under the public web root.

Permission failures may appear as a 500 response or as output in the PHP or web-server error stream.

## Sensitive Data

Do not log:

- Passwords or database credentials.
- API tokens or authorization headers.
- Session identifiers.
- Complete `.env` contents.
- Unfiltered personal or request data.

Record only the context required to diagnose an operation.

## Retention

Logging retention is not managed by the starter. If the application creates persistent logs:

- Define a retention period appropriate for the deployment.
- Rotate or archive logs before they consume available disk space.
- Restrict access to retained files.
- Remove logs according to operational and privacy requirements.

Use operating-system, hosting-platform, or application-owned retention tooling. No retention command is included in the starter.

## Limitations

Logging behavior must be implemented by application code or supplied by a separately verified package or runtime configuration. Do not assume logger APIs that are not present in the installed source.

## Next

Continue to [Database](../database/index.md).
