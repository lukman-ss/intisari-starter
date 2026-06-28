<?php

declare(strict_types=1);

$env = static fn (string $key, mixed $default = null): mixed => $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;

return [

    'default' => $env('DB_CONNECTION', 'sqlite'),

    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => $env('DB_DATABASE', dirname(__DIR__) . '/database/database.sqlite'),
            'prefix'   => '',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => $env('DB_HOST', '127.0.0.1'),
            'port'      => $env('DB_PORT', '3306'),
            'database'  => $env('DB_DATABASE', 'intisari'),
            'username'  => $env('DB_USERNAME', 'root'),
            'password'  => $env('DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => $env('DB_HOST', '127.0.0.1'),
            'port'     => $env('DB_PORT', '5432'),
            'database' => $env('DB_DATABASE', 'intisari'),
            'username' => $env('DB_USERNAME', 'postgres'),
            'password' => $env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

    ],

];
