<?php

declare(strict_types=1);

namespace App\Controllers;

use Lukman\Http\Response;

final class StatusController
{
    public function index(): Response|string
    {
        $payload = json_encode([
            'status'      => 'ok',
            'app'         => $_ENV['APP_NAME'] ?? $_SERVER['APP_NAME'] ?? 'Intisari App',
            'environment' => $_ENV['APP_ENV']  ?? $_SERVER['APP_ENV']  ?? 'production',
            'php'         => PHP_VERSION,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if (class_exists(Response::class)) {
            return new Response($payload ?: '{}', 200, ['Content-Type' => 'application/json']);
        }

        // Fallback: plain string (framework will wrap it)
        return $payload ?: '{}';
    }
}
