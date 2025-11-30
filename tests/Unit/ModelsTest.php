<?php

use Omisai\Billingo\Models\Partner;
use Omisai\Billingo\Models\Address;
use Omisai\Billingo\Models\Country;

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

    it('can set and get name', function () {
        $partner = new Partner();
        $partner->setName('Acme Inc');

        expect($partner->getName())->toBe('Acme Inc');
    });

    it('can set and get emails', function () {
        $partner = new Partner();
        $partner->setEmails(['test@example.com', 'billing@example.com']);

        expect($partner->getEmails())->toBe(['test@example.com', 'billing@example.com']);
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
    });

    it('can be serialized to JSON', function () {
        $partner = new Partner([
            'name' => 'Test Company',
            'taxcode' => '12345678-1-23',
        ]);

        $json = json_encode($partner);

        expect($json)->toContain('Test Company');
        expect($json)->toContain('12345678-1-23');
    });

    it('implements ArrayAccess', function () {
        $partner = new Partner(['name' => 'Test']);

        expect($partner['name'])->toBe('Test');

        $partner['name'] = 'Updated';
        expect($partner['name'])->toBe('Updated');
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

        expect($address->getCity())->toBe('Budapest');
        expect($address->getPostCode())->toBe('1234');
        expect($address->getAddress())->toBe('Test Street 1');
    });

    it('can set and get all properties', function () {
        $address = new Address();

        $address->setCountryCode(Country::HU);
        $address->setCity('Debrecen');
        $address->setPostCode('4000');
        $address->setAddress('Main Street 10');

        expect($address->getCountryCode())->toBe(Country::HU);
        expect($address->getCity())->toBe('Debrecen');
        expect($address->getPostCode())->toBe('4000');
        expect($address->getAddress())->toBe('Main Street 10');
    });
});
