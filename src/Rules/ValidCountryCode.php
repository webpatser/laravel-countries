<?php

namespace Webpatser\Countries\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Webpatser\Countries\Countries;

class ValidCountryCode implements ValidationRule
{
    private string $format;

    public function __construct(string $format = 'iso_3166_2')
    {
        $this->format = $format;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $countries = new Countries();
        $allCountries = $countries->getList();

        $isValid = match ($this->format) {
            'iso_3166_2' => $this->validateIso2($value, $allCountries),
            'iso_3166_3' => $this->validateIso3($value, $allCountries),
            'name' => $this->validateName($value, $allCountries),
            default => false,
        };

        if (!$isValid) {
            $fail("The {$attribute} must be a valid country {$this->format}.");
        }
    }

    private function validateIso2(string $value, array $countries): bool
    {
        return isset($countries[strtoupper($value)]);
    }

    private function validateIso3(string $value, array $countries): bool
    {
        foreach ($countries as $country) {
            if (strcasecmp($country['iso_3166_3'] ?? '', $value) === 0) {
                return true;
            }
        }
        return false;
    }

    private function validateName(string $value, array $countries): bool
    {
        foreach ($countries as $country) {
            if (strcasecmp($country['name'] ?? '', $value) === 0) {
                return true;
            }
        }
        return false;
    }
}