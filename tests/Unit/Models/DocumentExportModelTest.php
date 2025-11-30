<?php

use Omisai\Billingo\Models\CreateDocumentExport;
use Omisai\Billingo\Models\DocumentExportType;
use Omisai\Billingo\Models\DocumentExportSortBy;
use Omisai\Billingo\Models\DocumentExportQueryType;

describe('CreateDocumentExport Model', function () {
    it('can be instantiated with empty data', function () {
        $export = new CreateDocumentExport();

        expect($export)->toBeInstanceOf(CreateDocumentExport::class);
    });

    it('can be instantiated with export data', function () {
        $startDate = new DateTime('2024-01-01');
        $endDate = new DateTime('2024-12-31');

        $export = new CreateDocumentExport([
            'export_type' => DocumentExportType::SIMPLE_CSV,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sort_by' => DocumentExportSortBy::INVOICE_DATE,
        ]);

        expect($export->getExportType())->toBe(DocumentExportType::SIMPLE_CSV);
        expect($export->getStartDate())->toBe($startDate);
        expect($export->getEndDate())->toBe($endDate);
        expect($export->getSortBy())->toBe(DocumentExportSortBy::INVOICE_DATE);
    });

    it('can set and get all properties', function () {
        $export = new CreateDocumentExport();
        $startDate = new DateTime('2024-01-01');
        $endDate = new DateTime('2024-12-31');

        $export->setExportType(DocumentExportType::SIMPLE_EXCEL);
        $export->setStartDate($startDate);
        $export->setEndDate($endDate);
        $export->setSortBy(DocumentExportSortBy::FULFILLMENT_DATE);
        $export->setQueryType(DocumentExportQueryType::FULFILLMENT_DATE);

        expect($export->getExportType())->toBe(DocumentExportType::SIMPLE_EXCEL);
        expect($export->getStartDate())->toBe($startDate);
        expect($export->getEndDate())->toBe($endDate);
        expect($export->getSortBy())->toBe(DocumentExportSortBy::FULFILLMENT_DATE);
        expect($export->getQueryType())->toBe(DocumentExportQueryType::FULFILLMENT_DATE);
    });

    it('can be serialized to JSON', function () {
        $export = new CreateDocumentExport([
            'export_type' => DocumentExportType::SIMPLE_CSV,
        ]);

        $json = json_encode($export);
        $decoded = json_decode($json, true);

        expect($decoded['export_type'])->toBe(DocumentExportType::SIMPLE_CSV);
    });

    it('can create export like in README example', function () {
        $export = new CreateDocumentExport([
            'export_type' => DocumentExportType::SIMPLE_CSV,
            'start_date' => new DateTime('2024-01-01'),
            'end_date' => new DateTime('2024-12-31'),
            'sort_by' => DocumentExportSortBy::INVOICE_DATE,
        ]);

        expect($export->getExportType())->toBe(DocumentExportType::SIMPLE_CSV);
        expect($export->getStartDate())->toBeInstanceOf(DateTime::class);
        expect($export->getEndDate())->toBeInstanceOf(DateTime::class);
        expect($export->getSortBy())->toBe(DocumentExportSortBy::INVOICE_DATE);
    });
});

describe('DocumentExportType Enum', function () {
    it('has export types', function () {
        expect(DocumentExportType::SIMPLE_CSV)->toBe('simple_csv');
        expect(DocumentExportType::SIMPLE_EXCEL)->toBe('simple_excel');
        expect(DocumentExportType::SIMPLE_EXCEL_ITEMS)->toBe('simple_excel_items');
        expect(DocumentExportType::NAV_XML)->toBe('nav_xml');
    });

    it('can get all allowable values', function () {
        $values = DocumentExportType::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('simple_csv');
        expect($values)->toContain('simple_excel');
    });
});

describe('DocumentExportSortBy Enum', function () {
    it('has sort options', function () {
        expect(DocumentExportSortBy::INVOICE_RAW_NUMBER)->toBe('invoice_raw_number');
        expect(DocumentExportSortBy::FULFILLMENT_DATE)->toBe('fulfillment_date');
        expect(DocumentExportSortBy::INVOICE_DATE)->toBe('invoice_date');
    });

    it('can get all allowable values', function () {
        $values = DocumentExportSortBy::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('invoice_raw_number');
    });
});

describe('DocumentExportQueryType Enum', function () {
    it('has query types', function () {
        expect(DocumentExportQueryType::FULFILLMENT_DATE)->toBe('fulfillment_date');
        expect(DocumentExportQueryType::INVOICE_DATE)->toBe('invoice_date');
    });

    it('can get all allowable values', function () {
        $values = DocumentExportQueryType::getAllowableEnumValues();

        expect($values)->toBeArray();
        expect($values)->toContain('fulfillment_date');
    });
});
