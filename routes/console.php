<?php

declare(strict_types=1);

use Intisari\Application;

assert($app instanceof Application);

$app->command('hello', static function ($input, $output): int {
    $output->writeln('Hello Intisari');

    return 0;
}, 'Print hello message');

$app->command('serve', static function ($input, $output): int {
    $host = $input->option('host', '127.0.0.1');
    $port = (int) $input->option('port', 8000);

    $output->writeln('Intisari development server started');
    $output->writeln("http://{$host}:{$port}");

    $isTesting = (getenv('APP_ENV') === 'testing' || ($_ENV['APP_ENV'] ?? null) === 'testing' || ($_SERVER['APP_ENV'] ?? null) === 'testing');

    if ($isTesting) {
        $output->writeln(sprintf('Command preview: php -S %s:%d -t public', $host, $port));
        return 0;
    }

    $commandStr = sprintf('php -S %s:%d -t public', $host, $port);
    passthru($commandStr, $exitCode);

    return $exitCode;
}, 'Start the built-in development server');

$app->command('route:list', static function ($input, $output) use ($app): int {
    $webRoutesPath = $app->routesPath('web.php');
    if (is_file($webRoutesPath)) {
        $app->loadRoutes($webRoutesPath);
    }

    try {
        if (method_exists($app, 'router') && method_exists($app->router(), 'routes') && method_exists($app->router()->routes(), 'all')) {
            $routes = $app->router()->routes()->all();
        } else {
            $routes = null;
        }
    } catch (\Throwable $e) {
        $routes = null;
    }

    if ($routes === null) {
        $output->writeln('Route listing is not available.');
        return 0;
    }

    $formatHandler = static function (mixed $handler): string {
        if ($handler instanceof \Closure) {
            return 'Closure';
        }
        if (is_string($handler)) {
            return $handler;
        }
        if (is_array($handler) && count($handler) === 2) {
            $class = is_object($handler[0]) ? get_class($handler[0]) : (string) $handler[0];
            $method = (string) $handler[1];
            return "{$class}@{$method}";
        }
        if (is_object($handler)) {
            return get_class($handler);
        }
        return 'Unknown';
    };

    $headers = ['Method', 'URI', 'Name', 'Handler'];
    $rows = [];
    foreach ($routes as $route) {
        if (!is_object($route) || !method_exists($route, 'methods') || !method_exists($route, 'path')) {
            continue;
        }

        $handler = method_exists($route, 'handler') ? $route->handler() : null;
        $name = method_exists($route, 'name') ? $route->name() : '';

        $rows[] = [
            implode('|', $route->methods()),
            $route->path(),
            (string) ($name ?? ''),
            $formatHandler($handler),
        ];
    }

    $colWidths = [];
    foreach ($headers as $index => $header) {
        $colWidths[$index] = strlen($header);
    }

    foreach ($rows as $row) {
        foreach ($row as $index => $value) {
            $colWidths[$index] = max($colWidths[$index], strlen($value));
        }
    }

    $border = '+';
    foreach ($colWidths as $width) {
        $border .= str_repeat('-', $width + 2) . '+';
    }

    $output->writeln($border);
    $headerLine = '|';
    foreach ($headers as $index => $header) {
        $headerLine .= ' ' . str_pad($header, $colWidths[$index]) . ' |';
    }
    $output->writeln($headerLine);
    $output->writeln($border);

    foreach ($rows as $row) {
        $rowLine = '|';
        foreach ($row as $index => $value) {
            $rowLine .= ' ' . str_pad($value, $colWidths[$index]) . ' |';
        }
        $output->writeln($rowLine);
    }
    $output->writeln($border);

    return 0;
}, 'List all registered web routes');

$app->command('config:cache', static function ($input, $output) use ($app): int {
    $configPath = $app->configPath();
    if (is_dir($configPath)) {
        $app->loadConfiguration($configPath);
    }

    $cacheDir = $app->storagePath('cache');
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    $cacheFile = $cacheDir . '/config.php';
    $app->config()->cacheTo($cacheFile);

    $output->writeln('Configuration cached successfully.');

    return 0;
}, 'Create a cache file for faster configuration loading');

$app->command('config:clear', static function ($input, $output) use ($app): int {
    $cacheFile = $app->storagePath('cache/config.php');

    if (is_file($cacheFile)) {
        unlink($cacheFile);
    }

    $output->writeln('Configuration cache cleared.');

    return 0;
}, 'Remove the configuration cache file');

$app->command('make:controller', static function ($input, $output) use ($app): int {
    $name = $input->argument(0);
    if (empty($name)) {
        $output->errorLine('Not enough arguments (missing: "name").');
        return 1;
    }

    $name = ucfirst($name);
    if (!str_ends_with($name, 'Controller')) {
        $name .= 'Controller';
    }

    $controllersDir = $app->path('Controllers');
    if (!is_dir($controllersDir)) {
        mkdir($controllersDir, 0777, true);
    }

    $filePath = $controllersDir . '/' . $name . '.php';
    $force = (bool) $input->option('force', false);

    if (is_file($filePath) && !$force) {
        $output->writeln('Controller already exists.');
        return 0;
    }

    $template = <<<PHP
<?php

declare(strict_types=1);

namespace App\Controllers;

final class {$name}
{
    public function index(): mixed
    {
        return null;
    }
}

PHP;

    if (file_put_contents($filePath, $template) === false) {
        $output->errorLine('Failed to create controller.');
        return 1;
    }

    $output->writeln('Controller created successfully.');

    return 0;
}, 'Create a new controller class');

