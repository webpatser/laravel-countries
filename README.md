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
