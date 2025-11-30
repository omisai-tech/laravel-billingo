<?php

use Omisai\Billingo\Models\Partner;
use Omisai\Billingo\Models\Address;
use Omisai\Billingo\Models\Country;
use Omisai\Billingo\Models\PartnerTaxType;

describe('Partner Model', function () {
    it('can be instantiated with empty data', function () {
        $partner = new Partner();

        expect($partner)->toBeInstanceOf(Partner::class);
    });

    it('can be instantiated with array data', function () {
        $partner = new Partner([
            'name' => 'Test Company',
            'taxcode' => '12345678-1-23',
        ]);

        expect($partner->getName())->toBe('Test Company');
        expect($partner->getTaxcode())->toBe('12345678-1-23');
    });

    it('can set and get all properties', function () {
        $partner = new Partner();

        $partner->setId(123);
        $partner->setName('Acme Corporation');
        $partner->setTaxcode('12345678-1-23');
        $partner->setEmails(['billing@acme.com', 'finance@acme.com']);
        $partner->setPhone('+36 1 234 5678');
        $partner->setIban('HU42123456781234567812345678');
        $partner->setSwift('GIBAHUHB');
        $partner->setAccountNumber('12345678-12345678-12345678');
        $partner->setTaxType(PartnerTaxType::HAS_TAX_NUMBER);

        expect($partner->getId())->toBe(123);
        expect($partner->getName())->toBe('Acme Corporation');
        expect($partner->getTaxcode())->toBe('12345678-1-23');
        expect($partner->getEmails())->toBe(['billing@acme.com', 'finance@acme.com']);
        expect($partner->getPhone())->toBe('+36 1 234 5678');
        expect($partner->getIban())->toBe('HU42123456781234567812345678');
        expect($partner->getSwift())->toBe('GIBAHUHB');
        expect($partner->getAccountNumber())->toBe('12345678-12345678-12345678');
        expect($partner->getTaxType())->toBe(PartnerTaxType::HAS_TAX_NUMBER);
    });

    it('can set and get address', function () {
        $address = new Address([
            'country_code' => Country::HU,
            'city' => 'Budapest',
            'post_code' => '1234',
            'address' => 'Test Street 1',
        ]);

        $partner = new Partner();
        $partner->setAddress($address);

        expect($partner->getAddress())->toBeInstanceOf(Address::class);
        expect($partner->getAddress()->getCity())->toBe('Budapest');
        expect($partner->getAddress()->getPostCode())->toBe('1234');
        expect($partner->getAddress()->getAddress())->toBe('Test Street 1');
    });

    it('can be serialized to JSON', function () {
        $partner = new Partner([
            'name' => 'Test Company',
            'taxcode' => '12345678-1-23',
            'emails' => ['test@example.com'],
        ]);

        $json = json_encode($partner);
        $decoded = json_decode($json, true);

        expect($decoded['name'])->toBe('Test Company');
        expect($decoded['taxcode'])->toBe('12345678-1-23');
        expect($decoded['emails'])->toBe(['test@example.com']);
    });

    it('implements ArrayAccess', function () {
        $partner = new Partner(['name' => 'Test']);

        expect(isset($partner['name']))->toBeTrue();
        expect($partner['name'])->toBe('Test');

        $partner['name'] = 'Updated';
        expect($partner['name'])->toBe('Updated');

        unset($partner['name']);
        expect($partner['name'])->toBeNull();
    });

    it('can create partner with full data like in README example', function () {
        $address = new Address([
            'country_code' => Country::HU,
            'post_code' => '1234',
            'city' => 'Budapest',
            'address' => 'Sample Street 123',
        ]);

        $partner = new Partner([
            'name' => 'Acme Corporation',
            'address' => $address,
            'emails' => ['billing@acme.com'],
            'taxcode' => '12345678-1-23',
            'tax_type' => PartnerTaxType::HAS_TAX_NUMBER,
        ]);

        expect($partner->getName())->toBe('Acme Corporation');
        expect($partner->getAddress()->getCity())->toBe('Budapest');
        expect($partner->getEmails())->toContain('billing@acme.com');
        expect($partner->getTaxcode())->toBe('12345678-1-23');
        expect($partner->getTaxType())->toBe(PartnerTaxType::HAS_TAX_NUMBER);
    });
});

describe('Address Model', function () {
    it('can be instantiated with array data', function () {
        $address = new Address([
            'country_code' => Country::HU,
            'city' => 'Budapest',
            'post_code' => '1234',
            'address' => 'Test Street 1',
        ]);

        expect($address->getCountryCode())->toBe(Country::HU);
        expect($address->getCity())->toBe('Budapest');
        expect($address->getPostCode())->toBe('1234');
        expect($address->getAddress())->toBe('Test Street 1');
    });

    it('can set and get all properties', function () {
        $address = new Address();

        $address->setCountryCode(Country::DE);
        $address->setCity('Berlin');
        $address->setPostCode('10115');
        $address->setAddress('Hauptstraße 1');

        expect($address->getCountryCode())->toBe(Country::DE);
        expect($address->getCity())->toBe('Berlin');
        expect($address->getPostCode())->toBe('10115');
        expect($address->getAddress())->toBe('Hauptstraße 1');
    });

    it('can be serialized to JSON', function () {
        $address = new Address([
            'country_code' => Country::HU,
            'city' => 'Budapest',
            'post_code' => '1234',
            'address' => 'Test Street 1',
        ]);

        $json = json_encode($address);
        $decoded = json_decode($json, true);

        expect($decoded['country_code'])->toBe(Country::HU);
        expect($decoded['city'])->toBe('Budapest');
    });
});

describe('PartnerTaxType Enum', function () {
    it('has expected tax type values', function () {
        expect(PartnerTaxType::HAS_TAX_NUMBER)->toBe('HAS_TAX_NUMBER');
        expect(PartnerTaxType::NO_TAX_NUMBER)->toBe('NO_TAX_NUMBER');
    });

    it('can get all allowable values', function () {
        $values = PartnerTaxType::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain(PartnerTaxType::HAS_TAX_NUMBER);
        expect($values)->toContain(PartnerTaxType::NO_TAX_NUMBER);
    });
});

describe('Country Enum', function () {
    it('has common country codes', function () {
        expect(Country::HU)->toBe('HU');
        expect(Country::DE)->toBe('DE');
        expect(Country::AT)->toBe('AT');
        expect(Country::SK)->toBe('SK');
        expect(Country::RO)->toBe('RO');
    });

    it('can get all allowable values', function () {
        $values = Country::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('HU');
        expect($values)->toContain('DE');
    });
});
