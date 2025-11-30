<?php

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
use Omisai\Billingo\Models\BankAccount;
use Omisai\Billingo\Models\SpendingSave;
use Omisai\Billingo\Models\SpendingPaymentMethod;
use Omisai\Billingo\Models\Category;
use Omisai\Billingo\Models\SendDocument;
use Omisai\Billingo\Models\PaymentHistory;

describe('README Examples - Partners', function () {
    it('can create a partner with address as shown in README', function () {
        // Create address
        $address = new Address([
            'country_code' => Country::HU,
            'post_code' => '1234',
            'city' => 'Budapest',
            'address' => 'Sample Street 123',
        ]);

        // Create partner
        $partner = new Partner([
            'name' => 'Acme Corporation',
            'address' => $address,
            'emails' => ['billing@acme.com'],
            'taxcode' => '12345678-1-23',
            'tax_type' => PartnerTaxType::HAS_TAX_NUMBER,
        ]);

        // Validate partner data
        expect($partner->getName())->toBe('Acme Corporation');
        expect($partner->getTaxcode())->toBe('12345678-1-23');
        expect($partner->getEmails())->toBe(['billing@acme.com']);
        expect($partner->getTaxType())->toBe(PartnerTaxType::HAS_TAX_NUMBER);

        // Validate address
        expect($partner->getAddress()->getCountryCode())->toBe(Country::HU);
        expect($partner->getAddress()->getPostCode())->toBe('1234');
        expect($partner->getAddress()->getCity())->toBe('Budapest');
        expect($partner->getAddress()->getAddress())->toBe('Sample Street 123');

        // Verify the partner API is available and has createPartner method
        expect(Billingo::partner())->toBeInstanceOf(\Omisai\Billingo\Api\PartnerApi::class);
        expect(method_exists(Billingo::partner(), 'createPartner'))->toBeTrue();
    });

    it('can prepare partner update data', function () {
        $partner = new Partner([
            'name' => 'Updated Company Name',
            'emails' => ['new-email@example.com'],
        ]);

        expect($partner->getName())->toBe('Updated Company Name');
        expect($partner->getEmails())->toBe(['new-email@example.com']);
    });
});

