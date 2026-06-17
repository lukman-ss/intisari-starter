<?php

declare(strict_types=1);

namespace App\Controllers;

use Lukman\Http\Response;

final class StatusController
{
    public function index(): Response
    {
        return Response::json(['status' => 'ok']);
    }
}
