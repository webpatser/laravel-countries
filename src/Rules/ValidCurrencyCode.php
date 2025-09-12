<?php

namespace Webpatser\Countries\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Webpatser\Countries\Countries;

class ValidCurrencyCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $countries = new Countries();
        $allCountries = $countries->getList();

        foreach ($allCountries as $country) {
            if (isset($country['currency_code']) && 
                strcasecmp($country['currency_code'], $value) === 0) {
                return;
            }
        }

        $fail("The {$attribute} must be a valid currency code.");
    }
}