$app->command('make:middleware', static function ($input, $output) use ($app): int {
    $name = $input->argument(0);
    if (empty($name)) {
        $output->errorLine('Not enough arguments (missing: "name").');
        return 1;
    }

    $name = ucfirst($name);

    $middlewareDir = $app->path('Middleware');
    if (!is_dir($middlewareDir)) {
        mkdir($middlewareDir, 0777, true);
    }

    $filePath = $middlewareDir . '/' . $name . '.php';
    $force = (bool) $input->option('force', false);

    if (is_file($filePath) && !$force) {
        $output->writeln('Middleware already exists.');
        return 0;
    }

    $template = <<<'PHP'
<?php

declare(strict_types=1);

namespace App\Middleware;

use Lukman\Http\MiddlewareInterface;
use Lukman\Http\Request;
use Lukman\Http\RequestHandlerInterface;
use Lukman\Http\Response;

final class {{ClassName}} implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return $handler->handle($request);
    }
}

PHP;

    $template = str_replace('{{ClassName}}', $name, $template);

    if (file_put_contents($filePath, $template) === false) {
        $output->errorLine('Failed to create middleware.');
        return 1;
    }

    $output->writeln('Middleware created successfully.');

    return 0;
}, 'Create a new middleware class');

$app->command('make:provider', static function ($input, $output) use ($app): int {
    $name = $input->argument(0);
    if (empty($name)) {
        $output->errorLine('Not enough arguments (missing: "name").');
        return 1;
    }

    $name = ucfirst($name);
    if (!str_ends_with($name, 'ServiceProvider')) {
        $name .= 'ServiceProvider';
    }

    $providersDir = $app->path('Providers');
    if (!is_dir($providersDir)) {
        mkdir($providersDir, 0777, true);
    }

    $filePath = $providersDir . '/' . $name . '.php';
    $force = (bool) $input->option('force', false);

    if (is_file($filePath) && !$force) {
        $output->writeln('Provider already exists.');
        return 0;
    }

    $template = <<<'PHP'
<?php

declare(strict_types=1);

namespace App\Providers;

use Intisari\ServiceProvider;

final class {{ClassName}} extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}

PHP;

    $template = str_replace('{{ClassName}}', $name, $template);

    if (file_put_contents($filePath, $template) === false) {
        $output->errorLine('Failed to create provider.');
        return 1;
    }

    $output->writeln('Provider created successfully.');

    return 0;
}, 'Create a new service provider class');

$app->command('make:command', static function ($input, $output) use ($app): int {
    $name = $input->argument(0);
    if (empty($name)) {
        $output->errorLine('Not enough arguments (missing: "name").');
        return 1;
    }

    $name = ucfirst($name);
    if (!str_ends_with($name, 'Command')) {
        $name .= 'Command';
    }

    $commandsDir = $app->path('Commands');
    if (!is_dir($commandsDir)) {
        mkdir($commandsDir, 0777, true);
    }

    $filePath = $commandsDir . '/' . $name . '.php';
    $force = (bool) $input->option('force', false);

    if (is_file($filePath) && !$force) {
        $output->writeln('Command already exists.');
        return 0;
    }

    $baseName = $name;
    if (str_ends_with($baseName, 'Command')) {
        $baseName = substr($baseName, 0, -7);
    }
    $commandName = preg_replace('/(?<!^)[A-Z]/', ':$0', $baseName);
    $commandName = strtolower($commandName);

    $template = <<<'PHP'
<?php

declare(strict_types=1);

namespace App\Commands;

use Lukman\Console\Command;
use Lukman\Console\Input;
use Lukman\Console\Output;

final class {{ClassName}} extends Command
{
    protected string $name = '{{CommandName}}';
    protected string $description = 'Command description';

    public function handle(Input $input, Output $output): int
    {
        return self::SUCCESS;
    }
}

PHP;

    $template = str_replace(['{{ClassName}}', '{{CommandName}}'], [$name, $commandName], $template);

    if (file_put_contents($filePath, $template) === false) {
        $output->errorLine('Failed to create command.');
        return 1;
    }

    $output->writeln('Command created successfully.');

    return 0;
}, 'Create a new console command class');

$app->command('about', static function ($input, $output) use ($app): int {
    $output->writeln('IntisariPHP');
    $output->writeln('------------------------------------');
    $output->writeln('Application Name: ' . $app->config()->get('app.name', 'Intisari App'));
    $output->writeln('Environment:      ' . $app->environment());
    $output->writeln('PHP Version:      ' . PHP_VERSION);
    $output->writeln('Base Path:        ' . $app->basePath());

    return 0;
}, 'Display basic information about the application');

$app->command('env', static function ($input, $output) use ($app): int {
    $output->writeln('Current application environment: ' . $app->environment());

    return 0;
}, 'Display the current application environment');

$app->command('test', static function ($input, $output): int {
    $isTesting = (getenv('APP_ENV') === 'testing' || ($_ENV['APP_ENV'] ?? null) === 'testing' || ($_SERVER['APP_ENV'] ?? null) === 'testing');

    if ($isTesting) {
        $output->writeln('Command preview: composer test');
        return 0;
    }

    passthru('composer test', $exitCode);

    return $exitCode;
}, 'Run the application tests');
