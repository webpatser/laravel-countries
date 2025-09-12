<?php

namespace Webpatser\Countries\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webpatser\Countries\Rules\ValidCountryCode;
use Webpatser\Countries\Rules\ValidCurrencyCode;
use Webpatser\Countries\Rules\ValidRegion;

class CountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'iso_3166_2' => ['required', 'string', 'size:2', new ValidCountryCode('iso_3166_2')],
            'name' => ['required', 'string', 'max:255'],
            'capital' => ['nullable', 'string', 'max:255'],
            'iso_3166_3' => ['required', 'string', 'size:3', new ValidCountryCode('iso_3166_3')],
            'currency_code' => ['nullable', 'string', 'size:3', new ValidCurrencyCode()],
            'currency_name' => ['nullable', 'string', 'max:255'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'calling_code' => ['nullable', 'string', 'max:10', 'regex:/^\+?\d+$/'],
            'region' => ['nullable', 'string', 'max:255', new ValidRegion()],
            'languages' => ['nullable', 'array'],
            'languages.*' => ['string', 'max:100'],
            'flag' => ['nullable', 'string', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'iso_3166_2.size' => 'The ISO 3166-2 code must be exactly 2 characters.',
            'iso_3166_3.size' => 'The ISO 3166-3 code must be exactly 3 characters.',
            'currency_code.size' => 'The currency code must be exactly 3 characters.',
            'calling_code.regex' => 'The calling code must be a valid phone code format.',
        ];
    }

    public function attributes(): array
    {
        return [
            'iso_3166_2' => 'country code (ISO 2)',
            'iso_3166_3' => 'country code (ISO 3)',
            'currency_code' => 'currency code',
            'currency_name' => 'currency name',
            'currency_symbol' => 'currency symbol',
            'calling_code' => 'calling code',
        ];
    }
}