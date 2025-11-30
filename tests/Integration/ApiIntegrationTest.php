<?php

/**
 * Integration tests for Billingo API.
 *
 * These tests make actual API calls and require a valid API key.
 * Run with: ./vendor/bin/pest --group=integration
 * Skip with: ./vendor/bin/pest --exclude-group=integration
 *
 * Set your API key in .env or .env.testing:
 * BILLINGO_API_KEY=your-api-key
 */

use Omisai\Billingo\Facades\Billingo;
use Omisai\Billingo\Models\Partner;
use Omisai\Billingo\Models\Address;
use Omisai\Billingo\Models\Country;
use Omisai\Billingo\Models\PartnerTaxType;
use Omisai\Billingo\Models\Product;
use Omisai\Billingo\Models\Currency;
use Omisai\Billingo\Models\Vat;
use Omisai\Billingo\Models\DocumentInsert;
use Omisai\Billingo\Models\DocumentInsertItemsInner;
use Omisai\Billingo\Models\DocumentInsertType;
use Omisai\Billingo\Models\PaymentMethod;
use Omisai\Billingo\Models\DocumentLanguage;
use Omisai\Billingo\Models\UnitPriceType;
use Omisai\Billingo\Models\SendDocument;
use Omisai\Billingo\Models\PaymentHistory;
use Omisai\Billingo\ApiException;

beforeEach(function () {
    // Skip if no API key is configured
    if (empty(env('BILLINGO_API_KEY')) || env('BILLINGO_API_KEY') === 'your-billingo-api-key') {
        $this->markTestSkipped('BILLINGO_API_KEY not configured. Set it in .env to run integration tests.');
    }
});

describe('Partner API Integration', function () {
    it('can list partners', function () {
        $response = Billingo::partner()->listPartner();

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\PartnerList::class);
        expect($response->getData())->toBeArray();
    });

    it('can create a partner', function () {
        $address = new Address([
            'country_code' => Country::HU,
            'post_code' => '1234',
            'city' => 'Budapest',
            'address' => 'Test Street ' . uniqid(),
        ]);

        $partner = new Partner([
            'name' => 'Test Partner ' . uniqid(),
            'address' => $address,
            'emails' => ['test-' . uniqid() . '@example.com'],
            'taxcode' => '',
            'tax_type' => PartnerTaxType::NO_TAX_NUMBER,
        ]);

        $response = Billingo::partner()->createPartner($partner);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\Partner::class);
        expect($response->getId())->toBeInt();
        expect($response->getId())->toBeGreaterThan(0);
        expect($response->getName())->toBe($partner->getName());
    });

    it('can get a partner by id', function () {
        // First create a partner
        $partner = new Partner([
            'name' => 'Get Test Partner ' . uniqid(),
            'address' => new Address([
                'country_code' => Country::HU,
                'post_code' => '1000',
                'city' => 'Budapest',
                'address' => 'Test Address 1',
            ]),
            'emails' => ['gettest-' . uniqid() . '@example.com'],
            'tax_type' => PartnerTaxType::NO_TAX_NUMBER,
        ]);

        $created = Billingo::partner()->createPartner($partner);
        $partnerId = $created->getId();

        // Now get the partner
        $response = Billingo::partner()->getPartner($partnerId);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\Partner::class);
        expect($response->getId())->toBe($partnerId);
        expect($response->getName())->toBe($partner->getName());
    });

    it('can update a partner', function () {
        // First create a partner
        $partner = new Partner([
            'name' => 'Update Test Partner ' . uniqid(),
            'address' => new Address([
                'country_code' => Country::HU,
                'post_code' => '1000',
                'city' => 'Budapest',
                'address' => 'Original Address',
            ]),
            'emails' => ['update-' . uniqid() . '@example.com'],
            'tax_type' => PartnerTaxType::NO_TAX_NUMBER,
        ]);

        $created = Billingo::partner()->createPartner($partner);
        $partnerId = $created->getId();

        // Update the partner
        $updatedPartner = new Partner([
            'name' => 'Updated Partner Name ' . uniqid(),
            'address' => new Address([
                'country_code' => Country::HU,
                'post_code' => '1000',
                'city' => 'Budapest',
                'address' => 'Updated Address',
            ]),
            'emails' => $created->getEmails(),
            'tax_type' => PartnerTaxType::NO_TAX_NUMBER,
        ]);

        $response = Billingo::partner()->updatePartner($partnerId, $updatedPartner);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\Partner::class);
        expect($response->getName())->toBe($updatedPartner->getName());
    });

    it('can delete a partner', function () {
        // First create a partner to delete
        $partner = new Partner([
            'name' => 'Delete Test Partner ' . uniqid(),
            'address' => new Address([
                'country_code' => Country::HU,
                'post_code' => '1000',
                'city' => 'Budapest',
                'address' => 'Delete Test Address',
            ]),
            'emails' => ['delete-' . uniqid() . '@example.com'],
            'tax_type' => PartnerTaxType::NO_TAX_NUMBER,
        ]);

        $created = Billingo::partner()->createPartner($partner);
        $partnerId = $created->getId();

        // Delete the partner
        Billingo::partner()->deletePartner($partnerId);

        // Verify deletion by trying to get the partner (should throw exception)
        try {
            Billingo::partner()->getPartner($partnerId);
            $this->fail('Expected ApiException was not thrown');
        } catch (ApiException $e) {
            expect($e->getCode())->toBe(404);
        }
    });
});

