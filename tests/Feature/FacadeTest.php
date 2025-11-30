<?php

use Omisai\Billingo\Billingo;
use Omisai\Billingo\Facades\Billingo as BillingoFacade;
use Omisai\Billingo\Api\PartnerApi;
use Omisai\Billingo\Api\DocumentApi;
use Omisai\Billingo\Api\ProductApi;

it('resolves facade to billingo instance', function () {
    $resolved = BillingoFacade::getFacadeRoot();

    expect($resolved)->toBeInstanceOf(Billingo::class);
});

it('can access partner api through facade', function () {
    $partnerApi = BillingoFacade::partner();

    expect($partnerApi)->toBeInstanceOf(PartnerApi::class);
});

it('can access document api through facade', function () {
    $documentApi = BillingoFacade::document();

    expect($documentApi)->toBeInstanceOf(DocumentApi::class);
});

it('can access product api through facade', function () {
    $productApi = BillingoFacade::product();

    expect($productApi)->toBeInstanceOf(ProductApi::class);
});

it('can access configuration through facade', function () {
    $config = BillingoFacade::getConfiguration();
    $expectedApiKey = env('BILLINGO_API_KEY', 'test-api-key');

    expect($config->getApiKey('X-API-KEY'))->toBe($expectedApiKey);
});
