<?php

declare(strict_types=1);

$env = static function (string $key, mixed $default = null): mixed {
    return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
};

return [
    'default' => $env('DB_CONNECTION', 'sqlite'),
    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => $env('DB_DATABASE', 'database/database.sqlite'),
        ],
        'mysql' => [
            'driver' => 'mysql',
            'host' => $env('DB_HOST', '127.0.0.1'),
            'port' => (int) $env('DB_PORT', 3306),
            'database' => $env('DB_DATABASE', 'intisari'),
            'username' => $env('DB_USERNAME', 'root'),
            'password' => $env('DB_PASSWORD', ''),
            'charset' => $env('DB_CHARSET', 'utf8mb4'),
        ],
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => $env('DB_HOST', '127.0.0.1'),
            'port' => (int) $env('DB_PORT', 5432),
            'database' => $env('DB_DATABASE', 'intisari'),
            'username' => $env('DB_USERNAME', 'postgres'),
            'password' => $env('DB_PASSWORD', ''),
            'charset' => $env('DB_CHARSET', 'utf8'),
        ],
    ],
];
