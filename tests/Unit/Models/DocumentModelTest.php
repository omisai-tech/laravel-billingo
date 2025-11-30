<?php

use Omisai\Billingo\Models\DocumentInsert;
use Omisai\Billingo\Models\DocumentInsertItemsInner;
use Omisai\Billingo\Models\DocumentInsertType;
use Omisai\Billingo\Models\PaymentMethod;
use Omisai\Billingo\Models\DocumentLanguage;
use Omisai\Billingo\Models\Currency;
use Omisai\Billingo\Models\Vat;
use Omisai\Billingo\Models\UnitPriceType;
use Omisai\Billingo\Models\Discount;
use Omisai\Billingo\Models\DiscountType;
use Omisai\Billingo\Models\DocumentSettings;

describe('DocumentInsert Model', function () {
    it('can be instantiated with empty data', function () {
        $document = new DocumentInsert();

        expect($document)->toBeInstanceOf(DocumentInsert::class);
    });

    it('can be instantiated with basic data', function () {
        $document = new DocumentInsert([
            'partner_id' => 123,
            'block_id' => 12345,
            'type' => DocumentInsertType::INVOICE,
            'fulfillment_date' => new DateTime('2024-01-15'),
            'due_date' => new DateTime('2024-02-15'),
            'payment_method' => PaymentMethod::WIRE_TRANSFER,
            'language' => DocumentLanguage::HU,
            'currency' => Currency::HUF,
            'electronic' => true,
        ]);

        expect($document->getPartnerId())->toBe(123);
        expect($document->getBlockId())->toBe(12345);
        expect($document->getType())->toBe(DocumentInsertType::INVOICE);
        expect($document->getPaymentMethod())->toBe(PaymentMethod::WIRE_TRANSFER);
        expect($document->getLanguage())->toBe(DocumentLanguage::HU);
        expect($document->getCurrency())->toBe(Currency::HUF);
        expect($document->getElectronic())->toBeTrue();
    });

    it('can set and get all properties', function () {
        $document = new DocumentInsert();

        $document->setVendorId('VENDOR-001');
        $document->setPartnerId(123);
        $document->setBlockId(12345);
        $document->setBankAccountId(67890);
        $document->setType(DocumentInsertType::INVOICE);
        $document->setFulfillmentDate(new DateTime('2024-01-15'));
        $document->setDueDate(new DateTime('2024-02-15'));
        $document->setPaymentMethod(PaymentMethod::CASH);
        $document->setLanguage(DocumentLanguage::EN);
        $document->setCurrency(Currency::EUR);
        $document->setConversionRate(385.5);
        $document->setElectronic(false);
        $document->setPaid(true);
        $document->setComment('Test invoice');
        $document->setInstantPayment(true);

        expect($document->getVendorId())->toBe('VENDOR-001');
        expect($document->getPartnerId())->toBe(123);
        expect($document->getBlockId())->toBe(12345);
        expect($document->getBankAccountId())->toBe(67890);
        expect($document->getType())->toBe(DocumentInsertType::INVOICE);
        expect($document->getPaymentMethod())->toBe(PaymentMethod::CASH);
        expect($document->getLanguage())->toBe(DocumentLanguage::EN);
        expect($document->getCurrency())->toBe(Currency::EUR);
        expect($document->getConversionRate())->toBe(385.5);
        expect($document->getElectronic())->toBeFalse();
        expect($document->getPaid())->toBeTrue();
        expect($document->getComment())->toBe('Test invoice');
        expect($document->getInstantPayment())->toBeTrue();
    });

    it('can add items to document', function () {
        $item1 = new DocumentInsertItemsInner([
            'name' => 'Web Development',
            'unit_price' => 50000,
            'unit_price_type' => UnitPriceType::GROSS,
            'quantity' => 10,
            'unit' => 'hour',
            'vat' => Vat::_27,
        ]);

        $item2 = new DocumentInsertItemsInner([
            'name' => 'Consulting',
            'unit_price' => 30000,
            'unit_price_type' => UnitPriceType::NET,
            'quantity' => 5,
            'unit' => 'hour',
            'vat' => Vat::_27,
        ]);

        $document = new DocumentInsert();
        $document->setItems([$item1, $item2]);

        expect($document->getItems())->toHaveCount(2);
        expect($document->getItems()[0]->getName())->toBe('Web Development');
        expect($document->getItems()[1]->getName())->toBe('Consulting');
    });

    it('can create invoice like in README example', function () {
        $item1 = new DocumentInsertItemsInner([
            'name' => 'Web Development',
            'unit_price' => 50000,
            'unit_price_type' => UnitPriceType::GROSS,
            'quantity' => 10,
            'unit' => 'hour',
            'vat' => Vat::_27,
            'comment' => 'Development work for November 2024',
        ]);

        $item2 = new DocumentInsertItemsInner([
            'product_id' => 456,
            'quantity' => 5,
        ]);

        $documentInsert = new DocumentInsert([
            'partner_id' => 123,
            'block_id' => 12345,
            'bank_account_id' => 67890,
            'type' => DocumentInsertType::INVOICE,
            'fulfillment_date' => new DateTime('today'),
            'due_date' => new DateTime('+30 days'),
            'payment_method' => PaymentMethod::WIRE_TRANSFER,
            'language' => DocumentLanguage::HU,
            'currency' => Currency::HUF,
            'electronic' => true,
            'paid' => false,
            'items' => [$item1, $item2],
            'comment' => 'Thank you for your business!',
        ]);

        expect($documentInsert->getPartnerId())->toBe(123);
        expect($documentInsert->getBlockId())->toBe(12345);
        expect($documentInsert->getBankAccountId())->toBe(67890);
        expect($documentInsert->getType())->toBe(DocumentInsertType::INVOICE);
        expect($documentInsert->getPaymentMethod())->toBe(PaymentMethod::WIRE_TRANSFER);
        expect($documentInsert->getLanguage())->toBe(DocumentLanguage::HU);
        expect($documentInsert->getCurrency())->toBe(Currency::HUF);
        expect($documentInsert->getElectronic())->toBeTrue();
        expect($documentInsert->getPaid())->toBeFalse();
        expect($documentInsert->getItems())->toHaveCount(2);
        expect($documentInsert->getComment())->toBe('Thank you for your business!');
    });

    it('can create proforma invoice', function () {
        $item = new DocumentInsertItemsInner([
            'name' => 'Service',
            'unit_price' => 10000,
            'unit_price_type' => UnitPriceType::GROSS,
            'quantity' => 1,
            'unit' => 'db',
            'vat' => Vat::_27,
        ]);

        $proforma = new DocumentInsert([
            'partner_id' => 123,
            'block_id' => 12345,
            'type' => DocumentInsertType::PROFORMA,
            'fulfillment_date' => new DateTime('today'),
            'due_date' => new DateTime('+14 days'),
            'payment_method' => PaymentMethod::WIRE_TRANSFER,
            'language' => DocumentLanguage::HU,
            'currency' => Currency::HUF,
            'electronic' => true,
            'items' => [$item],
        ]);

        expect($proforma->getType())->toBe(DocumentInsertType::PROFORMA);
    });

    it('can add discount to document', function () {
        $discount = new Discount([
            'type' => DiscountType::PERCENT,
            'value' => 10,
        ]);

        $document = new DocumentInsert();
        $document->setDiscount($discount);

        expect($document->getDiscount())->toBeInstanceOf(Discount::class);
        expect($document->getDiscount()->getType())->toBe(DiscountType::PERCENT);
        expect($document->getDiscount()->getValue())->toEqual(10);
    });

    it('can be serialized to JSON', function () {
        $document = new DocumentInsert([
            'partner_id' => 123,
            'block_id' => 12345,
            'type' => DocumentInsertType::INVOICE,
            'currency' => Currency::HUF,
        ]);

        $json = json_encode($document);
        $decoded = json_decode($json, true);

        expect($decoded['partner_id'])->toBe(123);
        expect($decoded['block_id'])->toBe(12345);
        expect($decoded['type'])->toBe(DocumentInsertType::INVOICE);
        expect($decoded['currency'])->toBe(Currency::HUF);
    });
});

