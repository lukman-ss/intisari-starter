<?php

declare(strict_types=1);

$env = static function (string $key, mixed $default = null): mixed {
    return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
};

return [
    'name' => $env('APP_NAME', 'Intisari App'),
    'env' => $env('APP_ENV', 'local'),
    'debug' => filter_var($env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
    'url' => $env('APP_URL', 'http://127.0.0.1:8000'),
    'timezone' => $env('APP_TIMEZONE', 'Asia/Jakarta'),
    'locale' => $env('APP_LOCALE', 'en'),
    'providers' => [
        App\Providers\AppServiceProvider::class,
    ],
    'middleware' => [
        App\Middleware\ExampleMiddleware::class,
    ],
];
