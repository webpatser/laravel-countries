<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Webpatser\Countries\CountriesServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            CountriesServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Countries' => \Webpatser\Countries\CountriesFacade::class,
        ];
    }
}
