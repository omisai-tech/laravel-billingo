<?php

namespace Omisai\Billingo;

use Illuminate\Support\ServiceProvider;

class BillingoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/billingo.php',
            'billingo'
        );

        // Register the main Billingo service as a singleton
        $this->app->singleton(Billingo::class, function ($app) {
            return new Billingo(config('billingo'));
        });

        // Register convenient alias
        $this->app->alias(Billingo::class, 'billingo');

        // Register individual API classes for dependency injection
        $this->registerApiBindings();
    }

    /**
     * Register API class bindings.
     */
    protected function registerApiBindings(): void
    {
        $apis = [
            Api\BankAccountApi::class,
            Api\CurrencyApi::class,
            Api\DocumentApi::class,
            Api\DocumentBlockApi::class,
            Api\DocumentExportApi::class,
            Api\OrganizationApi::class,
            Api\PartnerApi::class,
            Api\ProductApi::class,
            Api\SpendingApi::class,
            Api\UtilApi::class,
        ];

        foreach ($apis as $apiClass) {
            $this->app->bind($apiClass, function ($app) use ($apiClass) {
                $billingo = $app->make(Billingo::class);
                return new $apiClass(
                    $billingo->getClient(),
                    $billingo->getConfiguration(),
                    new HeaderSelector()
                );
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/billingo.php' => config_path('billingo.php'),
            ], 'billingo-config');
        }
    }
}
