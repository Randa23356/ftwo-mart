<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    ],

    'rajaongkir' => [
        'api_key' => env('RAJAONGKIR_API_KEY'),
        'cost_api_key' => env('RAJAONGKIR_COST_API_KEY'),
        'base_url' => env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com'),
        'account_type' => env('RAJAONGKIR_ACCOUNT_TYPE', 'starter'),
        'origin_city_id' => env('SHIPPING_ORIGIN_CITY_ID', 256), // Mataram, Lombok
        'fallback_enabled' => env('RAJAONGKIR_FALLBACK_ENABLED', true),
    ],

    'binderbyte' => [
        'api_key' => env('BINDERBYTE_API_KEY'),
        'base_url' => env('BINDERBYTE_BASE_URL', 'https://api.binderbyte.com'),
        'origin_city_id' => env('SHIPPING_ORIGIN_CITY_ID', 256), // Mataram, Lombok
    ],

    'shipping_provider' => env('SHIPPING_PROVIDER', 'komerce'),

    'komerce' => [
        'cost_api_key' => env('KOMERCE_COST_API_KEY'),
        'delivery_api_key' => env('KOMERCE_DELIVERY_API_KEY'),
        'base_url' => env('KOMERCE_BASE_URL', 'https://api-sandbox.collaborator.komerce.id'),
        'environment' => env('KOMERCE_ENVIRONMENT', 'production'),
        'origin_city_id' => env('SHIPPING_ORIGIN_CITY_ID', 256),
    ],


];
