# Laravel Countries
Laravel Countries is a bundle for Laravel, providing Almost ISO 3166_2, 3166_3, currency, Capital and more for all countries.


## Installation

Add `webpatser/laravel-countries` to `composer.json`.

    "webpatser/laravel-countries": "dev-master"
    
Run `composer update` to pull down the latest version of Country List.

Edit `app/config/app.php` and add the `provider` and `filter`

    'providers' => array(
        'Webpatser\Countries\CountriesServiceProvider',
    )

Now add the alias.

    'aliases' => array(
        'Countries' => 'Webpatser\Countries\CountriesFacade',
    )

    
## Model

Generate the migration file:

    $ php artisan countries:migration
    
It will generate the <timestamp>_setup_countries_table.php migration and the CountriesSeeder.php files. To make sure the data is seeded insert the following code in the seeds/DatabaseSeeder.php

    //Seed the countries
    $this->call('CountriesSeeder');
    $this->command->info('Seeded the countries!'); 

You may now run it with the artisan migrate command:

    $ php artisan migrate --seed
    
After running this command the filled countries table will be available