<?php

use Omisai\Billingo\Models\Document;
use Omisai\Billingo\Models\DocumentItem;
use Omisai\Billingo\Models\DocumentList;
use Omisai\Billingo\Models\PartnerList;
use Omisai\Billingo\Models\ProductList;
use Omisai\Billingo\Models\DocumentBlock;
use Omisai\Billingo\Models\DocumentBlockList;
use Omisai\Billingo\Models\BankAccountList;
use Omisai\Billingo\Models\SpendingList;
use Omisai\Billingo\Models\OnlinePayment;
use Omisai\Billingo\Models\DocumentPublicUrl;

describe('Document Response Model', function () {
    it('can be instantiated with document data', function () {
        $document = new Document([
            'id' => 12345,
            'invoice_number' => 'INV-2024-001',
        ]);

        expect($document->getId())->toBe(12345);
        expect($document->getInvoiceNumber())->toBe('INV-2024-001');
    });

    it('can handle document items', function () {
        $item = new DocumentItem([
            'name' => 'Test Item',
            'net_unit_amount' => 10000,
        ]);

        expect($item->getName())->toBe('Test Item');
        expect($item->getNetUnitAmount())->toEqual(10000);
    });
});

describe('List Response Models', function () {
    it('can instantiate DocumentList', function () {
        $list = new DocumentList([
            'data' => [],
            'total' => 0,
        ]);

        expect($list)->toBeInstanceOf(DocumentList::class);
    });

    it('can instantiate PartnerList', function () {
        $list = new PartnerList([
            'data' => [],
            'total' => 0,
        ]);

        expect($list)->toBeInstanceOf(PartnerList::class);
    });

    it('can instantiate ProductList', function () {
        $list = new ProductList([
            'data' => [],
            'total' => 0,
        ]);

        expect($list)->toBeInstanceOf(ProductList::class);
    });

    it('can instantiate DocumentBlockList', function () {
        $list = new DocumentBlockList([
            'data' => [],
        ]);

        expect($list)->toBeInstanceOf(DocumentBlockList::class);
    });

    it('can instantiate BankAccountList', function () {
        $list = new BankAccountList([
            'data' => [],
        ]);

        expect($list)->toBeInstanceOf(BankAccountList::class);
    });

    it('can instantiate SpendingList', function () {
        $list = new SpendingList([
            'data' => [],
        ]);

        expect($list)->toBeInstanceOf(SpendingList::class);
    });
});

describe('DocumentBlock Model', function () {
    it('can be instantiated', function () {
        $block = new DocumentBlock([
            'id' => 12345,
            'name' => 'Default Block',
            'prefix' => 'INV',
        ]);

        expect($block->getId())->toBe(12345);
        expect($block->getName())->toBe('Default Block');
        expect($block->getPrefix())->toBe('INV');
    });
});

describe('OnlinePayment Enum', function () {
    it('has payment provider options', function () {
        expect(OnlinePayment::BARION)->toBe('Barion');
        expect(OnlinePayment::SIMPLE_PAY)->toBe('SimplePay');
        expect(OnlinePayment::NO)->toBe('no');
    });

    it('can get all allowable values', function () {
        $values = OnlinePayment::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('Barion');
        expect($values)->toContain('SimplePay');
    });
});

describe('DocumentPublicUrl Model', function () {
    it('can be instantiated', function () {
        $publicUrl = new DocumentPublicUrl([
            'public_url' => 'https://billingo.hu/public/document/abc123',
        ]);

        expect($publicUrl->getPublicUrl())->toBe('https://billingo.hu/public/document/abc123');
    });
});
