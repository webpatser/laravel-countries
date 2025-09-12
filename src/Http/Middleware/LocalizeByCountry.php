<?php

namespace Webpatser\Countries\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Webpatser\Countries\Countries;

class LocalizeByCountry
{
    private array $countryLocaleMap = [
        'US' => 'en',
        'GB' => 'en',
        'CA' => 'en',
        'AU' => 'en',
        'DE' => 'de',
        'FR' => 'fr',
        'ES' => 'es',
        'IT' => 'it',
        'PT' => 'pt',
        'BR' => 'pt',
        'RU' => 'ru',
        'CN' => 'zh',
        'JP' => 'ja',
        'KR' => 'ko',
        'NL' => 'nl',
        'SE' => 'sv',
        'NO' => 'no',
        'DK' => 'da',
        'FI' => 'fi',
        'PL' => 'pl',
        'CZ' => 'cs',
        'HU' => 'hu',
        'RO' => 'ro',
        'BG' => 'bg',
        'HR' => 'hr',
        'GR' => 'el',
        'TR' => 'tr',
        'AR' => 'es',
        'MX' => 'es',
        'CO' => 'es',
        'PE' => 'es',
        'CL' => 'es',
        'VE' => 'es',
        'EC' => 'es',
        'UY' => 'es',
        'PY' => 'es',
        'BO' => 'es',
    ];

    public function handle(Request $request, Closure $next, string $parameter = 'country'): Response
    {
        $countryCode = $request->route($parameter);

        if ($countryCode && isset($this->countryLocaleMap[$countryCode])) {
            $locale = $this->countryLocaleMap[$countryCode];
            app()->setLocale($locale);
        }

        return $next($request);
    }
}