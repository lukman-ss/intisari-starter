<?php

declare(strict_types=1);

namespace Tests\Feature;

use Lukman\Http\Request;
use Tests\TestCase;

final class HomePageTest extends TestCase
{
    public function testHealthPageReturnsOk(): void
    {
        $response = $this->createApplication()
            ->test(new Request('GET', '/health'))
            ->assertStatus(200)
            ->assertSee('OK');

        $this->assertSame('OK', $response->content());
    }
}