describe('DocumentInsertItemsInner Model', function () {
    it('can be instantiated with name and price', function () {
        $item = new DocumentInsertItemsInner([
            'name' => 'Test Item',
            'unit_price' => 10000,
            'unit_price_type' => UnitPriceType::GROSS,
            'quantity' => 1,
            'unit' => 'db',
            'vat' => Vat::_27,
        ]);

        expect($item->getName())->toBe('Test Item');
        expect($item->getUnitPrice())->toEqual(10000);
        expect($item->getUnitPriceType())->toBe(UnitPriceType::GROSS);
        expect($item->getQuantity())->toEqual(1);
        expect($item->getUnit())->toBe('db');
        expect($item->getVat())->toBe(Vat::_27);
    });

    it('can be instantiated with product_id', function () {
        $item = new DocumentInsertItemsInner([
            'product_id' => 456,
            'quantity' => 5,
        ]);

        expect($item->getProductId())->toBe(456);
        expect($item->getQuantity())->toEqual(5);
    });

    it('can set comment', function () {
        $item = new DocumentInsertItemsInner();
        $item->setName('Service');
        $item->setComment('Additional notes for this item');

        expect($item->getComment())->toBe('Additional notes for this item');
    });
});

describe('DocumentInsertType Enum', function () {
    it('has all document types', function () {
        expect(DocumentInsertType::INVOICE)->toBe('invoice');
        expect(DocumentInsertType::PROFORMA)->toBe('proforma');
        expect(DocumentInsertType::DRAFT)->toBe('draft');
        expect(DocumentInsertType::ADVANCE)->toBe('advance');
    });

    it('can get all allowable values', function () {
        $values = DocumentInsertType::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('invoice');
        expect($values)->toContain('proforma');
    });
});

describe('PaymentMethod Enum', function () {
    it('has common payment methods', function () {
        expect(PaymentMethod::CASH)->toBe('cash');
        expect(PaymentMethod::BANKCARD)->toBe('bankcard');
        expect(PaymentMethod::BARION)->toBe('barion');
        expect(PaymentMethod::PAYPAL)->toBe('paypal');
        expect(PaymentMethod::SZEP_CARD)->toBe('szep_card');
    });

    it('has bank transfer as wire_transfer', function () {
        expect(PaymentMethod::WIRE_TRANSFER)->toBe('wire_transfer');
    });

    it('can get all allowable values', function () {
        $values = PaymentMethod::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('cash');
        expect($values)->toContain('wire_transfer');
    });
});

describe('DocumentLanguage Enum', function () {
    it('has common languages', function () {
        expect(DocumentLanguage::HU)->toBe('hu');
        expect(DocumentLanguage::EN)->toBe('en');
        expect(DocumentLanguage::DE)->toBe('de');
    });

    it('can get all allowable values', function () {
        $values = DocumentLanguage::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('hu');
        expect($values)->toContain('en');
    });
});

describe('UnitPriceType Enum', function () {
    it('has gross and net types', function () {
        expect(UnitPriceType::GROSS)->toBe('gross');
        expect(UnitPriceType::NET)->toBe('net');
    });

    it('can get all allowable values', function () {
        $values = UnitPriceType::getAllowableEnumValues();

        expect($values)->toBe(['gross', 'net']);
    });
});
