<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Maps API Key
    |--------------------------------------------------------------------------
    */

    'googleMapsApiKey' => env('VITE_GOOGLE_MAPS_API_KEY') ?? '',

    /*
    |--------------------------------------------------------------------------
    | Consent API
    |--------------------------------------------------------------------------
    */

    'consentApiEnabled' => (bool) env('CONSENT_API_ENABLED'),
    'consentApiProvider' => env('CONSENT_API_PROVIDER'),

    /*
    |--------------------------------------------------------------------------
    | Iubenda Consent API Key
    |--------------------------------------------------------------------------
    */

    'iubendaConsentApiKey' => env('IUBENDA_CONSENT_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    'products' => [
        'imagesFetchingMethod' => env('APP_PRODUCT_IMAGE_FETCH_METHOD'),
        'imagesFetchingURL' => env('APP_PRODUCT_IMAGE_FETCH_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Typesense
    |--------------------------------------------------------------------------
    */
    'typesense' => [
        'schemaPrefix' => env('APP_TYPESENSE_SCHEMA_PREFIX') ?? 'sage_',
    ],
];
