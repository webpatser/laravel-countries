<?php

namespace Webpatser\Countries\Traits;

use Webpatser\Countries\Countries;

trait CountrySelectable
{
    public static function getCountrySelectOptions(?string $region = null, bool $includeFlags = true): array
    {
        $countries = new Countries();
        $countryList = $countries->getList();

        if ($region) {
            $countryList = array_filter($countryList, function ($country) use ($region) {
                return stripos($country['region'] ?? '', $region) !== false;
            });
        }

        uasort($countryList, fn($a, $b) => strcasecmp($a['name'], $b['name']));

        $options = [];
        foreach ($countryList as $code => $country) {
            $label = $country['name'];
            if ($includeFlags && !empty($country['flag'])) {
                $label = $country['flag'] . ' ' . $label;
            }
            $options[$code] = $label;
        }

        return $options;
    }

    public static function getRegionSelectOptions(): array
    {
        $countries = new Countries();
        $countryList = $countries->getList();

        $regions = [];
        foreach ($countryList as $country) {
            if (!empty($country['region'])) {
                $regions[$country['region']] = $country['region'];
            }
        }

        asort($regions);
        return $regions;
    }

    public static function getCurrencySelectOptions(): array
    {
        $countries = new Countries();
        $countryList = $countries->getList();

        $currencies = [];
        foreach ($countryList as $country) {
            if (!empty($country['currency_code']) && !empty($country['currency_name'])) {
                $symbol = $country['currency_symbol'] ? ' (' . $country['currency_symbol'] . ')' : '';
                $currencies[$country['currency_code']] = $country['currency_name'] . $symbol;
            }
        }

        asort($currencies);
        return $currencies;
    }

    public static function getLanguageSelectOptions(): array
    {
        $countries = new Countries();
        $countryList = $countries->getList();

        $languages = [];
        foreach ($countryList as $country) {
            if (!empty($country['languages']) && is_array($country['languages'])) {
                foreach ($country['languages'] as $language) {
                    $languages[$language] = $language;
                }
            }
        }

        asort($languages);
        return $languages;
    }
}