<?php

namespace Webpatser\Countries;

use Illuminate\Support\ServiceProvider;

/**
 * CountryListServiceProvider
 *
 */ 

class CountriesServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
    * Bootstrap the application.
    *
    * @return void
    */
    public function boot()
    {
        // The publication files to publish
        $this->publishes([__DIR__ . '/../../config/config.php' => config_path('countries.php')]);

        // Append the country settings
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php', 'countries'
        );
        /*$this->app['config']['database.connections'] = array_merge(
            $this->app['config']['database.connections'],
            \Config::get('career.library.database.connections')
        );*/
    }
        
    /**
     * Register everything.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCountries();
        $this->registerCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerCountries()
    {
        $this->app->bind('countries', function($app)
        {
            return new Countries();
        });
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.countries.migration', function($app)
        {
            return new MigrationCommand($app);
        });

        $this->commands('command.countries.migration');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('countries');
    }
}
