<?php

it('returns country name by code', function () {
    expect(country_name('NL'))->toBe('Netherlands');
});

it('returns country flag emoji by code', function () {
    $flag = country_flag('NL');

    expect($flag)->not->toBeNull()->toBeString();
});

it('returns country capital by code', function () {
    expect(country_capital('NL'))->toBe('Amsterdam');
});

it('returns currency info by country code', function () {
    $currency = country_currency('US');

    expect($currency)->toBeArray()
        ->and($currency['code'])->toBe('USD');
});

it('returns country region by code', function () {
    expect(strtolower(country_region('NL')))->toBe('europe');
});

it('checks if a country exists', function () {
    expect(country_exists('NL'))->toBeTrue()
        ->and(country_exists('XX'))->toBeFalse();
});

it('can look up country code from name', function () {
    expect(country_code_from_name('Netherlands'))->toBe('NL');
});

it('can get countries by region via helper', function () {
    $european = countries_by_region('Europe');

    expect($european)->toBeArray()->not->toBeEmpty();
});

it('can search countries via helper', function () {
    $results = countries_search('brazil');

    expect($results)->toBeArray()->not->toBeEmpty();
});

it('can generate select options via helper', function () {
    $options = countries_select_options();

    expect($options)->toBeArray()->not->toBeEmpty();
});

it('converts country code to flag emoji and back', function () {
    $flag = country_code_to_flag('NL');
    $code = flag_to_country_code($flag);

    expect($code)->toBe('NL');
});