describe('Product API Integration', function () {
    it('can list products', function () {
        $response = Billingo::product()->listProduct();

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\ProductList::class);
        expect($response->getData())->toBeArray();
    });

    it('can create a product', function () {
        $product = new Product([
            'name' => 'Test Product ' . uniqid(),
            'comment' => 'Created by integration test',
            'currency' => Currency::HUF,
            'vat' => Vat::_27,
            'net_unit_price' => 10000,
            'unit' => 'db',
        ]);

        $response = Billingo::product()->createProduct($product);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\Product::class);
        expect($response->getId())->toBeInt();
        expect($response->getId())->toBeGreaterThan(0);
        expect($response->getName())->toBe($product->getName());
        expect($response->getCurrency())->toBe(Currency::HUF);
        expect($response->getVat())->toBe(Vat::_27);
    });

    it('can get a product by id', function () {
        // First create a product
        $product = new Product([
            'name' => 'Get Test Product ' . uniqid(),
            'currency' => Currency::HUF,
            'vat' => Vat::_27,
            'net_unit_price' => 5000,
            'unit' => 'hour',
        ]);

        $created = Billingo::product()->createProduct($product);
        $productId = $created->getId();

        // Now get the product
        $response = Billingo::product()->getProduct($productId);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\Product::class);
        expect($response->getId())->toBe($productId);
        expect($response->getName())->toBe($product->getName());
    });

    it('can update a product', function () {
        // First create a product
        $product = new Product([
            'name' => 'Update Test Product ' . uniqid(),
            'currency' => Currency::HUF,
            'vat' => Vat::_27,
            'net_unit_price' => 5000,
            'unit' => 'db',
        ]);

        $created = Billingo::product()->createProduct($product);
        $productId = $created->getId();

        // Update the product
        $updatedProduct = new Product([
            'name' => 'Updated Product Name ' . uniqid(),
            'currency' => Currency::HUF,
            'vat' => Vat::_27,
            'net_unit_price' => 7500,
            'unit' => 'db',
        ]);

        $response = Billingo::product()->updateProduct($productId, $updatedProduct);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\Product::class);
        expect($response->getName())->toBe($updatedProduct->getName());
        expect($response->getNetUnitPrice())->toEqual(7500);
    });

    it('can delete a product', function () {
        // First create a product to delete
        $product = new Product([
            'name' => 'Delete Test Product ' . uniqid(),
            'currency' => Currency::HUF,
            'vat' => Vat::_27,
            'net_unit_price' => 1000,
            'unit' => 'db',
        ]);

        $created = Billingo::product()->createProduct($product);
        $productId = $created->getId();

        // Delete the product
        Billingo::product()->deleteProduct($productId);

        // Verify deletion
        try {
            Billingo::product()->getProduct($productId);
            $this->fail('Expected ApiException was not thrown');
        } catch (ApiException $e) {
            expect($e->getCode())->toBe(404);
        }
    });
});

