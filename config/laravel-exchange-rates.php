<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | Define which API service should be used to retrieve the exchange rates.
    |
    | Supported: "exchange-rates-api-io", "exchange-rates-data-api", "exchange-rate-host"
    |
    */
    'driver' => 'exchange-rates-data-api',

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Define your API key here.
    |
    */
    'api_key' => env('EXCHANGE_RATES_API_KEY', 'PDMCnAJMuw1GOr54Am9g5mIEhRL2i0P4'),

];
