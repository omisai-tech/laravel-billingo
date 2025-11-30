<?php

use Omisai\Billingo\Configuration;

describe('Configuration', function () {
    beforeEach(function () {
        $this->config = new Configuration();
    });

    it('can be instantiated', function () {
        expect($this->config)->toBeInstanceOf(Configuration::class);
    });

    it('has default host', function () {
        expect($this->config->getHost())->toBe('https://api.billingo.hu/v3');
    });

    it('can set and get API key', function () {
        $this->config->setApiKey('X-API-KEY', 'my-api-key');

        expect($this->config->getApiKey('X-API-KEY'))->toBe('my-api-key');
    });

    it('returns null for non-existent API key', function () {
        expect($this->config->getApiKey('NON_EXISTENT'))->toBeNull();
    });

    it('can set and get API key prefix', function () {
        $this->config->setApiKeyPrefix('X-API-KEY', 'Bearer');

        expect($this->config->getApiKeyPrefix('X-API-KEY'))->toBe('Bearer');
    });

    it('returns null for non-existent API key prefix', function () {
        expect($this->config->getApiKeyPrefix('NON_EXISTENT'))->toBeNull();
    });

    it('can set and get host', function () {
        $this->config->setHost('https://custom.api.host/v3');

        expect($this->config->getHost())->toBe('https://custom.api.host/v3');
    });

    it('can set and get debug mode', function () {
        expect($this->config->getDebug())->toBeFalse();

        $this->config->setDebug(true);

        expect($this->config->getDebug())->toBeTrue();
    });

    it('can set and get access token', function () {
        $this->config->setAccessToken('my-access-token');

        expect($this->config->getAccessToken())->toBe('my-access-token');
    });

    it('can set and get username', function () {
        $this->config->setUsername('testuser');

        expect($this->config->getUsername())->toBe('testuser');
    });

    it('can set and get password', function () {
        $this->config->setPassword('testpassword');

        expect($this->config->getPassword())->toBe('testpassword');
    });

    it('can set and get user agent', function () {
        $this->config->setUserAgent('CustomAgent/1.0');

        expect($this->config->getUserAgent())->toBe('CustomAgent/1.0');
    });

    it('can set and get temp folder path', function () {
        $this->config->setTempFolderPath('/tmp/custom');

        expect($this->config->getTempFolderPath())->toBe('/tmp/custom');
    });

    it('has default temp folder path', function () {
        expect($this->config->getTempFolderPath())->toBe(sys_get_temp_dir());
    });

    it('can set and get debug file', function () {
        $this->config->setDebugFile('/var/log/debug.log');

        expect($this->config->getDebugFile())->toBe('/var/log/debug.log');
    });

    it('can get default configuration', function () {
        $defaultConfig = Configuration::getDefaultConfiguration();

        expect($defaultConfig)->toBeInstanceOf(Configuration::class);
    });

    it('can set default configuration', function () {
        $customConfig = new Configuration();
        $customConfig->setHost('https://custom.host/v3');

        Configuration::setDefaultConfiguration($customConfig);

        expect(Configuration::getDefaultConfiguration()->getHost())->toBe('https://custom.host/v3');

        // Reset to avoid affecting other tests
        Configuration::setDefaultConfiguration(new Configuration());
    });

    it('returns fluent interface on setters', function () {
        $result = $this->config
            ->setApiKey('X-API-KEY', 'key')
            ->setHost('https://test.host')
            ->setDebug(true);

        expect($result)->toBe($this->config);
    });

    it('can set boolean format for query string', function () {
        expect($this->config->getBooleanFormatForQueryString())->toBe(Configuration::BOOLEAN_FORMAT_INT);

        $this->config->setBooleanFormatForQueryString(Configuration::BOOLEAN_FORMAT_STRING);

        expect($this->config->getBooleanFormatForQueryString())->toBe(Configuration::BOOLEAN_FORMAT_STRING);
    });
});

describe('Configuration API key with header', function () {
    it('builds correct API key header value', function () {
        $config = new Configuration();
        $config->setApiKey('X-API-KEY', 'test-key');

        expect($config->getApiKeyWithPrefix('X-API-KEY'))->toBe('test-key');
    });

    it('builds correct API key header value with prefix', function () {
        $config = new Configuration();
        $config->setApiKey('Authorization', 'test-token');
        $config->setApiKeyPrefix('Authorization', 'Bearer');

        expect($config->getApiKeyWithPrefix('Authorization'))->toBe('Bearer test-token');
    });

    it('returns null when API key does not exist', function () {
        $config = new Configuration();

        expect($config->getApiKeyWithPrefix('NON_EXISTENT'))->toBeNull();
    });
});
