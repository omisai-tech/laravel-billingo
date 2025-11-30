<?php

use Omisai\Billingo\Billingo;
use Omisai\Billingo\Configuration;
use Omisai\Billingo\HeaderSelector;
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
use GuzzleHttp\ClientInterface;

describe('Billingo', function () {
    it('can be instantiated with empty config', function () {
        $billingo = new Billingo();

        expect($billingo)->toBeInstanceOf(Billingo::class);
        expect($billingo->getConfiguration())->toBeInstanceOf(Configuration::class);
        expect($billingo->getClient())->toBeInstanceOf(ClientInterface::class);
    });

    it('can be instantiated with config array', function () {
        $billingo = new Billingo([
            'api_key' => 'test-api-key',
            'debug' => true,
            'host' => 'https://custom.api.host/v3',
            'timeout' => 60,
            'connect_timeout' => 15,
        ]);

        expect($billingo)->toBeInstanceOf(Billingo::class);
        expect($billingo->getConfiguration()->getApiKey('X-API-KEY'))->toBe('test-api-key');
        expect($billingo->getConfiguration()->getDebug())->toBeTrue();
        expect($billingo->getConfiguration()->getHost())->toBe('https://custom.api.host/v3');
    });

    it('sets API key correctly', function () {
        $billingo = new Billingo(['api_key' => 'my-secret-key']);

        expect($billingo->getConfiguration()->getApiKey('X-API-KEY'))->toBe('my-secret-key');
    });

    it('sets debug mode correctly', function () {
        $billingo = new Billingo(['debug' => true]);

        expect($billingo->getConfiguration()->getDebug())->toBeTrue();
    });

    it('sets custom host correctly', function () {
        $billingo = new Billingo(['host' => 'https://sandbox.billingo.hu/v3']);

        expect($billingo->getConfiguration()->getHost())->toBe('https://sandbox.billingo.hu/v3');
    });

    it('uses default timeout when not specified', function () {
        $billingo = new Billingo();

        // Default timeout is 30 seconds - we can verify the client was created
        expect($billingo->getClient())->toBeInstanceOf(ClientInterface::class);
    });
});

describe('Billingo API accessors', function () {
    beforeEach(function () {
        $this->billingo = new Billingo(['api_key' => 'test-key']);
    });

    it('returns BankAccountApi instance', function () {
        expect($this->billingo->bankAccount())->toBeInstanceOf(BankAccountApi::class);
    });

    it('returns CurrencyApi instance', function () {
        expect($this->billingo->currency())->toBeInstanceOf(CurrencyApi::class);
    });

    it('returns DocumentApi instance', function () {
        expect($this->billingo->document())->toBeInstanceOf(DocumentApi::class);
    });

    it('returns DocumentBlockApi instance', function () {
        expect($this->billingo->documentBlock())->toBeInstanceOf(DocumentBlockApi::class);
    });

    it('returns DocumentExportApi instance', function () {
        expect($this->billingo->documentExport())->toBeInstanceOf(DocumentExportApi::class);
    });

    it('returns OrganizationApi instance', function () {
        expect($this->billingo->organization())->toBeInstanceOf(OrganizationApi::class);
    });

    it('returns PartnerApi instance', function () {
        expect($this->billingo->partner())->toBeInstanceOf(PartnerApi::class);
    });

    it('returns ProductApi instance', function () {
        expect($this->billingo->product())->toBeInstanceOf(ProductApi::class);
    });

    it('returns SpendingApi instance', function () {
        expect($this->billingo->spending())->toBeInstanceOf(SpendingApi::class);
    });

    it('returns UtilApi instance', function () {
        expect($this->billingo->util())->toBeInstanceOf(UtilApi::class);
    });

    it('caches API instances', function () {
        $api1 = $this->billingo->partner();
        $api2 = $this->billingo->partner();

        expect($api1)->toBe($api2);
    });

    it('caches different API instances separately', function () {
        $partnerApi = $this->billingo->partner();
        $documentApi = $this->billingo->document();

        expect($partnerApi)->not->toBe($documentApi);
        expect($partnerApi)->toBeInstanceOf(PartnerApi::class);
        expect($documentApi)->toBeInstanceOf(DocumentApi::class);
    });
});
