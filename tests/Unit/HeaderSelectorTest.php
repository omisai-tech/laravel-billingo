<?php

use Omisai\Billingo\HeaderSelector;

describe('HeaderSelector', function () {
    beforeEach(function () {
        $this->headerSelector = new HeaderSelector();
    });

    it('can be instantiated', function () {
        expect($this->headerSelector)->toBeInstanceOf(HeaderSelector::class);
    });

    it('selects json header for json content type', function () {
        $result = $this->headerSelector->selectHeaders(
            accept: ['application/json'],
            contentType: 'application/json',
            isMultipart: false
        );

        expect($result['Content-Type'])->toBe('application/json');
    });

    it('uses provided content type', function () {
        $result = $this->headerSelector->selectHeaders(
            accept: ['application/json'],
            contentType: 'application/xml',
            isMultipart: false
        );

        expect($result['Content-Type'])->toBe('application/xml');
    });

    it('defaults to application/json when content type is empty', function () {
        $result = $this->headerSelector->selectHeaders(
            accept: ['application/json'],
            contentType: '',
            isMultipart: false
        );

        expect($result['Content-Type'])->toBe('application/json');
    });

    it('does not set content type for multipart requests', function () {
        $result = $this->headerSelector->selectHeaders(
            accept: ['application/json'],
            contentType: 'application/json',
            isMultipart: true
        );

        expect($result)->not->toHaveKey('Content-Type');
    });

    it('handles accept headers', function () {
        $result = $this->headerSelector->selectHeaders(
            accept: ['application/json'],
            contentType: 'application/json',
            isMultipart: false
        );

        expect($result)->toHaveKey('Accept');
        expect($result['Accept'])->toBe('application/json');
    });

    it('joins multiple accept headers', function () {
        $result = $this->headerSelector->selectHeaders(
            accept: ['application/json', 'application/xml'],
            contentType: 'application/json',
            isMultipart: false
        );

        expect($result['Accept'])->toContain('application/json');
    });

    it('returns null accept when empty array provided', function () {
        $result = $this->headerSelector->selectHeaders(
            accept: [],
            contentType: 'application/json',
            isMultipart: false
        );

        expect($result)->not->toHaveKey('Accept');
    });
});
