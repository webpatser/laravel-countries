<?php

namespace Webpatser\Countries\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CountryFlag implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value) || empty($value)) {
            return null;
        }

        return mb_strlen($value) === 1 ? $this->convertToFlag($attributes['iso_3166_2'] ?? '') : $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value) || empty($value)) {
            return null;
        }

        return (string) $value;
    }

    private function convertToFlag(string $countryCode): string
    {
        if (strlen($countryCode) !== 2) {
            return '';
        }

        $countryCode = strtoupper($countryCode);
        
        $firstLetter = ord($countryCode[0]) - ord('A') + 0x1F1E6;
        $secondLetter = ord($countryCode[1]) - ord('A') + 0x1F1E6;
        
        return mb_chr($firstLetter) . mb_chr($secondLetter);
    }
}