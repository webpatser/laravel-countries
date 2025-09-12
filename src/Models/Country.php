<?php

namespace Webpatser\Countries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Webpatser\Countries\Casts\CountryLanguages;
use Webpatser\Countries\Casts\CountryFlag;

class Country extends Model
{
    protected $fillable = [
        'iso_3166_2',
        'name',
        'capital',
        'iso_3166_3',
        'currency_code',
        'currency_name',
        'currency_symbol',
        'calling_code',
        'region',
        'languages',
        'flag',
    ];

    protected $casts = [
        'languages' => CountryLanguages::class,
        'flag' => CountryFlag::class,
    ];

    protected $primaryKey = 'iso_3166_2';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('countries.table_name', 'countries');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->localizeCountryName($value),
        );
    }

    protected function callingCode(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? '+' . ltrim($value, '+') : null,
        );
    }

    protected function currencySymbol(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?: '¤',
        );
    }

    public function scopeByRegion(Builder $query, string $region): Builder
    {
        return $query->where('region', 'LIKE', "%{$region}%");
    }

    public function scopeByCurrency(Builder $query, string $currencyCode): Builder
    {
        return $query->where('currency_code', strtoupper($currencyCode));
    }

    public function scopeByLanguage(Builder $query, string $language): Builder
    {
        return $query->where('languages', 'LIKE', "%{$language}%");
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('capital', 'LIKE', "%{$search}%")
              ->orWhere('iso_3166_2', 'LIKE', "%{$search}%")
              ->orWhere('iso_3166_3', 'LIKE', "%{$search}%");
        });
    }

    public function scopeEuropean(Builder $query): Builder
    {
        return $query->where('region', 'Europe');
    }

    public function scopeWithCurrency(Builder $query): Builder
    {
        return $query->whereNotNull('currency_code');
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ($this->flag ? ' ' . $this->flag : '');
    }

    public function getFormattedCallingCodeAttribute(): ?string
    {
        return $this->calling_code ? '+' . ltrim($this->calling_code, '+') : null;
    }

    public function hasLanguage(string $language): bool
    {
        if (!$this->languages) {
            return false;
        }

        $languages = is_array($this->languages) ? $this->languages : [$this->languages];
        
        return in_array(strtolower($language), array_map('strtolower', $languages));
    }

    public function hasCurrency(): bool
    {
        return !empty($this->currency_code);
    }

    public function isInRegion(string $region): bool
    {
        return stripos($this->region ?? '', $region) !== false;
    }

    public function getLanguagesListAttribute(): string
    {
        if (!$this->languages) {
            return 'N/A';
        }

        $languages = is_array($this->languages) ? $this->languages : [$this->languages];
        
        return implode(', ', $languages);
    }

    private function localizeCountryName(string $name): string
    {
        if (!config('countries.localized', false)) {
            return $name;
        }

        $locale = app()->getLocale();
        $key = "countries.{$this->iso_3166_2}";
        
        return trans($key, [], $locale) !== $key ? trans($key, [], $locale) : $name;
    }

    public function toSelectOption(): array
    {
        return [
            'value' => $this->iso_3166_2,
            'label' => $this->full_name,
        ];
    }

    public static function getSelectOptions(string $region = null): array
    {
        $query = static::query()->orderBy('name');
        
        if ($region) {
            $query->byRegion($region);
        }
        
        return $query->get()->map->toSelectOption()->toArray();
    }
}