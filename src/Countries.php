<?php

namespace Webpatser\Countries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Modern Countries class for Laravel 11/12
 */
class Countries extends Model
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private array $countries = [];

    /**
     * @var string The table name for countries in database
     */
    protected $table;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = config('countries.table_name', 'countries');
    }

    /**
     * Get countries data
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getCountries(): array
    {
        if (empty($this->countries)) {
            $dataSource = config('countries.data_source', 'json');
            $cacheTtl = config('countries.cache_ttl', 0);

            if ($cacheTtl > 0) {
                // Advanced: Optional caching if configured
                $cacheKey = 'countries_data_' . $dataSource;
                $this->countries = Cache::remember($cacheKey, $cacheTtl, function () use ($dataSource) {
                    return $this->loadCountriesData($dataSource);
                });
            } else {
                // Default: No caching, direct load
                $this->countries = $this->loadCountriesData($dataSource);
            }
        }

        return $this->countries;
    }

    /**
     * Load countries data from configured source
     *
     * @param string $dataSource
     * @return array<string, array<string, mixed>>
     */
    private function loadCountriesData(string $dataSource): array
    {
        return $dataSource === 'database' 
            ? $this->getCountriesFromDatabase()
            : $this->getCountriesFromJson();
    }

    /**
     * Get countries from database table
     *
     * @return array<string, array<string, mixed>>
     */
    private function getCountriesFromDatabase(): array
    {
        $countries = [];
        
        $records = \DB::table($this->table)->get();
        
        foreach ($records as $record) {
            $countryData = [
                'name' => $record->name,
                'capital' => $record->capital,
                'iso_3166_2' => $record->iso_3166_2,
                'iso_3166_3' => $record->iso_3166_3,
                'currency_code' => $record->currency_code,
                'currency_name' => $record->currency_name,
                'currency_symbol' => $record->currency_symbol,
                'calling_code' => $record->calling_code,
                'region' => $record->region,
                'languages' => json_decode($record->languages ?? '[]', true),
                'flag' => $record->flag,
            ];
            
            $countries[$record->iso_3166_2] = $countryData;
        }
        
        return $countries;
    }

    /**
     * Get countries from JSON file
     *
     * @return array<string, array<string, mixed>>
     */
    private function getCountriesFromJson(): array
    {
        $jsonPath = __DIR__ . '/Models/countries.json';
        
        if (!file_exists($jsonPath)) {
            throw new \RuntimeException('Countries JSON file not found: ' . $jsonPath);
        }

        $data = json_decode(file_get_contents($jsonPath), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in countries file: ' . json_last_error_msg());
        }

        return $data;
    }

    /**
     * Get a single country by ISO code
     *
     * @param string $iso The ISO 3166-2 country code
     * @return array<string, mixed>|null
     */
    public function getOne(string $iso): ?array
    {
        $countries = $this->getCountries();
        return $countries[strtoupper($iso)] ?? null;
    }

    /**
     * Get list of all countries
     *
     * @param string|null $sort Field to sort by
     * @return array<string, array<string, mixed>>
     */
    public function getList(?string $sort = null): array
    {
        $countries = $this->getCountries();

        if ($sort && $this->isValidSortField($sort)) {
            uasort($countries, function ($a, $b) use ($sort) {
                $aValue = $a[$sort] ?? '';
                $bValue = $b[$sort] ?? '';
                
                if (is_array($aValue)) {
                    $aValue = implode(', ', $aValue);
                }
                if (is_array($bValue)) {
                    $bValue = implode(', ', $bValue);
                }

                return strcasecmp((string) $aValue, (string) $bValue);
            });
        }

        return $countries;
    }

    /**
     * Get countries by currency code
     *
     * @param string $currencyCode
     * @return array<string, array<string, mixed>>
     */
    public function getByCurrency(string $currencyCode): array
    {
        $countries = $this->getCountries();
        
        return array_filter($countries, function ($country) use ($currencyCode) {
            return isset($country['currency_code']) && 
                   strcasecmp($country['currency_code'], $currencyCode) === 0;
        });
    }

    /**
     * Get countries by region
     *
     * @param string $region
     * @return array<string, array<string, mixed>>
     */
    public function getByRegion(string $region): array
    {
        $countries = $this->getCountries();
        
        return array_filter($countries, function ($country) use ($region) {
            return isset($country['region']) && 
                   strcasecmp($country['region'], $region) === 0;
        });
    }

    /**
     * Search countries by name or capital
     *
     * @param string $query
     * @return array<string, array<string, mixed>>
     */
    public function search(string $query): array
    {
        $countries = $this->getCountries();
        $query = strtolower($query);
        
        return array_filter($countries, function ($country) use ($query) {
            $name = strtolower($country['name'] ?? '');
            $capital = strtolower($country['capital'] ?? '');
            
            return str_contains($name, $query) || str_contains($capital, $query);
        });
    }

    /**
     * Get list formatted for HTML select elements
     *
     * @param string $display Field to display
     * @return array<string, string>
     */
    public function getListForSelect(string $display = 'name'): array
    {
        $countries = $this->getList($display);
        $result = [];
        
        foreach ($countries as $iso => $country) {
            if (isset($country[$display])) {
                $value = $country[$display];
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }
                $result[$iso] = (string) $value;
            }
        }
        
        return $result;
    }

    /**
     * Get countries as Laravel Collection
     *
     * @return Collection<string, array<string, mixed>>
     */
    public function collect(): Collection
    {
        return collect($this->getCountries());
    }

    /**
     * Clear the countries cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget('countries_data');
        $this->countries = [];
    }

    /**
     * Check if a sort field is valid
     *
     * @param string $field
     * @return bool
     */
    private function isValidSortField(string $field): bool
    {
        $validFields = [
            'name',
            'capital',
            'iso_3166_2',
            'iso_3166_3',
            'currency_code',
            'currency_name',
            'currency_symbol',
            'calling_code',
            'region',
            'languages',
            'flag',
        ];

        return in_array($field, $validFields, true);
    }
}