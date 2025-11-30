<?php

use Omisai\Billingo\Billingo;
use Omisai\Billingo\Api\PartnerApi;
use Omisai\Billingo\Api\ProductApi;
use Omisai\Billingo\Api\DocumentApi;
use Omisai\Billingo\Api\BankAccountApi;
use Omisai\Billingo\Api\DocumentBlockApi;
use Omisai\Billingo\Api\CurrencyApi;
use Omisai\Billingo\Api\OrganizationApi;
use Omisai\Billingo\Api\SpendingApi;
use Omisai\Billingo\Api\DocumentExportApi;
use Omisai\Billingo\Api\UtilApi;

describe('Billingo API Accessors', function () {
    describe('Partner API', function () {
        it('returns PartnerApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->partner())->toBeInstanceOf(PartnerApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->partner();
            $api2 = $billingo->partner();

            expect($api1)->toBe($api2);
        });
    });

    describe('Product API', function () {
        it('returns ProductApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->product())->toBeInstanceOf(ProductApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->product();
            $api2 = $billingo->product();

            expect($api1)->toBe($api2);
        });
    });

    describe('Document API', function () {
        it('returns DocumentApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->document())->toBeInstanceOf(DocumentApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->document();
            $api2 = $billingo->document();

            expect($api1)->toBe($api2);
        });
    });

    describe('Bank Account API', function () {
        it('returns BankAccountApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->bankAccount())->toBeInstanceOf(BankAccountApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->bankAccount();
            $api2 = $billingo->bankAccount();

            expect($api1)->toBe($api2);
        });
    });

    describe('Document Block API', function () {
        it('returns DocumentBlockApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->documentBlock())->toBeInstanceOf(DocumentBlockApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->documentBlock();
            $api2 = $billingo->documentBlock();

            expect($api1)->toBe($api2);
        });
    });

    describe('Currency API', function () {
        it('returns CurrencyApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->currency())->toBeInstanceOf(CurrencyApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->currency();
            $api2 = $billingo->currency();

            expect($api1)->toBe($api2);
        });
    });

    describe('Organization API', function () {
        it('returns OrganizationApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->organization())->toBeInstanceOf(OrganizationApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->organization();
            $api2 = $billingo->organization();

            expect($api1)->toBe($api2);
        });
    });

    describe('Spending API', function () {
        it('returns SpendingApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->spending())->toBeInstanceOf(SpendingApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->spending();
            $api2 = $billingo->spending();

            expect($api1)->toBe($api2);
        });
    });

    describe('Document Export API', function () {
        it('returns DocumentExportApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->documentExport())->toBeInstanceOf(DocumentExportApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->documentExport();
            $api2 = $billingo->documentExport();

            expect($api1)->toBe($api2);
        });
    });

    describe('Util API', function () {
        it('returns UtilApi instance', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            expect($billingo->util())->toBeInstanceOf(UtilApi::class);
        });

        it('returns same instance on multiple calls', function () {
            $billingo = new Billingo(['api_key' => 'test-api-key']);
            $api1 = $billingo->util();
            $api2 = $billingo->util();

            expect($api1)->toBe($api2);
        });
    });
});

describe('API Configuration', function () {
    it('can be created with minimal config', function () {
        $billingo = new Billingo([
            'api_key' => 'my-api-key',
        ]);

        expect($billingo)->toBeInstanceOf(Billingo::class);
    });

    it('can be created with full config', function () {
        $billingo = new Billingo([
            'api_key' => 'my-api-key',
            'host' => 'https://api.billingo.hu/v3',
            'debug' => true,
            'timeout' => 60,
            'connect_timeout' => 15,
        ]);

        expect($billingo)->toBeInstanceOf(Billingo::class);
    });

    it('exposes getConfiguration method', function () {
        $billingo = new Billingo([
            'api_key' => 'my-api-key',
        ]);

        expect($billingo->getConfiguration())->toBeInstanceOf(\Omisai\Billingo\Configuration::class);
    });

    it('exposes getClient method', function () {
        $billingo = new Billingo([
            'api_key' => 'my-api-key',
        ]);

        expect($billingo->getClient())->toBeInstanceOf(\GuzzleHttp\ClientInterface::class);
    });

    it('can configure API key through configuration', function () {
        $billingo = new Billingo([
            'api_key' => 'test-key-12345',
        ]);

        $config = $billingo->getConfiguration();
        expect($config->getApiKey('X-API-KEY'))->toBe('test-key-12345');
    });

    it('can configure custom host', function () {
        $billingo = new Billingo([
            'api_key' => 'my-api-key',
            'host' => 'https://custom.api.host/v3',
        ]);

        $config = $billingo->getConfiguration();
        expect($config->getHost())->toBe('https://custom.api.host/v3');
    });

    it('can configure debug mode', function () {
        $billingo = new Billingo([
            'api_key' => 'my-api-key',
            'debug' => true,
        ]);

        $config = $billingo->getConfiguration();
        expect($config->getDebug())->toBeTrue();
    });
});
