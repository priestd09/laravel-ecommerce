<?php
namespace ANavallaSuiza\Ecommerce;

use ANavallaSuiza\Ecommerce\Product\Models\Product;
use ANavallaSuiza\Ecommerce\Product\Observer\ProductObserver;
use Illuminate\Support\ServiceProvider;

class StoreServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('ans-ecommerce.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations/' => base_path('/database/migrations')
        ], 'migrations');

        // Register Model Events
        Product::observe(new ProductObserver());
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'ans-ecommerce');

        $this->app->bind('ANavallaSuiza\Ecommerce\Product\Models\ProductInterface', function () {
            return $this->app->make(config('ans-ecommerce.product_model'));
        });

        $this->app->bind('ANavallaSuiza\Ecommerce\Product\Builder\ProductBuilderInterface', 'ANavallaSuiza\Ecommerce\Product\Builder\ProductBuilder');

        $this->app->register('Dimsav\Translatable\TranslatableServiceProvider');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'ANavallaSuiza\Ecommerce\Product\Models\ProductInterface',
            'ANavallaSuiza\Ecommerce\Product\Builder\ProductBuilderInterface'
        ];
    }
}
