<?php

use Omisai\Billingo\Models\BankAccount;
use Omisai\Billingo\Models\Currency;

describe('BankAccount Model', function () {
    it('can be instantiated with empty data', function () {
        $bankAccount = new BankAccount();

        expect($bankAccount)->toBeInstanceOf(BankAccount::class);
    });

    it('can be instantiated with array data', function () {
        $bankAccount = new BankAccount([
            'name' => 'Main Business Account',
            'account_number' => '12345678-12345678-12345678',
            'account_number_iban' => 'HU42 1234 5678 1234 5678 1234 5678',
            'swift' => 'GIBAHUHB',
            'currency' => Currency::HUF,
        ]);

        expect($bankAccount->getName())->toBe('Main Business Account');
        expect($bankAccount->getAccountNumber())->toBe('12345678-12345678-12345678');
        expect($bankAccount->getAccountNumberIban())->toBe('HU42 1234 5678 1234 5678 1234 5678');
        expect($bankAccount->getSwift())->toBe('GIBAHUHB');
        expect($bankAccount->getCurrency())->toBe(Currency::HUF);
    });

    it('can set and get all properties', function () {
        $bankAccount = new BankAccount();

        $bankAccount->setId(123);
        $bankAccount->setName('EUR Account');
        $bankAccount->setAccountNumber('12345678-12345678');
        $bankAccount->setAccountNumberIban('HU42123456781234567812345678');
        $bankAccount->setSwift('GIBAHUHB');
        $bankAccount->setCurrency(Currency::EUR);

        expect($bankAccount->getId())->toBe(123);
        expect($bankAccount->getName())->toBe('EUR Account');
        expect($bankAccount->getAccountNumber())->toBe('12345678-12345678');
        expect($bankAccount->getAccountNumberIban())->toBe('HU42123456781234567812345678');
        expect($bankAccount->getSwift())->toBe('GIBAHUHB');
        expect($bankAccount->getCurrency())->toBe(Currency::EUR);
    });

    it('can be serialized to JSON', function () {
        $bankAccount = new BankAccount([
            'name' => 'Test Account',
            'account_number' => '12345678-12345678-12345678',
            'currency' => Currency::HUF,
        ]);

        $json = json_encode($bankAccount);
        $decoded = json_decode($json, true);

        expect($decoded['name'])->toBe('Test Account');
        expect($decoded['account_number'])->toBe('12345678-12345678-12345678');
        expect($decoded['currency'])->toBe(Currency::HUF);
    });

    it('implements ArrayAccess', function () {
        $bankAccount = new BankAccount(['name' => 'Test Account']);

        expect($bankAccount['name'])->toBe('Test Account');

        $bankAccount['name'] = 'Updated Account';
        expect($bankAccount['name'])->toBe('Updated Account');
    });

    it('can create bank account like in README example', function () {
        $bankAccount = new BankAccount([
            'name' => 'Main Business Account',
            'account_number' => '12345678-12345678-12345678',
            'account_number_iban' => 'HU42 1234 5678 1234 5678 1234 5678',
            'swift' => 'GIBAHUHB',
            'currency' => Currency::HUF,
        ]);

        expect($bankAccount->getName())->toBe('Main Business Account');
        expect($bankAccount->getAccountNumber())->toBe('12345678-12345678-12345678');
        expect($bankAccount->getAccountNumberIban())->toBe('HU42 1234 5678 1234 5678 1234 5678');
        expect($bankAccount->getSwift())->toBe('GIBAHUHB');
        expect($bankAccount->getCurrency())->toBe(Currency::HUF);
    });
});
