<?php

namespace Webpatser\Countries;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class CountriesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('countries.php')
        ]);
    }

    /**
     * Register everything.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerCountries();
        $this->registerCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerCountries(): void
    {
        $this->app->bind('countries', function () {
            return new Countries;
        });

        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php', 'countries'
        );
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->app->singleton('command.countries.migration', function (Application $app) {
            return new MigrationCommand($app);
        });

        $this->commands('command.countries.migration');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['countries'];
    }
}