describe('Document Block API Integration', function () {
    it('can list document blocks', function () {
        $response = Billingo::documentBlock()->listDocumentBlock();

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\DocumentBlockList::class);
        expect($response->getData())->toBeArray();
        expect($response->getData())->not->toBeEmpty();
    });

    it('has document blocks with valid structure', function () {
        // Get the list and validate first block's structure
        $list = Billingo::documentBlock()->listDocumentBlock();
        $blocks = $list->getData();

        if (empty($blocks)) {
            $this->markTestSkipped('No document blocks available');
        }

        $block = $blocks[0];

        expect($block)->toBeInstanceOf(\Omisai\Billingo\Models\DocumentBlock::class);
        expect($block->getId())->toBeInt();
        expect($block->getId())->toBeGreaterThan(0);
    });
});

describe('Bank Account API Integration', function () {
    it('can list bank accounts', function () {
        $response = Billingo::bankAccount()->listBankAccount();

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\BankAccountList::class);
        expect($response->getData())->toBeArray();
    });

    it('can get a bank account by id', function () {
        // First get the list to find a valid bank account ID
        $list = Billingo::bankAccount()->listBankAccount();
        $accounts = $list->getData();

        if (empty($accounts)) {
            $this->markTestSkipped('No bank accounts available');
        }

        $accountId = $accounts[0]->getId();

        $response = Billingo::bankAccount()->getBankAccount($accountId);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\BankAccount::class);
        expect($response->getId())->toBe($accountId);
    });
});

describe('Currency API Integration', function () {
    it('can get conversion rate', function () {
        // Get EUR to HUF conversion rate (date is optional, omitting it for simplicity)
        $response = Billingo::currency()->getConversionRate(Currency::EUR, Currency::HUF);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\ConversationRate::class);
        expect($response->getConversationRate())->toBeFloat();
        expect($response->getConversationRate())->toBeGreaterThan(0);
    });
});

describe('Organization API Integration', function () {
    it('can get organization data', function () {
        $response = Billingo::organization()->getOrganizationData();

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\OrganizationData::class);
        expect($response->getTaxCode())->toBeString();
    });
});

