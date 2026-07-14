<?php

use Countries;

it('resolves the facade through the service provider', function () {
    $country = Countries::getOne('US');

    expect($country['name'])->toBe('United States')
        ->and($country['full_name'])->toBe('United States of America')
        ->and($country['iso_3166_3'])->toBe('USA');
});

it('searches by official name through the facade', function () {
    $results = Countries::search('Hellenic');

    expect(array_values($results)[0]['name'])->toBe('Greece');
});
