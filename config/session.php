<?php

declare(strict_types=1);

$env = static function (string $key, mixed $default = null): mixed {
    return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
};

return [
    'driver' => $env('SESSION_DRIVER', 'file'),
    'lifetime' => (int) $env('SESSION_LIFETIME', 120),
    'files' => dirname(__DIR__) . '/storage/framework/sessions',
];
