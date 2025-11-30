<?php

use Omisai\Billingo\Models\SpendingSave;
use Omisai\Billingo\Models\SpendingPaymentMethod;
use Omisai\Billingo\Models\Currency;
use Omisai\Billingo\Models\Category;

describe('SpendingSave Model', function () {
    it('can be instantiated with empty data', function () {
        $spending = new SpendingSave();

        expect($spending)->toBeInstanceOf(SpendingSave::class);
    });

    it('can be instantiated with array data', function () {
        $spending = new SpendingSave([
            'partner_id' => 123,
            'category' => Category::STOCK,
            'due_date' => new DateTime('+30 days'),
            'fulfillment_date' => new DateTime('today'),
            'paid_at' => new DateTime('today'),
            'invoice_number' => 'VENDOR-2024-001',
            'invoice_date' => new DateTime('today'),
            'currency' => Currency::HUF,
            'total_gross' => 125000,
            'payment_method' => SpendingPaymentMethod::WIRE_TRANSFER,
        ]);

        expect($spending->getPartnerId())->toBe(123);
        expect($spending->getCategory())->toBe(Category::STOCK);
        expect($spending->getInvoiceNumber())->toBe('VENDOR-2024-001');
        expect($spending->getCurrency())->toBe(Currency::HUF);
        expect($spending->getTotalGross())->toEqual(125000);
        expect($spending->getPaymentMethod())->toBe(SpendingPaymentMethod::WIRE_TRANSFER);
    });

    it('can set and get all properties', function () {
        $spending = new SpendingSave();
        $dueDate = new DateTime('+30 days');
        $fulfillmentDate = new DateTime('today');
        $paidAt = new DateTime('today');
        $invoiceDate = new DateTime('today');

        $spending->setPartnerId(456);
        $spending->setCategory(Category::SERVICE);
        $spending->setDueDate($dueDate);
        $spending->setFulfillmentDate($fulfillmentDate);
        $spending->setPaidAt($paidAt);
        $spending->setInvoiceNumber('INV-2024-002');
        $spending->setInvoiceDate($invoiceDate);
        $spending->setCurrency(Currency::EUR);
        $spending->setTotalGross(500);
        $spending->setPaymentMethod(SpendingPaymentMethod::CASH);
        $spending->setComment('Office supplies');

        expect($spending->getPartnerId())->toBe(456);
        expect($spending->getCategory())->toBe(Category::SERVICE);
        expect($spending->getDueDate())->toBe($dueDate);
        expect($spending->getFulfillmentDate())->toBe($fulfillmentDate);
        expect($spending->getPaidAt())->toBe($paidAt);
        expect($spending->getInvoiceNumber())->toBe('INV-2024-002');
        expect($spending->getInvoiceDate())->toBe($invoiceDate);
        expect($spending->getCurrency())->toBe(Currency::EUR);
        expect($spending->getTotalGross())->toEqual(500);
        expect($spending->getPaymentMethod())->toBe(SpendingPaymentMethod::CASH);
        expect($spending->getComment())->toBe('Office supplies');
    });

    it('can be serialized to JSON', function () {
        $spending = new SpendingSave([
            'partner_id' => 123,
            'invoice_number' => 'TEST-001',
            'currency' => Currency::HUF,
            'total_gross' => 50000,
        ]);

        $json = json_encode($spending);
        $decoded = json_decode($json, true);

        expect($decoded['partner_id'])->toBe(123);
        expect($decoded['invoice_number'])->toBe('TEST-001');
        expect($decoded['currency'])->toBe(Currency::HUF);
        expect($decoded['total_gross'])->toEqual(50000);
    });

    it('can create spending like in README example', function () {
        $spending = new SpendingSave([
            'partner_id' => 123,
            'category' => Category::STOCK,
            'due_date' => new DateTime('+30 days'),
            'fulfillment_date' => new DateTime('today'),
            'paid_at' => new DateTime('today'),
            'invoice_number' => 'VENDOR-2024-001',
            'invoice_date' => new DateTime('today'),
            'currency' => Currency::HUF,
            'total_gross' => 125000,
            'payment_method' => SpendingPaymentMethod::WIRE_TRANSFER,
        ]);

        expect($spending->getPartnerId())->toBe(123);
        expect($spending->getCategory())->toBe(Category::STOCK);
        expect($spending->getInvoiceNumber())->toBe('VENDOR-2024-001');
        expect($spending->getCurrency())->toBe(Currency::HUF);
        expect($spending->getTotalGross())->toEqual(125000);
        expect($spending->getPaymentMethod())->toBe(SpendingPaymentMethod::WIRE_TRANSFER);
    });
});

describe('SpendingPaymentMethod Enum', function () {
    it('has common payment methods', function () {
        expect(SpendingPaymentMethod::CASH)->toBe('cash');
        expect(SpendingPaymentMethod::WIRE_TRANSFER)->toBe('wire_transfer');
        expect(SpendingPaymentMethod::BANKCARD)->toBe('bankcard');
    });

    it('can get all allowable values', function () {
        $values = SpendingPaymentMethod::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('cash');
        expect($values)->toContain('wire_transfer');
    });
});

describe('Category Enum', function () {
    it('has common categories', function () {
        expect(Category::STOCK)->toBe('stock');
        expect(Category::SERVICE)->toBe('service');
        expect(Category::OVERHEADS)->toBe('overheads');
        expect(Category::DEVELOPMENT)->toBe('development');
    });

    it('can get all allowable values', function () {
        $values = Category::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('stock');
        expect($values)->toContain('service');
    });
});
