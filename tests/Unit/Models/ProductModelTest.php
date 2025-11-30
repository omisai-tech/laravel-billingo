<?php

use Omisai\Billingo\Models\Product;
use Omisai\Billingo\Models\Currency;
use Omisai\Billingo\Models\Vat;
use Omisai\Billingo\Models\Entitlement;

describe('Product Model', function () {
    it('can be instantiated with empty data', function () {
        $product = new Product();

        expect($product)->toBeInstanceOf(Product::class);
    });

    it('can be instantiated with array data', function () {
        $product = new Product([
            'name' => 'Web Development Service',
            'comment' => 'Hourly rate for development',
            'currency' => Currency::HUF,
            'vat' => Vat::_27,
            'net_unit_price' => 25000,
            'unit' => 'hour',
        ]);

        expect($product->getName())->toBe('Web Development Service');
        expect($product->getComment())->toBe('Hourly rate for development');
        expect($product->getCurrency())->toBe(Currency::HUF);
        expect($product->getVat())->toBe(Vat::_27);
        expect($product->getNetUnitPrice())->toEqual(25000);
        expect($product->getUnit())->toBe('hour');
    });

    it('can set and get all properties', function () {
        $product = new Product();

        $product->setId(456);
        $product->setName('Consulting Service');
        $product->setComment('Professional consulting');
        $product->setCurrency(Currency::EUR);
        $product->setVat(Vat::_27);
        $product->setNetUnitPrice(100);
        $product->setUnit('hour');
        $product->setGeneralLedgerNumber('123456');
        $product->setGeneralLedgerTaxcode('789');

        expect($product->getId())->toBe(456);
        expect($product->getName())->toBe('Consulting Service');
        expect($product->getComment())->toBe('Professional consulting');
        expect($product->getCurrency())->toBe(Currency::EUR);
        expect($product->getVat())->toBe(Vat::_27);
        expect($product->getNetUnitPrice())->toEqual(100);
        expect($product->getUnit())->toBe('hour');
        expect($product->getGeneralLedgerNumber())->toBe('123456');
        expect($product->getGeneralLedgerTaxcode())->toBe('789');
    });

    it('can be serialized to JSON', function () {
        $product = new Product([
            'name' => 'Test Product',
            'currency' => Currency::HUF,
            'vat' => Vat::_27,
            'net_unit_price' => 10000,
            'unit' => 'db',
        ]);

        $json = json_encode($product);
        $decoded = json_decode($json, true);

        expect($decoded['name'])->toBe('Test Product');
        expect($decoded['currency'])->toBe(Currency::HUF);
        expect($decoded['vat'])->toBe(Vat::_27);
    });

    it('implements ArrayAccess', function () {
        $product = new Product(['name' => 'Test']);

        expect($product['name'])->toBe('Test');

        $product['name'] = 'Updated Product';
        expect($product['name'])->toBe('Updated Product');
    });

    it('can create product like in README example', function () {
        $product = new Product([
            'name' => 'Web Development Service',
            'comment' => 'Hourly rate for development',
            'currency' => Currency::HUF,
            'vat' => Vat::_27,
            'net_unit_price' => 25000,
            'unit' => 'hour',
        ]);

        expect($product->getName())->toBe('Web Development Service');
        expect($product->getComment())->toBe('Hourly rate for development');
        expect($product->getCurrency())->toBe(Currency::HUF);
        expect($product->getVat())->toBe(Vat::_27);
        expect($product->getNetUnitPrice())->toEqual(25000);
        expect($product->getUnit())->toBe('hour');
    });
});

describe('Currency Enum', function () {
    it('has common currency codes', function () {
        expect(Currency::HUF)->toBe('HUF');
        expect(Currency::EUR)->toBe('EUR');
        expect(Currency::USD)->toBe('USD');
        expect(Currency::GBP)->toBe('GBP');
        expect(Currency::CHF)->toBe('CHF');
        expect(Currency::CZK)->toBe('CZK');
        expect(Currency::PLN)->toBe('PLN');
        expect(Currency::RON)->toBe('RON');
    });

    it('can get all allowable values', function () {
        $values = Currency::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('HUF');
        expect($values)->toContain('EUR');
        expect($values)->toContain('USD');
    });
});

describe('Vat Enum', function () {
    it('has common VAT rates', function () {
        expect(Vat::_0)->toBe('0%');
        expect(Vat::_5)->toBe('5%');
        expect(Vat::_18)->toBe('18%');
        expect(Vat::_27)->toBe('27%');
    });

    it('has special VAT types', function () {
        expect(Vat::AAM)->toBe('AAM');
        expect(Vat::TAM)->toBe('TAM');
        expect(Vat::EU)->toBe('EU');
        expect(Vat::EUK)->toBe('EUK');
        expect(Vat::MAA)->toBe('MAA');
    });

    it('can get all allowable values', function () {
        $values = Vat::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('27%');
        expect($values)->toContain('0%');
    });
});
