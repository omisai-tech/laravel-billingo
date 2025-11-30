<?php

namespace Omisai\Billingo\Tests;

use Omisai\Billingo\BillingoServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            BillingoServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Billingo' => \Omisai\Billingo\Facades\Billingo::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('billingo.api_key', env('BILLINGO_API_KEY', 'test-api-key'));
        $app['config']->set('billingo.host', env('BILLINGO_API_HOST', 'https://api.billingo.hu/v3'));
        $app['config']->set('billingo.debug', env('BILLINGO_DEBUG', false));
        $app['config']->set('billingo.timeout', env('BILLINGO_TIMEOUT', 30));
        $app['config']->set('billingo.connect_timeout', env('BILLINGO_CONNECT_TIMEOUT', 10));
    }

    protected function resolveApplicationEnvironmentVariables($app)
    {
        if (property_exists($this, 'loadEnvironmentVariables') && $this->loadEnvironmentVariables === true) {
            $app->useEnvironmentPath(__DIR__ . '/..');
            $app->make('Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables')->bootstrap($app);
        }
    }
}