describe('README Examples - Products', function () {
    it('can create a product as shown in README', function () {
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

    it('can create product with different VAT rates', function () {
        $productWithZeroVat = new Product([
            'name' => 'Tax-free item',
            'currency' => Currency::HUF,
            'vat' => Vat::_0,
            'net_unit_price' => 1000,
            'unit' => 'db',
        ]);

        expect($productWithZeroVat->getVat())->toBe(Vat::_0);

        $productWith5Vat = new Product([
            'name' => 'Reduced VAT item',
            'currency' => Currency::HUF,
            'vat' => Vat::_5,
            'net_unit_price' => 1000,
            'unit' => 'db',
        ]);

        expect($productWith5Vat->getVat())->toBe(Vat::_5);
    });
});

describe('README Examples - Documents/Invoices', function () {
    it('can create an invoice with items as shown in README', function () {
        // Inline item
        $item1 = new DocumentInsertItemsInner([
            'name' => 'Web Development',
            'unit_price' => 50000,
            'unit_price_type' => UnitPriceType::GROSS,
            'quantity' => 10,
            'unit' => 'hour',
            'vat' => Vat::_27,
            'comment' => 'Development work for November 2024',
        ]);

        // Product reference item
        $item2 = new DocumentInsertItemsInner([
            'product_id' => 456,
            'quantity' => 5,
        ]);

        // Create invoice
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

        // Validate invoice
        expect($documentInsert->getType())->toBe(DocumentInsertType::INVOICE);
        expect($documentInsert->getItems())->toHaveCount(2);
        expect($documentInsert->getElectronic())->toBeTrue();
        expect($documentInsert->getPaid())->toBeFalse();
        expect($documentInsert->getComment())->toBe('Thank you for your business!');
    });

    it('can create a proforma invoice', function () {
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

    it('can create draft document', function () {
        $draft = new DocumentInsert([
            'partner_id' => 123,
            'block_id' => 12345,
            'type' => DocumentInsertType::DRAFT,
            'fulfillment_date' => new DateTime('today'),
            'due_date' => new DateTime('+30 days'),
            'payment_method' => PaymentMethod::CASH,
            'language' => DocumentLanguage::EN,
            'currency' => Currency::EUR,
        ]);

        expect($draft->getType())->toBe(DocumentInsertType::DRAFT);
        expect($draft->getCurrency())->toBe(Currency::EUR);
    });

    it('can prepare send document request', function () {
        $sendDocument = new SendDocument([
            'emails' => ['customer@example.com', 'cc@example.com'],
        ]);

        expect($sendDocument->getEmails())->toContain('customer@example.com');
        expect($sendDocument->getEmails())->toContain('cc@example.com');
    });

    it('can prepare payment history entry', function () {
        $payment = new PaymentHistory([
            'date' => new DateTime('today'),
            'price' => 50000,
            'payment_method' => PaymentMethod::WIRE_TRANSFER,
        ]);

        expect($payment->getPrice())->toEqual(50000);
        expect($payment->getPaymentMethod())->toBe(PaymentMethod::WIRE_TRANSFER);
    });
});

describe('README Examples - Bank Accounts', function () {
    it('can create a bank account as shown in README', function () {
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

    it('can create EUR bank account', function () {
        $eurAccount = new BankAccount([
            'name' => 'EUR Account',
            'account_number_iban' => 'HU42 1234 5678 1234 5678 1234 5678',
            'swift' => 'GIBAHUHB',
            'currency' => Currency::EUR,
        ]);

        expect($eurAccount->getCurrency())->toBe(Currency::EUR);
    });
});

describe('README Examples - Spending', function () {
    it('can create a spending as shown in README', function () {
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

    it('can create spending with different categories', function () {
        $serviceSpending = new SpendingSave([
            'partner_id' => 123,
            'category' => Category::SERVICE,
            'invoice_number' => 'SVC-001',
            'currency' => Currency::HUF,
            'total_gross' => 50000,
        ]);

        expect($serviceSpending->getCategory())->toBe(Category::SERVICE);

        $overheadSpending = new SpendingSave([
            'partner_id' => 123,
            'category' => Category::OVERHEADS,
            'invoice_number' => 'OVH-001',
            'currency' => Currency::HUF,
            'total_gross' => 20000,
        ]);

        expect($overheadSpending->getCategory())->toBe(Category::OVERHEADS);
    });
});

describe('README Examples - Multi-currency Support', function () {
    it('supports HUF currency', function () {
        $product = new Product([
            'name' => 'HUF Product',
            'currency' => Currency::HUF,
            'net_unit_price' => 10000,
        ]);

        expect($product->getCurrency())->toBe(Currency::HUF);
    });

    it('supports EUR currency', function () {
        $product = new Product([
            'name' => 'EUR Product',
            'currency' => Currency::EUR,
            'net_unit_price' => 50,
        ]);

        expect($product->getCurrency())->toBe(Currency::EUR);
    });

    it('supports USD currency', function () {
        $product = new Product([
            'name' => 'USD Product',
            'currency' => Currency::USD,
            'net_unit_price' => 60,
        ]);

        expect($product->getCurrency())->toBe(Currency::USD);
    });

    it('supports conversion rate for foreign currency invoices', function () {
        $document = new DocumentInsert([
            'partner_id' => 123,
            'block_id' => 12345,
            'type' => DocumentInsertType::INVOICE,
            'currency' => Currency::EUR,
            'conversion_rate' => 385.5,
        ]);

        expect($document->getCurrency())->toBe(Currency::EUR);
        expect($document->getConversionRate())->toBe(385.5);
    });
});

describe('README Examples - Document Languages', function () {
    it('supports Hungarian language', function () {
        $document = new DocumentInsert([
            'partner_id' => 123,
            'block_id' => 12345,
            'type' => DocumentInsertType::INVOICE,
            'language' => DocumentLanguage::HU,
        ]);

        expect($document->getLanguage())->toBe(DocumentLanguage::HU);
    });

    it('supports English language', function () {
        $document = new DocumentInsert([
            'partner_id' => 123,
            'block_id' => 12345,
            'type' => DocumentInsertType::INVOICE,
            'language' => DocumentLanguage::EN,
        ]);

        expect($document->getLanguage())->toBe(DocumentLanguage::EN);
    });

    it('supports German language', function () {
        $document = new DocumentInsert([
            'partner_id' => 123,
            'block_id' => 12345,
            'type' => DocumentInsertType::INVOICE,
            'language' => DocumentLanguage::DE,
        ]);

        expect($document->getLanguage())->toBe(DocumentLanguage::DE);
    });
});
