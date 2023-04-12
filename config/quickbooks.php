<?php

return [

    /*
    |--------------------------------------------------------------------------
    | QuickBooks DataService
    |--------------------------------------------------------------------------
    |
    | https://intuit.github.io/QuickBooks-V3-PHP-SDK/configuration.html
    |
    */

    'data_service' => [
        'auth_mode' => 'oauth2',
        'base_url' => env('QUICKBOOKS_API_URL', config('app.env') === 'production' ? 'Production' : 'Development'),
        'client_id' => env('QUICKBOOKS_CLIENT_ID'),
        'client_secret' => env('QUICKBOOKS_CLIENT_SECRET'),
        'scope' => 'com.intuit.quickbooks.accounting'
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Configures logging to <storage_path>/logs/quickbooks.log when in debug
    | mode or when 'QUICKBOOKS_DEBUG' is true.
    |
    */

    'logging' => [
        'enabled' => env('QUICKBOOKS_DEBUG', config('app.debug')),
        'location' => storage_path('logs')
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirect URLs
    |--------------------------------------------------------------------------
    |
    | Return URI that QuickBooks sends code to allow getting OAuth token
    |
    */

    'redirect_url' => env('QUICKBOOKS_REDIRECT_URL', config('app.url') . '/api/quickbooks/token')

];
