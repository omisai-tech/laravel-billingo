<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Billingo API Key
    |--------------------------------------------------------------------------
    |
    | Your Billingo API key for authentication. You can generate an API key
    | from your Billingo account at https://app.billingo.hu/api-key
    |
    */

    'api_key' => env('BILLINGO_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | API Host
    |--------------------------------------------------------------------------
    |
    | The base URL for the Billingo API. You typically don't need to change
    | this unless you're using a sandbox or testing environment.
    |
    */

    'host' => env('BILLINGO_API_HOST', 'https://api.billingo.hu/v3'),

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, the API client will output debug information. This is
    | useful for troubleshooting API issues during development.
    |
    */

    'debug' => env('BILLINGO_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The number of seconds to wait for a response from the Billingo API
    | before timing out.
    |
    */

    'timeout' => env('BILLINGO_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Connection Timeout
    |--------------------------------------------------------------------------
    |
    | The number of seconds to wait while trying to connect to the Billingo
    | API server.
    |
    */

    'connect_timeout' => env('BILLINGO_CONNECT_TIMEOUT', 10),

];
