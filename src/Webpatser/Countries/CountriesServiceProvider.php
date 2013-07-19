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
	    $this->app['command.countries.migration'] = $this->app->share(function($app)
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