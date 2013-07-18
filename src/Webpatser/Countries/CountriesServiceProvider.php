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
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['countries'] = $this->app->share(function($app)
		{
			return new Countries;
		});
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