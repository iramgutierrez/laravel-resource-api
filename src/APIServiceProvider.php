<?php namespace Iramgutierrez\API;

use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider {

    // [...]

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // [...]

        $this->publishes([
            realpath(__DIR__.'/../config/config.php') => config_path('resource_api.php'),
        ], 'config');

        $this->publishes([
            realpath(__DIR__.'/../database/migrations/') => database_path('/migrations')
        ], 'migrations');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            realpath(__DIR__.'/../config/config.php'), 'resource_api'
        );
    }
}