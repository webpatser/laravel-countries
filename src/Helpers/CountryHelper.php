<?php

namespace Webpatser\Countries\Helpers;

use Webpatser\Countries\Countries;

class CountryHelper
{
    private static ?Countries $countries = null;

    private static function getCountriesInstance(): Countries
    {
        if (self::$countries === null) {
            self::$countries = new Countries();
        }
        return self::$countries;
    }

    public static function name(string $countryCode): ?string
    {
        $country = self::getCountriesInstance()->getOne($countryCode);
        return $country['name'] ?? null;
    }

    public static function flag(string $countryCode): ?string
    {
        $country = self::getCountriesInstance()->getOne($countryCode);
        return $country['flag'] ?? null;
    }

    public static function capital(string $countryCode): ?string
    {
        $country = self::getCountriesInstance()->getOne($countryCode);
        return $country['capital'] ?? null;
    }

    public static function currency(string $countryCode): ?array
    {
        $country = self::getCountriesInstance()->getOne($countryCode);
        if (!$country) {
            return null;
        }

        return [
            'code' => $country['currency_code'] ?? null,
            'name' => $country['currency_name'] ?? null,
            'symbol' => $country['currency_symbol'] ?? null,
        ];
    }

    public static function region(string $countryCode): ?string
    {
        $country = self::getCountriesInstance()->getOne($countryCode);
        return $country['region'] ?? null;
    }

    public static function languages(string $countryCode): ?array
    {
        $country = self::getCountriesInstance()->getOne($countryCode);
        return $country['languages'] ?? null;
    }

    public static function callingCode(string $countryCode): ?string
    {
        $country = self::getCountriesInstance()->getOne($countryCode);
        $code = $country['calling_code'] ?? null;
        return $code ? '+' . ltrim($code, '+') : null;
    }

    public static function formatted(string $countryCode, bool $includeFlag = true): ?string
    {
        $country = self::getCountriesInstance()->getOne($countryCode);
        if (!$country) {
            return null;
        }

        $name = $country['name'];
        if ($includeFlag && !empty($country['flag'])) {
            return $country['flag'] . ' ' . $name;
        }

        return $name;
    }

    public static function exists(string $countryCode): bool
    {
        return self::getCountriesInstance()->getOne($countryCode) !== null;
    }

    public static function codeFromName(string $countryName): ?string
    {
        $countries = self::getCountriesInstance()->getList();
        
        foreach ($countries as $code => $country) {
            if (strcasecmp($country['name'], $countryName) === 0) {
                return $code;
            }
        }

        return null;
    }

    public static function searchByName(string $query): array
    {
        return self::getCountriesInstance()->search($query);
    }

    public static function getByRegion(string $region): array
    {
        return self::getCountriesInstance()->getByRegion($region);
    }

    public static function getByCurrency(string $currencyCode): array
    {
        return self::getCountriesInstance()->getByCurrency($currencyCode);
    }

    public static function all(): array
    {
        return self::getCountriesInstance()->getList();
    }

    public static function selectOptions(?string $region = null, bool $includeFlags = true): array
    {
        $countries = $region ? self::getByRegion($region) : self::all();
        
        uasort($countries, fn($a, $b) => strcasecmp($a['name'], $b['name']));

        $options = [];
        foreach ($countries as $code => $country) {
            $label = $country['name'];
            if ($includeFlags && !empty($country['flag'])) {
                $label = $country['flag'] . ' ' . $label;
            }
            $options[$code] = $label;
        }

        return $options;
    }

    public static function flagToCountryCode(string $flag): ?string
    {
        if (mb_strlen($flag) !== 2) {
            return null;
        }

        $firstCodepoint = mb_ord(mb_substr($flag, 0, 1));
        $secondCodepoint = mb_ord(mb_substr($flag, 1, 1));
        
        if ($firstCodepoint < 0x1F1E6 || $firstCodepoint > 0x1F1FF ||
            $secondCodepoint < 0x1F1E6 || $secondCodepoint > 0x1F1FF) {
            return null;
        }
        
        $firstLetter = chr($firstCodepoint - 0x1F1E6 + ord('A'));
        $secondLetter = chr($secondCodepoint - 0x1F1E6 + ord('A'));
        
        return $firstLetter . $secondLetter;
    }

    public static function countryCodeToFlag(string $countryCode): ?string
    {
        if (strlen($countryCode) !== 2) {
            return null;
        }

        $countryCode = strtoupper($countryCode);
        
        $firstLetter = ord($countryCode[0]) - ord('A') + 0x1F1E6;
        $secondLetter = ord($countryCode[1]) - ord('A') + 0x1F1E6;
        
        return mb_chr($firstLetter) . mb_chr($secondLetter);
    }
}