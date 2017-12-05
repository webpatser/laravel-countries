<?php

namespace Tests;

use Orchestra\Testbench\TestCase;
use Webpatser\Countries\CountriesServiceProvider;

class BaseCase extends TestCase
{
    /**
     * Add package provider.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [CountriesServiceProvider::class];
    }
}