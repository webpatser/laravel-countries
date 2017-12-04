<?php

namespace Webpatser\Countries;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    /**
     * Array containing countries data.
     *
     * @var array
     */
    protected $countries = [];

    /**
     * Table name - "countries" by default.
     *
     * @var string
     */
    protected $table;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->table = config('countries.table_name');
    }

    /**
     * Get the countries from the JSON file
     * if it hasn't already been loaded.
     *
     * @return array
     */
    protected function getCountries(): array
    {
        if (count($this->countries) === 0) {
            $this->countries = json_decode(file_get_contents(__DIR__.'/Models/countries.json'), true);
        }

        return $this->countries;
    }

    /**
     * Get country by id.
     *
     * @param  int  $id
     * @return array
     */
    public function getOne(int $id): array
    {
        $countries = $this->getCountries();

        return $countries[$id];
    }

    /**
     * Get list of countries.
     *
     * @param  string|null  $sort
     * @return array
     */
    public function getList(string $sort = null): array
    {
        $countries = $this->getCountries();

        $validSorts = [
            'capital',
            'citizenship',
            'country_code',
            'currency',
            'currency_code',
            'currency_sub_unit',
            'full_name',
            'iso_3166_2',
            'iso_3166_3',
            'name',
            'region_code',
            'sub_region_code',
            'eea',
            'calling_code',
            'currency_symbol',
            'flag',
        ];

        if (!is_null($sort) && in_array($sort, $validSorts)) {
            uasort($countries, function ($a, $b) use ($sort) {
                if (!isset($a[$sort], $b[$sort])) {
                    return 0;
                } elseif (!isset($a[$sort])) {
                    return -1;
                } elseif (!isset($b[$sort])) {
                    return 1;
                } else {
                    return strcasecmp($a[$sort], $b[$sort]);
                }
            });
        }

        return $countries;
    }

    /**
     * Get list of countries for use with a select form element.
     *
     * @param  string  $display
     * @return array
     */
    public function getListForSelect(string $display = 'name'): array
    {
        return array_map(function(array $country) use ($display) {
            return $country[$display];
        }, $this->getList($display));
    }
}