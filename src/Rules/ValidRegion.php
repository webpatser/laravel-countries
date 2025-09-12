<?php

namespace Webpatser\Countries\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Webpatser\Countries\Countries;

class ValidRegion implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $countries = new Countries();
        $allCountries = $countries->getList();

        $regions = [];
        foreach ($allCountries as $country) {
            if (!empty($country['region'])) {
                $regions[] = $country['region'];
            }
        }

        $uniqueRegions = array_unique($regions);

        foreach ($uniqueRegions as $region) {
            if (strcasecmp($region, $value) === 0) {
                return;
            }
        }

        $fail("The {$attribute} must be a valid region.");
    }
}