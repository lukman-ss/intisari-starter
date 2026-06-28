<?php

declare(strict_types=1);

use Intisari\Application;

assert($app instanceof Application);

$app->command('about', static function ($input, $output) use ($app): int {
    $name = $app->config()->get('app.name', 'Intisari App');
    $output->writeln('Application Name: ' . $name);
    return 0;
}, 'Display basic information about the application');

$app->command('env', static function ($input, $output) use ($app): int {
    $env = $app->config()->get('app.env', 'unknown');
    $output->writeln('APP_ENV: ' . $env);
    return 0;
}, 'Display the current application environment');

$app->command('route:list', static function ($input, $output) use ($app): int {
    $webRoutesPath = $app->basePath('routes/web.php');
    if (is_file($webRoutesPath)) {
        $app->loadRoutes($webRoutesPath);
    }
    
    try {
        $routes = method_exists($app->router(), 'routes') ? $app->router()->routes()->all() : [];
    } catch (\Throwable) {
        $routes = [];
    }
    
    if (empty($routes)) {
        $output->writeln('No routes registered.');
        return 0;
    }
    
    $output->writeln(str_pad('Method', 10) . str_pad('URI', 20) . 'Handler');
    $output->writeln(str_repeat('-', 60));
    
    foreach ($routes as $route) {
        $methods = implode('|', method_exists($route, 'methods') ? $route->methods() : []);
        $path = method_exists($route, 'path') ? $route->path() : 'unknown';
        $handler = method_exists($route, 'handler') ? $route->handler() : 'unknown';
        
        if (is_array($handler)) {
            $handlerStr = (is_object($handler[0]) ? get_class($handler[0]) : $handler[0]) . '@' . $handler[1];
        } elseif ($handler instanceof \Closure) {
            $handlerStr = 'Closure';
        } else {
            $handlerStr = is_string($handler) ? $handler : 'Unknown';
        }
        
        $output->writeln(str_pad($methods, 10) . str_pad($path, 20) . $handlerStr);
    }
    return 0;
}, 'List all registered web routes');

$app->command('serve', static function ($input, $output) use ($app): int {
    $host = '127.0.0.1';
    $port = 8000;
    $output->writeln("Intisari development server started on http://{$host}:{$port}");
    
    if (getenv('APP_ENV') === 'testing' || ($_ENV['APP_ENV'] ?? null) === 'testing') {
        return 0;
    }
    
    $cmd = sprintf('%s -S %s:%d -t %s', escapeshellarg(PHP_BINARY), escapeshellarg($host), $port, escapeshellarg($app->basePath('public')));
    passthru($cmd, $exitCode);
    return $exitCode;
}, 'Start the built-in development server');

$app->command('test', static function ($input, $output): int {
    if (getenv('APP_ENV') === 'testing' || ($_ENV['APP_ENV'] ?? null) === 'testing') {
        $output->writeln('Command preview: composer test');
        return 0;
    }
    passthru('composer test', $exitCode);
    return $exitCode;
}, 'Run the application tests');
