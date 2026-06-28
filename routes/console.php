<?php

declare(strict_types=1);

use Intisari\Application;

assert($app instanceof Application);

$isTesting = static function (): bool {
    return getenv('APP_ENV') === 'testing'
        || ($_ENV['APP_ENV'] ?? null) === 'testing'
        || ($_SERVER['APP_ENV'] ?? null) === 'testing';
};

$app->command('about', static function ($input, $output) use ($app): int {
    $name = $app->config()->get('app.name', 'Intisari App');

    $output->writeln('Application Name: ' . $name);
    $output->writeln('PHP Version:      ' . PHP_VERSION);
    $output->writeln('Base Path:        ' . $app->basePath());

    return 0;
}, 'Display basic information about the application');

$app->command('env', static function ($input, $output) use ($app): int {
    $env   = $app->config()->get('app.env', (string) ($_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: 'unknown'));
    $debug = $app->config()->get('app.debug', false) ? 'true' : 'false';

    $output->writeln('APP_ENV:   ' . $env);
    $output->writeln('APP_DEBUG: ' . $debug);

    return 0;
}, 'Display the current application environment and debug flag');

$app->command('route:list', static function ($input, $output) use ($app): int {
    $webRoutesPath = $app->routesPath('web.php');

    if (is_file($webRoutesPath)) {
        $app->loadRoutes($webRoutesPath);
    }

    try {
        $routes = method_exists($app->router(), 'routes')
            ? $app->router()->routes()->all()
            : null;
    } catch (\Throwable) {
        $routes = null;
    }

    if ($routes === null || $routes === []) {
        $output->writeln('No routes registered or route listing is not available.');
        return 0;
    }

    $formatHandler = static function (mixed $handler): string {
        if ($handler instanceof \Closure) {
            return 'Closure';
        }
        if (is_array($handler) && count($handler) === 2) {
            $class  = is_object($handler[0]) ? get_class($handler[0]) : (string) $handler[0];
            $method = (string) $handler[1];
            return "{$class}@{$method}";
        }
        if (is_string($handler)) {
            return $handler;
        }
        return 'Unknown';
    };

    $headers = ['Method', 'URI', 'Name', 'Handler'];
    $rows    = [];

    foreach ($routes as $route) {
        if (!is_object($route) || !method_exists($route, 'methods') || !method_exists($route, 'path')) {
            continue;
        }
        $handler = method_exists($route, 'handler') ? $route->handler() : null;
        $name    = method_exists($route, 'name') ? (string) ($route->name() ?? '') : '';

        $rows[] = [
            implode('|', $route->methods()),
            $route->path(),
            $name,
            $formatHandler($handler),
        ];
    }

    $colWidths = array_map('strlen', $headers);
    foreach ($rows as $row) {
        foreach ($row as $i => $value) {
            $colWidths[$i] = max($colWidths[$i], strlen($value));
        }
    }

    $border = '+' . implode('+', array_map(static fn (int $w) => str_repeat('-', $w + 2), $colWidths)) . '+';

    $output->writeln($border);
    $headerLine = '|' . implode('|', array_map(static fn (int $i) => ' ' . str_pad($headers[$i], $colWidths[$i]) . ' ', array_keys($headers))) . '|';
    $output->writeln($headerLine);
    $output->writeln($border);

    foreach ($rows as $row) {
        $line = '|' . implode('|', array_map(static fn (int $i) => ' ' . str_pad($row[$i], $colWidths[$i]) . ' ', array_keys($row))) . '|';
        $output->writeln($line);
    }

    $output->writeln($border);

    return 0;
}, 'List all registered web routes');

$app->command('serve', static function ($input, $output) use ($app, $isTesting): int {
    $host = (string) $input->option('host', '127.0.0.1');
    $port = (int) $input->option('port', 8000);

    if (preg_match('/^[A-Za-z0-9.-]+$/', $host) !== 1) {
        $output->errorLine('Invalid host value.');
        return 1;
    }

    if ($port < 1 || $port > 65535) {
        $output->errorLine('Port must be between 1 and 65535.');
        return 1;
    }

    $output->writeln('Intisari development server started');
    $output->writeln("http://{$host}:{$port}");

    if ($isTesting()) {
        $output->writeln(sprintf('Command preview: php -S %s:%d -t public', $host, $port));
        return 0;
    }

    $cmd = sprintf(
        '%s -S %s -t %s',
        escapeshellarg(PHP_BINARY),
        escapeshellarg("{$host}:{$port}"),
        escapeshellarg($app->basePath('public'))
    );

    passthru($cmd, $exitCode);

    return $exitCode;
}, 'Start the built-in development server');

$app->command('test', static function ($input, $output) use ($isTesting): int {
    if ($isTesting()) {
        $output->writeln('Command preview: composer test');
        return 0;
    }

    passthru('composer test', $exitCode);

    return $exitCode;
}, 'Run the application tests');
