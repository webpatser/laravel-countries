<?php

namespace Webpatser\Countries\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Countries\Models\Country;
use Webpatser\Countries\Countries;

trait HasCountry
{
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'iso_3166_2');
    }

    public function getCountryNameAttribute(): ?string
    {
        return $this->country?->name;
    }

    public function getCountryFlagAttribute(): ?string
    {
        return $this->country?->flag;
    }

    public function getFormattedCountryAttribute(): ?string
    {
        if (!$this->country) {
            return null;
        }

        return $this->country->name . ($this->country->flag ? ' ' . $this->country->flag : '');
    }

    public function scopeFromCountry($query, string $countryCode)
    {
        return $query->where('country_code', strtoupper($countryCode));
    }

    public function scopeFromRegion($query, string $region)
    {
        return $query->whereHas('country', function ($q) use ($region) {
            $q->byRegion($region);
        });
    }

    public function scopeFromCurrency($query, string $currencyCode)
    {
        return $query->whereHas('country', function ($q) use ($currencyCode) {
            $q->byCurrency($currencyCode);
        });
    }

    public function isFromCountry(string $countryCode): bool
    {
        return strcasecmp($this->country_code ?? '', $countryCode) === 0;
    }

    public function isFromRegion(string $region): bool
    {
        return $this->country?->isInRegion($region) ?? false;
    }

    public function isFromEurope(): bool
    {
        return $this->isFromRegion('Europe');
    }

    public function isFromAsia(): bool
    {
        return $this->isFromRegion('Asia');
    }

    public function isFromAfrica(): bool
    {
        return $this->isFromRegion('Africa');
    }

    public function isFromNorthAmerica(): bool
    {
        return $this->isFromRegion('North America');
    }

    public function isFromSouthAmerica(): bool
    {
        return $this->isFromRegion('South America');
    }

    public function isFromOceania(): bool
    {
        return $this->isFromRegion('Oceania');
    }
}