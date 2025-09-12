<?php

use Webpatser\Countries\Helpers\CountryHelper;

if (!function_exists('country_name')) {
    function country_name(string $countryCode): ?string
    {
        return CountryHelper::name($countryCode);
    }
}

if (!function_exists('country_flag')) {
    function country_flag(string $countryCode): ?string
    {
        return CountryHelper::flag($countryCode);
    }
}

if (!function_exists('country_capital')) {
    function country_capital(string $countryCode): ?string
    {
        return CountryHelper::capital($countryCode);
    }
}

if (!function_exists('country_currency')) {
    function country_currency(string $countryCode): ?array
    {
        return CountryHelper::currency($countryCode);
    }
}

if (!function_exists('country_region')) {
    function country_region(string $countryCode): ?string
    {
        return CountryHelper::region($countryCode);
    }
}

if (!function_exists('country_languages')) {
    function country_languages(string $countryCode): ?array
    {
        return CountryHelper::languages($countryCode);
    }
}

if (!function_exists('country_calling_code')) {
    function country_calling_code(string $countryCode): ?string
    {
        return CountryHelper::callingCode($countryCode);
    }
}

if (!function_exists('country_formatted')) {
    function country_formatted(string $countryCode, bool $includeFlag = true): ?string
    {
        return CountryHelper::formatted($countryCode, $includeFlag);
    }
}

if (!function_exists('country_exists')) {
    function country_exists(string $countryCode): bool
    {
        return CountryHelper::exists($countryCode);
    }
}

if (!function_exists('country_code_from_name')) {
    function country_code_from_name(string $countryName): ?string
    {
        return CountryHelper::codeFromName($countryName);
    }
}

if (!function_exists('countries_by_region')) {
    function countries_by_region(string $region): array
    {
        return CountryHelper::getByRegion($region);
    }
}

if (!function_exists('countries_by_currency')) {
    function countries_by_currency(string $currencyCode): array
    {
        return CountryHelper::getByCurrency($currencyCode);
    }
}

if (!function_exists('countries_search')) {
    function countries_search(string $query): array
    {
        return CountryHelper::searchByName($query);
    }
}

if (!function_exists('countries_select_options')) {
    function countries_select_options(?string $region = null, bool $includeFlags = true): array
    {
        return CountryHelper::selectOptions($region, $includeFlags);
    }
}

if (!function_exists('flag_to_country_code')) {
    function flag_to_country_code(string $flag): ?string
    {
        return CountryHelper::flagToCountryCode($flag);
    }
}

if (!function_exists('country_code_to_flag')) {
    function country_code_to_flag(string $countryCode): ?string
    {
        return CountryHelper::countryCodeToFlag($countryCode);
    }
}