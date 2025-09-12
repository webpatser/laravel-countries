# Laravel Countries

[![Total Downloads](https://img.shields.io/packagist/dt/webpatser/laravel-countries.svg)](https://packagist.org/packages/webpatser/laravel-countries)
[![PHP Version](https://img.shields.io/badge/php-^8.2-blue.svg)](https://packagist.org/packages/webpatser/laravel-countries)
[![Laravel Version](https://img.shields.io/badge/laravel-^11.0%20%7C%20^12.0-red.svg)](https://packagist.org/packages/webpatser/laravel-countries)
[![License](https://img.shields.io/packagist/l/webpatser/laravel-countries.svg)](https://packagist.org/packages/webpatser/laravel-countries)

A comprehensive Laravel package for handling countries data with all 249 countries, flags, currencies, regions, and more.

## Installation

Install via Composer:

```bash
composer require webpatser/laravel-countries
```

Run the installation command to set up the database:

```bash
php artisan countries:install
```

## Quick Usage

```php
use Webpatser\Countries\Countries;

$countries = new Countries();
$usa = $countries->getOne('US');
$allCountries = $countries->getList();
$euroCountries = $countries->getByCurrency('EUR');
```

## Features

- 🌍 All 249 countries with complete data
- 🏳️ Flag emojis for every country
- 💰 Currency information and filtering
- 🌏 Regional grouping and filtering
- 🔍 Search and query capabilities
- 📋 Laravel validation rules
- 🎨 Collection and String macros
- 🗄️ Eloquent model support

## Documentation

For complete documentation, examples, and API reference, visit:

**[https://documentation.downsized.nl/laravel-countries](https://documentation.downsized.nl/laravel-countries)**

## License

MIT License
