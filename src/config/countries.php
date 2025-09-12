<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Database Table Name
    |--------------------------------------------------------------------------
    |
    | The name of the table to create in the database for storing countries
    | data. This table will be used if you choose to migrate countries to
    | your database instead of using the JSON file.
    |
    */
    'table_name' => 'countries',

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Configure caching for countries data to improve performance.
    | Set cache_ttl to 0 to disable caching.
    |
    */
    'cache_ttl' => 3600, // Cache for 1 hour

    /*
    |--------------------------------------------------------------------------
    | Data Source
    |--------------------------------------------------------------------------
    |
    | Choose between 'json' or 'database' as your data source.
    | 
    | - json: Use the built-in JSON file (recommended for most use cases)
    | - database: Use database table (requires running migrations)
    |
    */
    'data_source' => 'json',

    /*
    |--------------------------------------------------------------------------
    | Default Sort Field
    |--------------------------------------------------------------------------
    |
    | The default field to sort countries by when no specific sort is requested.
    | Valid options: name, capital, iso_3166_2, iso_3166_3, currency_code,
    | currency_name, region, etc.
    |
    */
    'default_sort' => 'name',

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Enable automatic localization of country names if your application
    | supports multiple languages. This requires additional translation files.
    |
    */
    'localized' => false,

    /*
    |--------------------------------------------------------------------------
    | Search Settings
    |--------------------------------------------------------------------------
    |
    | Configure search behavior for country lookups.
    |
    */
    'search' => [
        'fields' => ['name', 'capital'], // Fields to search in
        'case_sensitive' => false,
    ],
];