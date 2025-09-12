<?php

namespace Webpatser\Countries;

use Illuminate\Support\Facades\Facade;

/**
 * Countries Facade for Laravel integration
 * 
 * @method static array getList()
 * @method static array|null getOne(string $iso)
 * @method static array getByCurrency(string $currency)
 * @method static array getByRegion(string $region)
 * @method static array search(string $query)
 * 
 * @see \Webpatser\Countries\Countries
 */
class CountriesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'countries';
    }
}