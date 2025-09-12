<?php

namespace Webpatser\Countries\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CountryCode implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value ? strtoupper($value) : null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value ? strtoupper(trim($value)) : null;
    }
}