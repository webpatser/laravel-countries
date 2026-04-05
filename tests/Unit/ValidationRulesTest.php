<?php

use Webpatser\Countries\Rules\ValidCountryCode;
use Webpatser\Countries\Rules\ValidCurrencyCode;
use Webpatser\Countries\Rules\ValidRegion;

it('validates a correct ISO 3166-2 country code', function () {
    $rule = new ValidCountryCode('iso_3166_2');
    $failed = false;

    $rule->validate('country', 'NL', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('rejects an invalid ISO 3166-2 country code', function () {
    $rule = new ValidCountryCode('iso_3166_2');
    $failed = false;

    $rule->validate('country', 'XX', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('validates a correct ISO 3166-3 country code', function () {
    $rule = new ValidCountryCode('iso_3166_3');
    $failed = false;

    $rule->validate('country', 'NLD', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('validates a correct country name', function () {
    $rule = new ValidCountryCode('name');
    $failed = false;

    $rule->validate('country', 'Netherlands', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('allows empty values to pass validation', function () {
    $rule = new ValidCountryCode();
    $failed = false;

    $rule->validate('country', '', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});
