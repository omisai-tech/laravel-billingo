<?php

use Omisai\Billingo\Models\SendDocument;
use Omisai\Billingo\Models\PaymentHistory;
use Omisai\Billingo\Models\PaymentMethod;
use Omisai\Billingo\Models\DocumentCancellation;
use Omisai\Billingo\Models\InvoiceSettings;

describe('SendDocument Model', function () {
    it('can be instantiated with empty data', function () {
        $sendDocument = new SendDocument();

        expect($sendDocument)->toBeInstanceOf(SendDocument::class);
    });

    it('can be instantiated with emails array', function () {
        $sendDocument = new SendDocument([
            'emails' => ['customer@example.com', 'cc@example.com'],
        ]);

        expect($sendDocument->getEmails())->toBe(['customer@example.com', 'cc@example.com']);
    });

    it('can set and get emails', function () {
        $sendDocument = new SendDocument();
        $sendDocument->setEmails(['test@example.com']);

        expect($sendDocument->getEmails())->toBe(['test@example.com']);
    });

    it('can be serialized to JSON', function () {
        $sendDocument = new SendDocument([
            'emails' => ['customer@example.com'],
        ]);

        $json = json_encode($sendDocument);
        $decoded = json_decode($json, true);

        expect($decoded['emails'])->toBe(['customer@example.com']);
    });

    it('can create send document like in README example', function () {
        $sendDocument = new SendDocument([
            'emails' => ['customer@example.com', 'cc@example.com'],
        ]);

        expect($sendDocument->getEmails())->toHaveCount(2);
        expect($sendDocument->getEmails())->toContain('customer@example.com');
        expect($sendDocument->getEmails())->toContain('cc@example.com');
    });
});

describe('PaymentHistory Model', function () {
    it('can be instantiated with empty data', function () {
        $payment = new PaymentHistory();

        expect($payment)->toBeInstanceOf(PaymentHistory::class);
    });

    it('can be instantiated with payment data', function () {
        $payment = new PaymentHistory([
            'date' => new DateTime('today'),
            'price' => 50000,
            'payment_method' => PaymentMethod::WIRE_TRANSFER,
        ]);

        expect($payment->getPrice())->toEqual(50000);
        expect($payment->getPaymentMethod())->toBe(PaymentMethod::WIRE_TRANSFER);
    });

    it('can set and get all properties', function () {
        $payment = new PaymentHistory();
        $date = new DateTime('2024-01-15');

        $payment->setDate($date);
        $payment->setPrice(75000);
        $payment->setPaymentMethod(PaymentMethod::CASH);

        expect($payment->getDate())->toBe($date);
        expect($payment->getPrice())->toEqual(75000);
        expect($payment->getPaymentMethod())->toBe(PaymentMethod::CASH);
    });

    it('can be serialized to JSON', function () {
        $payment = new PaymentHistory([
            'price' => 50000,
            'payment_method' => PaymentMethod::WIRE_TRANSFER,
        ]);

        $json = json_encode($payment);
        $decoded = json_decode($json, true);

        expect($decoded['price'])->toEqual(50000);
        expect($decoded['payment_method'])->toBe(PaymentMethod::WIRE_TRANSFER);
    });

    it('can create payment history like in README example', function () {
        $payment = new PaymentHistory([
            'date' => new DateTime('today'),
            'price' => 50000,
            'payment_method' => PaymentMethod::WIRE_TRANSFER,
        ]);

        expect($payment->getPrice())->toEqual(50000);
        expect($payment->getPaymentMethod())->toBe(PaymentMethod::WIRE_TRANSFER);
        expect($payment->getDate())->toBeInstanceOf(DateTime::class);
    });
});

describe('DocumentCancellation Model', function () {
    it('can be instantiated with empty data', function () {
        $cancellation = new DocumentCancellation();

        expect($cancellation)->toBeInstanceOf(DocumentCancellation::class);
    });

    it('can set cancellation reason', function () {
        $cancellation = new DocumentCancellation([
            'cancellation_reason' => 'Customer requested cancellation',
        ]);

        expect($cancellation->getCancellationReason())->toBe('Customer requested cancellation');
    });

    it('can be serialized to JSON', function () {
        $cancellation = new DocumentCancellation([
            'cancellation_reason' => 'Test reason',
        ]);

        $json = json_encode($cancellation);
        $decoded = json_decode($json, true);

        expect($decoded['cancellation_reason'])->toBe('Test reason');
    });
});

describe('InvoiceSettings Model', function () {
    it('can be instantiated with empty data', function () {
        $settings = new InvoiceSettings();

        expect($settings)->toBeInstanceOf(InvoiceSettings::class);
    });

    it('can be instantiated with dates', function () {
        $fulfillmentDate = new DateTime('today');
        $dueDate = new DateTime('+30 days');

        $settings = new InvoiceSettings([
            'fulfillment_date' => $fulfillmentDate,
            'due_date' => $dueDate,
        ]);

        expect($settings->getFulfillmentDate())->toBe($fulfillmentDate);
        expect($settings->getDueDate())->toBe($dueDate);
    });

    it('can set and get dates', function () {
        $settings = new InvoiceSettings();
        $fulfillmentDate = new DateTime('2024-01-15');
        $dueDate = new DateTime('2024-02-15');

        $settings->setFulfillmentDate($fulfillmentDate);
        $settings->setDueDate($dueDate);

        expect($settings->getFulfillmentDate())->toBe($fulfillmentDate);
        expect($settings->getDueDate())->toBe($dueDate);
    });

    it('can create invoice settings like in README example', function () {
        $settings = new InvoiceSettings([
            'fulfillment_date' => new DateTime('today'),
            'due_date' => new DateTime('+30 days'),
        ]);

        expect($settings->getFulfillmentDate())->toBeInstanceOf(DateTime::class);
        expect($settings->getDueDate())->toBeInstanceOf(DateTime::class);
    });
});
