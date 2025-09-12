<?php

namespace Webpatser\Countries\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseContract;
use Webpatser\Countries\Countries;

class ValidateCountryCode
{
    public function handle(Request $request, Closure $next, string $parameter = 'country'): ResponseContract
    {
        $countryCode = $request->route($parameter);

        if (!$countryCode) {
            return response()->json(['error' => 'Country code is required'], 400);
        }

        $countries = new Countries();
        $country = $countries->getOne($countryCode);

        if (!$country) {
            return response()->json([
                'error' => 'Invalid country code',
                'code' => $countryCode
            ], 404);
        }

        $request->merge(['validated_country' => $country]);

        return $next($request);
    }
}