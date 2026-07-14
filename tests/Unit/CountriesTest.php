<?php

use Webpatser\Countries\Countries;

beforeEach(function () {
    $this->countries = new Countries();
});

it('can get all countries', function () {
    $list = $this->countries->getList();

    expect($list)->toBeArray()->not->toBeEmpty();
});

it('can get a country by ISO 3166-2 code', function () {
    $country = $this->countries->getOne('NL');

    expect($country)
        ->not->toBeNull()
        ->and($country['name'])->toBe('Netherlands')
        ->and($country['iso_3166_2'])->toBe('NL')
        ->and($country['iso_3166_3'])->toBe('NLD');
});

it('returns the common name with the official name in full_name', function () {
    $us = $this->countries->getOne('US');
    $gr = $this->countries->getOne('GR');

    expect($us['name'])->toBe('United States')
        ->and($us['full_name'])->toBe('United States of America')
        ->and($gr['name'])->toBe('Greece')
        ->and($gr['full_name'])->toBe('Hellenic Republic');
});

it('has a name and full_name for every country', function () {
    foreach ($this->countries->getList() as $country) {
        expect($country['name'])->toBeString()->not->toBeEmpty()
            ->and($country['full_name'])->toBeString()->not->toBeEmpty();
    }
});

it('can search countries by their official full name', function () {
    $results = $this->countries->search('Hellenic');

    expect($results)->toBeArray()->toHaveCount(1);
    expect(array_values($results)[0]['name'])->toBe('Greece');
});

it('returns null for invalid country code', function () {
    expect($this->countries->getOne('XX'))->toBeNull();
});

it('handles case-insensitive lookups', function () {
    $upper = $this->countries->getOne('NL');
    $lower = $this->countries->getOne('nl');

    expect($upper)->toEqual($lower);
});

it('can get countries by currency', function () {
    $euroCountries = $this->countries->getByCurrency('EUR');

    expect($euroCountries)->toBeArray()->not->toBeEmpty();

    foreach ($euroCountries as $country) {
        expect($country['currency_code'])->toBe('EUR');
    }
});

it('can get countries by region', function () {
    $european = $this->countries->getByRegion('Europe');

    expect($european)->toBeArray()->not->toBeEmpty();

    foreach ($european as $country) {
        expect(strtolower($country['region']))->toBe('europe');
    }
});

it('can search countries by name', function () {
    $results = $this->countries->search('nether');

    expect($results)->toBeArray()->not->toBeEmpty();
    expect(array_values($results)[0]['name'])->toBe('Netherlands');
});

it('can search countries by capital', function () {
    $results = $this->countries->search('amsterdam');

    expect($results)->toBeArray()->not->toBeEmpty();
});

it('can get a sorted list', function () {
    $sorted = $this->countries->getList('name');

    $names = array_column($sorted, 'name');

    expect($names)->toEqual(
        collect($names)->sort(SORT_STRING | SORT_FLAG_CASE)->values()->all()
    );
});

it('can generate select options', function () {
    $options = $this->countries->getListForSelect();

    expect($options)->toBeArray()->not->toBeEmpty();

    foreach ($options as $iso => $name) {
        expect($iso)->toBeString()
            ->and($name)->toBeString();
    }
});

it('can collect countries as a Laravel Collection', function () {
    $collection = $this->countries->collect();

    expect($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class)
        ->and($collection)->not->toBeEmpty();
});
