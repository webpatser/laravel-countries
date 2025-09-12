<?php

namespace Webpatser\Countries\Macros;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Webpatser\Countries\Countries;

class CountryMacros
{
    public static function register(): void
    {
        Collection::macro('byCountry', function (string $countryCode) {
            return $this->where('country_code', strtoupper($countryCode));
        });

        Collection::macro('byRegion', function (string $region) {
            return $this->filter(function ($item) use ($region) {
                $itemRegion = is_array($item) ? ($item['region'] ?? null) : $item->region ?? null;
                return $itemRegion && stripos($itemRegion, $region) !== false;
            });
        });

        Collection::macro('byCurrency', function (string $currencyCode) {
            return $this->filter(function ($item) use ($currencyCode) {
                $itemCurrency = is_array($item) ? ($item['currency_code'] ?? null) : $item->currency_code ?? null;
                return $itemCurrency && strcasecmp($itemCurrency, $currencyCode) === 0;
            });
        });

        Collection::macro('withFlags', function () {
            return $this->map(function ($item) {
                if (is_array($item)) {
                    if (isset($item['name']) && isset($item['flag'])) {
                        $item['display_name'] = $item['flag'] . ' ' . $item['name'];
                    }
                } elseif (is_object($item)) {
                    if (property_exists($item, 'name') && property_exists($item, 'flag')) {
                        $item->display_name = $item->flag . ' ' . $item->name;
                    }
                }
                return $item;
            });
        });

        Collection::macro('countryNames', function () {
            return $this->pluck('name');
        });

        Collection::macro('countryCodes', function () {
            return $this->pluck('iso_3166_2');
        });

        Collection::macro('countryFlags', function () {
            return $this->pluck('flag')->filter();
        });

        Collection::macro('uniqueRegions', function () {
            return $this->pluck('region')->filter()->unique()->sort();
        });

        Collection::macro('uniqueCurrencies', function () {
            return $this->pluck('currency_code')->filter()->unique()->sort();
        });

        Collection::macro('searchCountries', function (string $query) {
            $query = strtolower($query);
            return $this->filter(function ($item) use ($query) {
                $name = is_array($item) ? strtolower($item['name'] ?? '') : strtolower($item->name ?? '');
                $capital = is_array($item) ? strtolower($item['capital'] ?? '') : strtolower($item->capital ?? '');
                $iso2 = is_array($item) ? strtolower($item['iso_3166_2'] ?? '') : strtolower($item->iso_3166_2 ?? '');
                $iso3 = is_array($item) ? strtolower($item['iso_3166_3'] ?? '') : strtolower($item->iso_3166_3 ?? '');
                
                return str_contains($name, $query) || 
                       str_contains($capital, $query) || 
                       str_contains($iso2, $query) || 
                       str_contains($iso3, $query);
            });
        });

        Str::macro('toCountryFlag', function (string $countryCode) {
            if (strlen($countryCode) !== 2) {
                return '';
            }

            $countryCode = strtoupper($countryCode);
            
            $firstLetter = ord($countryCode[0]) - ord('A') + 0x1F1E6;
            $secondLetter = ord($countryCode[1]) - ord('A') + 0x1F1E6;
            
            return mb_chr($firstLetter) . mb_chr($secondLetter);
        });

        Str::macro('fromCountryFlag', function (string $flag) {
            if (mb_strlen($flag) !== 2) {
                return '';
            }

            $firstCodepoint = mb_ord(mb_substr($flag, 0, 1));
            $secondCodepoint = mb_ord(mb_substr($flag, 1, 1));
            
            if ($firstCodepoint < 0x1F1E6 || $firstCodepoint > 0x1F1FF ||
                $secondCodepoint < 0x1F1E6 || $secondCodepoint > 0x1F1FF) {
                return '';
            }
            
            $firstLetter = chr($firstCodepoint - 0x1F1E6 + ord('A'));
            $secondLetter = chr($secondCodepoint - 0x1F1E6 + ord('A'));
            
            return $firstLetter . $secondLetter;
        });

        Str::macro('countryName', function (string $countryCode) {
            $countries = new Countries();
            $country = $countries->getOne($countryCode);
            return $country['name'] ?? '';
        });
    }
}