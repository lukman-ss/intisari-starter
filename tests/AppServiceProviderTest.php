<?php

declare(strict_types=1);

namespace Tests;

use App\Providers\AppServiceProvider;
use Intisari\Application;
use Intisari\ServiceProvider;
use PHPUnit\Framework\TestCase;

final class AppServiceProviderTest extends TestCase
{
    public function testProviderCanBeInstantiatedWithApplication(): void
    {
        $provider = new AppServiceProvider(new Application(dirname(__DIR__)));

        $this->assertInstanceOf(ServiceProvider::class, $provider);
    }

    public function testRegisterCanBeCalled(): void
    {
        $provider = new AppServiceProvider(new Application(dirname(__DIR__)));

        $provider->register();

        $this->assertTrue(true);
    }

    public function testBootCanBeCalled(): void
    {
        $provider = new AppServiceProvider(new Application(dirname(__DIR__)));

        $provider->boot();

        $this->assertTrue(true);
    }
}
