# Usage Guide

This guide provides comprehensive examples for using the Laravel Billingo package to interact with the Billingo API v3.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Basic Usage](#basic-usage)
- [Using the Facade](#using-the-facade)
- [Using Dependency Injection](#using-dependency-injection)
- [API Reference](#api-reference)
  - [Partners](#partners)
  - [Products](#products)
  - [Documents (Invoices)](#documents-invoices)
  - [Bank Accounts](#bank-accounts)
  - [Document Blocks](#document-blocks)
  - [Currency](#currency)
  - [Organization](#organization)
  - [Spending](#spending)
  - [Document Export](#document-export)
  - [Utilities](#utilities)
- [Error Handling](#error-handling)
- [Working with Models](#working-with-models)
- [Need help?](#need-help)

## Installation

```bash
composer require omisai/laravel-billingo
```

## Configuration

1. Publish the configuration file:

```bash
php artisan vendor:publish --tag="billingo-config"
```

2. Add your API key to your `.env` file:

```env
BILLINGO_API_KEY=your-api-key-here

# Optional settings
BILLINGO_DEBUG=false
BILLINGO_TIMEOUT=30
BILLINGO_CONNECT_TIMEOUT=10
```

## Basic Usage

### Using the Facade

The simplest way to use the package is through the Facade:

```php
use Omisai\Billingo\Facades\Billingo;

// List all partners
$partners = Billingo::partner()->listPartner();

// Get a specific document
$document = Billingo::document()->getDocument(123456);
```

### Using Dependency Injection

You can also inject the `Billingo` service directly into your classes:

```php
use Omisai\Billingo\Billingo;

class InvoiceController extends Controller
{
    public function __construct(
        protected Billingo $billingo
    ) {}

    public function index()
    {
        $documents = $this->billingo->document()->listDocument();

        return view('invoices.index', compact('documents'));
    }
}
```

Or inject specific API classes:

```php
use Omisai\Billingo\Api\DocumentApi;
use Omisai\Billingo\Api\PartnerApi;

class InvoiceService
{
    public function __construct(
        protected DocumentApi $documentApi,
        protected PartnerApi $partnerApi
    ) {}

    public function createInvoiceForPartner(int $partnerId, array $items): Document
    {
        // Your logic here
    }
}
```

## API Reference

### Partners

Partners represent your customers/clients in Billingo.

#### List Partners

```php
use Omisai\Billingo\Facades\Billingo;

// List all partners (paginated)
$partners = Billingo::partner()->listPartner(
    page: 1,
    per_page: 25,
    query: null // Optional search query
);

// Access partner data
foreach ($partners->getData() as $partner) {
    echo $partner->getName();
    echo $partner->getTaxcode();
}
```

#### Get a Partner

```php
$partner = Billingo::partner()->getPartner(123);

echo $partner->getName();
echo $partner->getAddress()->getCity();
```

#### Create a Partner

```php
use Omisai\Billingo\Models\Partner;
use Omisai\Billingo\Models\Address;
use Omisai\Billingo\Models\Country;
use Omisai\Billingo\Models\PartnerTaxType;

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

$createdPartner = Billingo::partner()->createPartner($partner);

echo "Partner created with ID: " . $createdPartner->getId();
```

#### Update a Partner

```php
$partner = Billingo::partner()->getPartner(123);
$partner->setName('Updated Company Name');
$partner->setPhone('+36 1 234 5678');

$updatedPartner = Billingo::partner()->updatePartner(123, $partner);
```

#### Delete a Partner

```php
Billingo::partner()->deletePartner(123);
```

### Products

Products are items you can add to invoices.

#### List Products

```php
$products = Billingo::product()->listProduct(
    page: 1,
    per_page: 25,
    query: 'search term' // Optional
);
```

#### Get a Product

```php
$product = Billingo::product()->getProduct(456);

echo $product->getName();
echo $product->getUnitPrice();
```

#### Create a Product

```php
use Omisai\Billingo\Models\Product;
use Omisai\Billingo\Models\Currency;
use Omisai\Billingo\Models\Vat;
use Omisai\Billingo\Models\UnitPriceType;

$product = new Product([
    'name' => 'Web Development Service',
    'comment' => 'Hourly rate for development',
    'currency' => Currency::HUF,
    'vat' => Vat::_27,
    'unit_price' => 25000,
    'unit_price_type' => UnitPriceType::GROSS,
    'unit' => 'hour',
]);

$createdProduct = Billingo::product()->createProduct($product);
```

#### Update a Product

```php
$product = Billingo::product()->getProduct(456);
$product->setUnitPrice(30000);

$updatedProduct = Billingo::product()->updateProduct(456, $product);
```

#### Delete a Product

```php
Billingo::product()->deleteProduct(456);
```

### Documents (Invoices)

Documents include invoices, proforma invoices, receipts, and more.

#### List Documents

```php
use Omisai\Billingo\Models\DocumentType;
use Omisai\Billingo\Models\PaymentStatus;

$documents = Billingo::document()->listDocument(
    page: 1,
    per_page: 25,
    block_id: 12345, // Required: document block ID
    partner_id: null,
    payment_method: null,
    payment_status: PaymentStatus::PAID,
    start_date: new DateTime('2024-01-01'),
    end_date: new DateTime('2024-12-31'),
    start_number: null,
    end_number: null,
    start_year: null,
    end_year: null,
    type: DocumentType::INVOICE,
    query: null,
    paid_start_date: null,
    paid_end_date: null,
    fulfillment_start_date: null,
    fulfillment_end_date: null,
    last_modified_date: null
);

foreach ($documents->getData() as $document) {
    echo $document->getInvoiceNumber();
    echo $document->getTotalGross();
}
```

#### Get a Document

```php
$document = Billingo::document()->getDocument(789);

echo $document->getInvoiceNumber();
echo $document->getPartner()->getName();
echo $document->getTotalGross();
```

#### Create an Invoice

```php
use Omisai\Billingo\Models\DocumentInsert;
use Omisai\Billingo\Models\DocumentInsertItemsInner;
use Omisai\Billingo\Models\DocumentInsertType;
use Omisai\Billingo\Models\PaymentMethod;
use Omisai\Billingo\Models\DocumentLanguage;
use Omisai\Billingo\Models\Currency;
use Omisai\Billingo\Models\Vat;
use Omisai\Billingo\Models\UnitPriceType;

// Create invoice items
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
    'product_id' => 456, // Use existing product
    'quantity' => 5,
]);

// Create the document
$documentInsert = new DocumentInsert([
    'partner_id' => 123,
    'block_id' => 12345, // Your document block ID
    'bank_account_id' => 67890, // Your bank account ID
    'type' => DocumentInsertType::INVOICE,
    'fulfillment_date' => new DateTime('today'),
    'due_date' => new DateTime('+30 days'),
    'payment_method' => PaymentMethod::BANK_TRANSFER,
    'language' => DocumentLanguage::HU,
    'currency' => Currency::HUF,
    'electronic' => true,
    'paid' => false,
    'items' => [$item1, $item2],
    'comment' => 'Thank you for your business!',
]);

$invoice = Billingo::document()->createDocument($documentInsert);

echo "Invoice created: " . $invoice->getInvoiceNumber();
```

#### Create a Proforma Invoice

```php
$proforma = new DocumentInsert([
    'partner_id' => 123,
    'block_id' => 12345,
    'type' => DocumentInsertType::PROFORMA,
    'fulfillment_date' => new DateTime('today'),
    'due_date' => new DateTime('+14 days'),
    'payment_method' => PaymentMethod::BANK_TRANSFER,
    'language' => DocumentLanguage::HU,
    'currency' => Currency::HUF,
    'electronic' => true,
    'items' => [$item1],
]);

$proformaInvoice = Billingo::document()->createDocument($proforma);
```

#### Convert Proforma to Invoice

```php
use Omisai\Billingo\Models\InvoiceSettings;

$settings = new InvoiceSettings([
    'fulfillment_date' => new DateTime('today'),
    'due_date' => new DateTime('+30 days'),
]);

$invoice = Billingo::document()->createDocumentFromProforma(
    id: $proformaId,
    invoice_settings: $settings
);
```

#### Download Document PDF

```php
// Get PDF as binary content
$pdfContent = Billingo::document()->downloadDocument($documentId);

// Save to file
file_put_contents('invoice.pdf', $pdfContent);

// Or return as response in Laravel
return response($pdfContent)
    ->header('Content-Type', 'application/pdf')
    ->header('Content-Disposition', 'attachment; filename="invoice.pdf"');
```

#### Get Public URL

```php
$publicUrl = Billingo::document()->getPublicUrl($documentId);

echo $publicUrl->getPublicUrl(); // URL valid for limited time
```

#### Send Document via Email

```php
use Omisai\Billingo\Models\SendDocument;

$sendDocument = new SendDocument([
    'emails' => ['customer@example.com', 'cc@example.com'],
]);

Billingo::document()->sendDocument($documentId, $sendDocument);
```

#### Cancel/Storno an Invoice

```php
use Omisai\Billingo\Models\DocumentCancellation;

$cancellation = new DocumentCancellation([
    'cancellation_reason' => 'Customer requested cancellation',
]);

$cancelledDocument = Billingo::document()->cancelDocument($documentId, $cancellation);
```

#### Record Payment

```php
use Omisai\Billingo\Models\PaymentHistory;
use Omisai\Billingo\Models\PaymentMethod;

$payment = new PaymentHistory([
    'date' => new DateTime('today'),
    'price' => 50000,
    'payment_method' => PaymentMethod::BANK_TRANSFER,
]);

$paymentHistory = Billingo::document()->updatePayment($documentId, [$payment]);
```

#### Delete Payment

```php
Billingo::document()->deletePayment($documentId);
```

### Bank Accounts

#### List Bank Accounts

```php
$bankAccounts = Billingo::bankAccount()->listBankAccount(
    page: 1,
    per_page: 25
);

foreach ($bankAccounts->getData() as $account) {
    echo $account->getName();
    echo $account->getAccountNumber();
}
```

#### Get a Bank Account

```php
$bankAccount = Billingo::bankAccount()->getBankAccount(123);
```

#### Create a Bank Account

```php
use Omisai\Billingo\Models\BankAccount;
use Omisai\Billingo\Models\Currency;

$bankAccount = new BankAccount([
    'name' => 'Main Business Account',
    'account_number' => '12345678-12345678-12345678',
    'account_number_iban' => 'HU42 1234 5678 1234 5678 1234 5678',
    'swift' => 'GIBAHUHB',
    'currency' => Currency::HUF,
]);

$createdAccount = Billingo::bankAccount()->createBankAccount($bankAccount);
```

#### Update a Bank Account

```php
$bankAccount->setName('Updated Account Name');
$updatedAccount = Billingo::bankAccount()->updateBankAccount($accountId, $bankAccount);
```

#### Delete a Bank Account

```php
Billingo::bankAccount()->deleteBankAccount($accountId);
```

### Document Blocks

Document blocks are numbering sequences for your invoices.

#### List Document Blocks

```php
use Omisai\Billingo\Models\DocumentBlockType;

$blocks = Billingo::documentBlock()->listDocumentBlock(
    page: 1,
    per_page: 25,
    type: DocumentBlockType::INVOICE // Optional filter
);

foreach ($blocks->getData() as $block) {
    echo $block->getName();
    echo $block->getPrefix();
}
```

#### Get a Document Block

```php
$block = Billingo::documentBlock()->getDocumentBlock(12345);
```

### Currency

#### Get Conversion Rate

```php
use Omisai\Billingo\Models\Currency;

$conversionRate = Billingo::currency()->getConversionRate(
    from: Currency::EUR,
    to: Currency::HUF,
    date: new DateTime('today')
);

echo $conversionRate->getConversionRate(); // e.g., 385.50
```

### Organization

#### Get Organization Data

```php
$organization = Billingo::organization()->getOrganizationData();

echo $organization->getName();
echo $organization->getTaxNumber();
```

### Spending

Spending records are for tracking expenses.

#### List Spendings

```php
$spendings = Billingo::spending()->spendingList(
    page: 1,
    per_page: 25,
    q: 'search query' // Optional
);
```

#### Get a Spending

```php
$spending = Billingo::spending()->spendingShow($spendingId);
```

#### Create a Spending

```php
use Omisai\Billingo\Models\SpendingSave;
use Omisai\Billingo\Models\SpendingPaymentMethod;
use Omisai\Billingo\Models\Currency;
use Omisai\Billingo\Models\Category;

$spending = new SpendingSave([
    'partner_id' => 123,
    'category' => Category::MATERIAL,
    'due_date' => new DateTime('+30 days'),
    'fulfillment_date' => new DateTime('today'),
    'paid_at' => new DateTime('today'),
    'invoice_number' => 'VENDOR-2024-001',
    'invoice_date' => new DateTime('today'),
    'currency' => Currency::HUF,
    'total_gross' => 125000,
    'payment_method' => SpendingPaymentMethod::BANK_TRANSFER,
]);

$createdSpending = Billingo::spending()->spendingSave($spending);
```

### Document Export

#### Create Export

```php
use Omisai\Billingo\Models\CreateDocumentExport;
use Omisai\Billingo\Models\DocumentExportType;
use Omisai\Billingo\Models\DocumentExportSortBy;

$export = new CreateDocumentExport([
    'export_type' => DocumentExportType::ZIP,
    'start_date' => new DateTime('2024-01-01'),
    'end_date' => new DateTime('2024-12-31'),
    'sort_by' => DocumentExportSortBy::INVOICE_NUMBER,
]);

$exportId = Billingo::documentExport()->create($export);
```

#### Check Export Status

```php
$status = Billingo::documentExport()->poll($exportId->getId());

if ($status->getStatus() === 'completed') {
    // Export ready for download
    $downloadUrl = $status->getPath();
}
```

#### Download Export

```php
$content = Billingo::documentExport()->download($exportId->getId());
file_put_contents('export.zip', $content);
```

### Utilities

#### Check Tax Number

```php
$result = Billingo::util()->checkTaxNumber('12345678-1-23');

if ($result->getValid()) {
    echo "Tax number is valid";
    echo "Company: " . $result->getTaxPayerName();
}
```

#### Get Server Time

```php
$serverTime = Billingo::util()->getServerTime();
echo $serverTime->getTime(); // DateTime object
```

## Error Handling

The API throws `ApiException` for errors. Always wrap API calls in try-catch blocks:

```php
use Omisai\Billingo\Facades\Billingo;
use Omisai\Billingo\ApiException;

try {
    $document = Billingo::document()->getDocument(999999);
} catch (ApiException $e) {
    $statusCode = $e->getCode();
    $responseBody = $e->getResponseBody();
    $responseHeaders = $e->getResponseHeaders();

    switch ($statusCode) {
        case 400:
            // Bad Request - validation error
            $errors = json_decode($responseBody, true);
            Log::error('Validation error', $errors);
            break;
        case 401:
            // Unauthorized - invalid API key
            Log::error('Invalid API key');
            break;
        case 404:
            // Not Found
            Log::warning('Document not found');
            break;
        case 429:
            // Too Many Requests - rate limited
            Log::warning('Rate limited, please slow down');
            break;
        case 500:
            // Server Error
            Log::error('Billingo server error');
            break;
        default:
            Log::error('API error: ' . $e->getMessage());
    }
}
```

### Validation Errors

For validation errors (400), the response contains detailed field errors:

```php
try {
    $partner = Billingo::partner()->createPartner($invalidPartner);
} catch (ApiException $e) {
    if ($e->getCode() === 400) {
        $body = json_decode($e->getResponseBody(), true);

        foreach ($body['errors'] as $error) {
            echo "Field: " . $error['field'];
            echo "Message: " . $error['message'];
        }
    }
}
```

## Working with Models

### Creating Models from Arrays

All models can be instantiated with arrays:

```php
use Omisai\Billingo\Models\Partner;
use Omisai\Billingo\Models\Address;

$partner = new Partner([
    'name' => 'Test Company',
    'address' => new Address([
        'country_code' => 'HU',
        'city' => 'Budapest',
        'post_code' => '1234',
        'address' => 'Test Street 1',
    ]),
    'emails' => ['test@example.com'],
]);
```

### Using Setters

```php
$partner = new Partner();
$partner->setName('Test Company');
$partner->setEmails(['test@example.com']);

$address = new Address();
$address->setCity('Budapest');
$address->setPostCode('1234');
$address->setAddress('Test Street 1');
$partner->setAddress($address);
```

### Converting to Array/JSON

```php
// To array
$array = $partner->jsonSerialize();

// To JSON
$json = json_encode($partner);
```

## Real-World Example: Complete Invoice Flow

```php
use Omisai\Billingo\Facades\Billingo;
use Omisai\Billingo\Models\Partner;
use Omisai\Billingo\Models\Address;
use Omisai\Billingo\Models\Country;
use Omisai\Billingo\Models\PartnerTaxType;
use Omisai\Billingo\Models\DocumentInsert;
use Omisai\Billingo\Models\DocumentInsertItemsInner;
use Omisai\Billingo\Models\DocumentInsertType;
use Omisai\Billingo\Models\PaymentMethod;
use Omisai\Billingo\Models\DocumentLanguage;
use Omisai\Billingo\Models\Currency;
use Omisai\Billingo\Models\Vat;
use Omisai\Billingo\Models\UnitPriceType;
use Omisai\Billingo\Models\SendDocument;
use Omisai\Billingo\ApiException;

class InvoiceService
{
    public function createAndSendInvoice(array $customerData, array $items): array
    {
        try {
            // 1. Create or find partner
            $partner = $this->findOrCreatePartner($customerData);

            // 2. Prepare invoice items
            $invoiceItems = $this->prepareItems($items);

            // 3. Create the invoice
            $documentInsert = new DocumentInsert([
                'partner_id' => $partner->getId(),
                'block_id' => config('billingo.default_block_id'),
                'bank_account_id' => config('billingo.default_bank_account_id'),
                'type' => DocumentInsertType::INVOICE,
                'fulfillment_date' => new \DateTime('today'),
                'due_date' => new \DateTime('+30 days'),
                'payment_method' => PaymentMethod::BANK_TRANSFER,
                'language' => DocumentLanguage::HU,
                'currency' => Currency::HUF,
                'electronic' => true,
                'items' => $invoiceItems,
            ]);

            $invoice = Billingo::document()->createDocument($documentInsert);

            // 4. Send via email
            $sendDocument = new SendDocument([
                'emails' => $customerData['emails'],
            ]);
            Billingo::document()->sendDocument($invoice->getId(), $sendDocument);

            // 5. Get public URL for customer
            $publicUrl = Billingo::document()->getPublicUrl($invoice->getId());

            return [
                'success' => true,
                'invoice_number' => $invoice->getInvoiceNumber(),
                'invoice_id' => $invoice->getId(),
                'public_url' => $publicUrl->getPublicUrl(),
            ];

        } catch (ApiException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }

    private function findOrCreatePartner(array $data): Partner
    {
        // Try to find existing partner by tax code
        if (!empty($data['taxcode'])) {
            $partners = Billingo::partner()->listPartner(
                query: $data['taxcode']
            );

            if (count($partners->getData()) > 0) {
                return $partners->getData()[0];
            }
        }

        // Create new partner
        $partner = new Partner([
            'name' => $data['name'],
            'address' => new Address([
                'country_code' => $data['country'] ?? Country::HU,
                'post_code' => $data['post_code'],
                'city' => $data['city'],
                'address' => $data['address'],
            ]),
            'emails' => $data['emails'],
            'taxcode' => $data['taxcode'] ?? null,
            'tax_type' => $data['taxcode']
                ? PartnerTaxType::HAS_TAX_NUMBER
                : PartnerTaxType::NO_TAX_NUMBER,
        ]);

        return Billingo::partner()->createPartner($partner);
    }

    private function prepareItems(array $items): array
    {
        return array_map(function ($item) {
            return new DocumentInsertItemsInner([
                'name' => $item['name'],
                'unit_price' => $item['price'],
                'unit_price_type' => UnitPriceType::GROSS,
                'quantity' => $item['quantity'],
                'unit' => $item['unit'] ?? 'db',
                'vat' => Vat::_27,
                'comment' => $item['comment'] ?? null,
            ]);
        }, $items);
    }
}
```

## Need Help?

- [Billingo API Documentation](https://app.swaggerhub.com/apis/Billingo/Billingo/)
- [Generate API Key](https://app.billingo.hu/n/api/list)
- [GitHub Issues](https://github.com/omisai/laravel-billingo/issues)
