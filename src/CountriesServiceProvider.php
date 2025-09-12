<?php

namespace Webpatser\Countries;

use Illuminate\Support\ServiceProvider;
use Webpatser\Countries\Macros\CountryMacros;

/**
 * Modern Laravel Countries Service Provider for Laravel 11/12
 */
class CountriesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/config/countries.php' => config_path('countries.php')
        ], 'countries-config');

        // Merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/config/countries.php', 
            'countries'
        );

        // Register commands if running in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                MigrationCommand::class,
            ]);
        }

        // Register macros
        CountryMacros::register();

        // Load helpers
        if (file_exists(__DIR__ . '/helpers.php')) {
            require_once __DIR__ . '/helpers.php';
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('countries', function ($app) {
            return new Countries();
        });

        $this->app->singleton(Countries::class, function ($app) {
            return $app['countries'];
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return ['countries', Countries::class];
    }
}