<?php

namespace Webpatser\Countries;

use Illuminate\Support\Facades\Facade;

/**
 * CountriesFacade
 *
 */ 
class CountriesFacade extends Facade {
 
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'countries'; }
 
}