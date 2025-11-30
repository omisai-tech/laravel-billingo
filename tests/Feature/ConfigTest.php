<?php

it('has required configuration keys', function () {
    expect(config('billingo'))->toHaveKeys([
        'api_key',
        'host',
        'debug',
        'timeout',
        'connect_timeout',
    ]);
});

it('has correct default values from environment', function () {
    // These values come from env or fallback to defaults in TestCase
    expect(config('billingo.api_key'))->toBe(env('BILLINGO_API_KEY', 'test-api-key'));
    expect(config('billingo.host'))->toBe(env('BILLINGO_API_HOST', 'https://api.billingo.hu/v3'));
    expect(config('billingo.debug'))->toBeFalsy();
    expect(config('billingo.timeout'))->toEqual(env('BILLINGO_TIMEOUT', 30));
    expect(config('billingo.connect_timeout'))->toEqual(env('BILLINGO_CONNECT_TIMEOUT', 10));
});

it('can override configuration at runtime', function () {
    config(['billingo.api_key' => 'new-api-key']);

    expect(config('billingo.api_key'))->toBe('new-api-key');
});

it('can set debug mode', function () {
    config(['billingo.debug' => true]);

    expect(config('billingo.debug'))->toBeTrue();
});

it('can set custom timeout', function () {
    config(['billingo.timeout' => 60]);

    expect(config('billingo.timeout'))->toBe(60);
});
