<?php

use Omisai\Billingo\Billingo;
use Omisai\Billingo\Api\BankAccountApi;
use Omisai\Billingo\Api\CurrencyApi;
use Omisai\Billingo\Api\DocumentApi;
use Omisai\Billingo\Api\DocumentBlockApi;
use Omisai\Billingo\Api\DocumentExportApi;
use Omisai\Billingo\Api\OrganizationApi;
use Omisai\Billingo\Api\PartnerApi;
use Omisai\Billingo\Api\ProductApi;
use Omisai\Billingo\Api\SpendingApi;
use Omisai\Billingo\Api\UtilApi;

it('can resolve BankAccountApi from container', function () {
    $api = $this->app->make(BankAccountApi::class);

    expect($api)->toBeInstanceOf(BankAccountApi::class);
});

it('can resolve CurrencyApi from container', function () {
    $api = $this->app->make(CurrencyApi::class);

    expect($api)->toBeInstanceOf(CurrencyApi::class);
});

it('can resolve DocumentApi from container', function () {
    $api = $this->app->make(DocumentApi::class);

    expect($api)->toBeInstanceOf(DocumentApi::class);
});

it('can resolve DocumentBlockApi from container', function () {
    $api = $this->app->make(DocumentBlockApi::class);

    expect($api)->toBeInstanceOf(DocumentBlockApi::class);
});

it('can resolve DocumentExportApi from container', function () {
    $api = $this->app->make(DocumentExportApi::class);

    expect($api)->toBeInstanceOf(DocumentExportApi::class);
});

it('can resolve OrganizationApi from container', function () {
    $api = $this->app->make(OrganizationApi::class);

    expect($api)->toBeInstanceOf(OrganizationApi::class);
});

it('can resolve PartnerApi from container', function () {
    $api = $this->app->make(PartnerApi::class);

    expect($api)->toBeInstanceOf(PartnerApi::class);
});

it('can resolve ProductApi from container', function () {
    $api = $this->app->make(ProductApi::class);

    expect($api)->toBeInstanceOf(ProductApi::class);
});

it('can resolve SpendingApi from container', function () {
    $api = $this->app->make(SpendingApi::class);

    expect($api)->toBeInstanceOf(SpendingApi::class);
});

it('can resolve UtilApi from container', function () {
    $api = $this->app->make(UtilApi::class);

    expect($api)->toBeInstanceOf(UtilApi::class);
});

it('injects APIs with correct configuration', function () {
    $api = $this->app->make(PartnerApi::class);
    $expectedApiKey = env('BILLINGO_API_KEY', 'test-api-key');

    expect($api->getConfig()->getApiKey('X-API-KEY'))->toBe($expectedApiKey);
});

it('creates new API instances on each resolution', function () {
    $api1 = $this->app->make(PartnerApi::class);
    $api2 = $this->app->make(PartnerApi::class);

    // APIs should be new instances each time (not singletons)
    expect($api1)->not->toBe($api2);
});
