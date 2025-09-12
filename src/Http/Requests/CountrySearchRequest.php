<?php

namespace Webpatser\Countries\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountrySearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'min:1', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'size:3'],
            'language' => ['nullable', 'string', 'max:100'],
            'sort_by' => ['nullable', 'string', 'in:name,capital,iso_3166_2,iso_3166_3,currency_code,region'],
            'sort_direction' => ['nullable', 'string', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:250'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'currency.size' => 'The currency code must be exactly 3 characters.',
            'sort_by.in' => 'The sort field must be one of: name, capital, iso_3166_2, iso_3166_3, currency_code, region.',
            'sort_direction.in' => 'The sort direction must be either asc or desc.',
            'per_page.max' => 'You can request at most 250 countries per page.',
        ];
    }

    public function getSearchQuery(): ?string
    {
        return $this->input('search');
    }

    public function getRegion(): ?string
    {
        return $this->input('region');
    }

    public function getCurrency(): ?string
    {
        return $this->input('currency');
    }

    public function getLanguage(): ?string
    {
        return $this->input('language');
    }

    public function getSortBy(): string
    {
        return $this->input('sort_by', 'name');
    }

    public function getSortDirection(): string
    {
        return $this->input('sort_direction', 'asc');
    }

    public function getPerPage(): int
    {
        return $this->input('per_page', 50);
    }
}