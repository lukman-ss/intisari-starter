<?php

declare(strict_types=1);

namespace App\Controllers;

final class HomeController
{
    public function index(): string
    {
        if (function_exists('view')) {
            try {
                return view('home');
            } catch (\Throwable) {
            }
        }

        return 'Welcome to IntisariPHP';
    }
}