describe('Document API Integration', function () {
    it('can list documents', function () {
        $response = Billingo::document()->listDocument();

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\DocumentList::class);
        expect($response->getData())->toBeArray();
    });

    it('can create a draft invoice', function () {
        // First, we need a partner and document block
        $partner = new Partner([
            'name' => 'Invoice Test Partner ' . uniqid(),
            'address' => new Address([
                'country_code' => Country::HU,
                'post_code' => '1000',
                'city' => 'Budapest',
                'address' => 'Invoice Test Address',
            ]),
            'emails' => ['invoice-' . uniqid() . '@example.com'],
            'tax_type' => PartnerTaxType::NO_TAX_NUMBER,
        ]);
        $createdPartner = Billingo::partner()->createPartner($partner);

        // Get document block
        $blocks = Billingo::documentBlock()->listDocumentBlock()->getData();
        if (empty($blocks)) {
            $this->markTestSkipped('No document blocks available');
        }
        $blockId = $blocks[0]->getId();

        // Create draft invoice
        $item = new DocumentInsertItemsInner([
            'name' => 'Test Service',
            'unit_price' => 10000,
            'unit_price_type' => UnitPriceType::GROSS,
            'quantity' => 1,
            'unit' => 'db',
            'vat' => Vat::_27,
        ]);

        $documentInsert = new DocumentInsert([
            'partner_id' => $createdPartner->getId(),
            'block_id' => $blockId,
            'type' => DocumentInsertType::DRAFT,
            'fulfillment_date' => new DateTime('today'),
            'due_date' => new DateTime('+30 days'),
            'payment_method' => PaymentMethod::WIRE_TRANSFER,
            'language' => DocumentLanguage::HU,
            'currency' => Currency::HUF,
            'electronic' => false,
            'items' => [$item],
        ]);

        $response = Billingo::document()->createDocument($documentInsert);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\Document::class);
        expect($response->getId())->toBeInt();
        expect($response->getId())->toBeGreaterThan(0);
    });

    it('can get a document by id', function () {
        // First create a draft
        $partner = new Partner([
            'name' => 'Get Doc Partner ' . uniqid(),
            'address' => new Address([
                'country_code' => Country::HU,
                'post_code' => '1000',
                'city' => 'Budapest',
                'address' => 'Get Doc Address',
            ]),
            'emails' => ['getdoc-' . uniqid() . '@example.com'],
            'tax_type' => PartnerTaxType::NO_TAX_NUMBER,
        ]);
        $createdPartner = Billingo::partner()->createPartner($partner);

        $blocks = Billingo::documentBlock()->listDocumentBlock()->getData();
        $blockId = $blocks[0]->getId();

        $item = new DocumentInsertItemsInner([
            'name' => 'Get Test Service',
            'unit_price' => 5000,
            'unit_price_type' => UnitPriceType::GROSS,
            'quantity' => 2,
            'unit' => 'db',
            'vat' => Vat::_27,
        ]);

        $documentInsert = new DocumentInsert([
            'partner_id' => $createdPartner->getId(),
            'block_id' => $blockId,
            'type' => DocumentInsertType::DRAFT,
            'fulfillment_date' => new DateTime('today'),
            'due_date' => new DateTime('+14 days'),
            'payment_method' => PaymentMethod::CASH,
            'language' => DocumentLanguage::HU,
            'currency' => Currency::HUF,
            'electronic' => false,
            'items' => [$item],
        ]);

        $created = Billingo::document()->createDocument($documentInsert);
        $documentId = $created->getId();

        // Get the document
        $response = Billingo::document()->getDocument($documentId);

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\Document::class);
        expect($response->getId())->toBe($documentId);
    });

    it('can delete a draft document', function () {
        // Create a draft to delete
        $partner = new Partner([
            'name' => 'Delete Doc Partner ' . uniqid(),
            'address' => new Address([
                'country_code' => Country::HU,
                'post_code' => '1000',
                'city' => 'Budapest',
                'address' => 'Delete Doc Address',
            ]),
            'emails' => ['deletedoc-' . uniqid() . '@example.com'],
            'tax_type' => PartnerTaxType::NO_TAX_NUMBER,
        ]);
        $createdPartner = Billingo::partner()->createPartner($partner);

        $blocks = Billingo::documentBlock()->listDocumentBlock()->getData();
        $blockId = $blocks[0]->getId();

        $item = new DocumentInsertItemsInner([
            'name' => 'Delete Test Service',
            'unit_price' => 1000,
            'unit_price_type' => UnitPriceType::GROSS,
            'quantity' => 1,
            'unit' => 'db',
            'vat' => Vat::_27,
        ]);

        $documentInsert = new DocumentInsert([
            'partner_id' => $createdPartner->getId(),
            'block_id' => $blockId,
            'type' => DocumentInsertType::DRAFT,
            'fulfillment_date' => new DateTime('today'),
            'due_date' => new DateTime('+7 days'),
            'payment_method' => PaymentMethod::CASH,
            'language' => DocumentLanguage::HU,
            'currency' => Currency::HUF,
            'electronic' => false,
            'items' => [$item],
        ]);

        $created = Billingo::document()->createDocument($documentInsert);
        $documentId = $created->getId();

        // Delete the draft
        Billingo::document()->deleteDocument($documentId);

        // Verify deletion - API may return 403 (forbidden) or 404 (not found) for deleted documents
        try {
            Billingo::document()->getDocument($documentId);
            $this->fail('Expected ApiException was not thrown');
        } catch (ApiException $e) {
            expect($e->getCode())->toBeIn([403, 404]);
        }
    });
});

describe('Util API Integration', function () {
    it('can check tax number', function () {
        // Use a known valid Hungarian tax number format
        $response = Billingo::util()->checkTaxNumber('12345678-1-23');

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\TaxNumber::class);
    });
});

describe('Spending API Integration', function () {
    it('can list spendings', function () {
        $response = Billingo::spending()->spendingList();

        expect($response)->toBeInstanceOf(\Omisai\Billingo\Models\SpendingList::class);
        expect($response->getData())->toBeArray();
    });
});
