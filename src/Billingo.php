<?php

namespace Omisai\Billingo;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Omisai\Billingo\Api\BankAccountApi;
use Omisai\Billingo\Api\CurrencyApi;
use Omisai\Billingo\Api\DocumentApi;
use Omisai\Billingo\Api\DocumentBlockApi;
use Omisai\Billingo\Api\DocumentExportApi;
use Omisai\Billingo\Api\OrganizationApi;
use Omisai\Billingo\Api\PartnerApi;
use Omisai\Billingo\Api\ProductApi;
use Omisai\Billingo\Api\SpendingApi;
use Omisai\Billingo\Api\UtilApi;

class Billingo
{
    /**
     * The configuration instance.
     */
    protected Configuration $configuration;

    /**
     * The HTTP client instance.
     */
    protected ClientInterface $client;

    /**
     * The header selector instance.
     */
    protected HeaderSelector $headerSelector;

    /**
     * Cached API instances.
     */
    protected array $apis = [];

    /**
     * Create a new Billingo instance.
     */
    public function __construct(array $config = [])
    {
        $this->configuration = new Configuration();

        // Set the API key from config
        if (!empty($config['api_key'])) {
            $this->configuration->setApiKey('X-API-KEY', $config['api_key']);
        }

        // Set debug mode if configured
        if (isset($config['debug'])) {
            $this->configuration->setDebug($config['debug']);
        }

        // Set custom host if configured
        if (!empty($config['host'])) {
            $this->configuration->setHost($config['host']);
        }

        // Set timeout configuration
        $timeout = $config['timeout'] ?? 30;

        $this->client = new Client([
            'timeout' => $timeout,
            'connect_timeout' => $config['connect_timeout'] ?? 10,
        ]);

        $this->headerSelector = new HeaderSelector();
    }

    /**
     * Get the Configuration instance.
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * Get the HTTP client instance.
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * Get or create an API instance.
     */
    protected function getApi(string $class): object
    {
        if (!isset($this->apis[$class])) {
            $this->apis[$class] = new $class(
                $this->client,
                $this->configuration,
                $this->headerSelector
            );
        }

        return $this->apis[$class];
    }

    /**
     * Get the BankAccount API.
     */
    public function bankAccount(): BankAccountApi
    {
        return $this->getApi(BankAccountApi::class);
    }

    /**
     * Get the Currency API.
     */
    public function currency(): CurrencyApi
    {
        return $this->getApi(CurrencyApi::class);
    }

    /**
     * Get the Document API.
     */
    public function document(): DocumentApi
    {
        return $this->getApi(DocumentApi::class);
    }

    /**
     * Get the DocumentBlock API.
     */
    public function documentBlock(): DocumentBlockApi
    {
        return $this->getApi(DocumentBlockApi::class);
    }

    /**
     * Get the DocumentExport API.
     */
    public function documentExport(): DocumentExportApi
    {
        return $this->getApi(DocumentExportApi::class);
    }

    /**
     * Get the Organization API.
     */
    public function organization(): OrganizationApi
    {
        return $this->getApi(OrganizationApi::class);
    }

    /**
     * Get the Partner API.
     */
    public function partner(): PartnerApi
    {
        return $this->getApi(PartnerApi::class);
    }

    /**
     * Get the Product API.
     */
    public function product(): ProductApi
    {
        return $this->getApi(ProductApi::class);
    }

    /**
     * Get the Spending API.
     */
    public function spending(): SpendingApi
    {
        return $this->getApi(SpendingApi::class);
    }

    /**
     * Get the Util API.
     */
    public function util(): UtilApi
    {
        return $this->getApi(UtilApi::class);
    }
}
