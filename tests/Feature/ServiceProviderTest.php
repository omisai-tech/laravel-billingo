<?php

use Omisai\Billingo\Billingo;
use Omisai\Billingo\BillingoServiceProvider;

it('registers the service provider', function () {
    expect($this->app->getProviders(BillingoServiceProvider::class))->not->toBeEmpty();
});

it('registers billingo as singleton', function () {
    $instance1 = $this->app->make(Billingo::class);
    $instance2 = $this->app->make(Billingo::class);

    expect($instance1)->toBe($instance2);
});

it('can resolve billingo from container', function () {
    $billingo = $this->app->make(Billingo::class);

    expect($billingo)->toBeInstanceOf(Billingo::class);
});

it('can resolve billingo using alias', function () {
    $billingo = $this->app->make('billingo');

    expect($billingo)->toBeInstanceOf(Billingo::class);
});

it('uses config values from application config', function () {
    $billingo = $this->app->make(Billingo::class);
    $expectedApiKey = env('BILLINGO_API_KEY', 'test-api-key');

    expect($billingo->getConfiguration()->getApiKey('X-API-KEY'))->toBe($expectedApiKey);
});

it('merges package config with application config', function () {
    expect(config('billingo'))->toBeArray();
    expect(config('billingo.api_key'))->toBe(env('BILLINGO_API_KEY', 'test-api-key'));
    expect(config('billingo.timeout'))->toEqual(env('BILLINGO_TIMEOUT', 30));
});
