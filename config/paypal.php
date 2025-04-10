<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayPal Mode
    |--------------------------------------------------------------------------
    |
    | Spécifie si vous souhaitez utiliser le bac à sable PayPal (sandbox) ou
    | l'environnement de production.
    |
    | Supported: "sandbox", "live"
    |
    */
    'mode' => env('PAYPAL_MODE', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | PayPal Sandbox Credentials
    |--------------------------------------------------------------------------
    |
    | Les identifiants de l'environnement sandbox PayPal.
    |
    */
    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | PayPal Live Credentials
    |--------------------------------------------------------------------------
    |
    | Les identifiants de l'environnement de production PayPal.
    |
    */
    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | PayPal Currency
    |--------------------------------------------------------------------------
    |
    | La devise utilisée pour les paiements PayPal.
    |
    */
    'currency' => env('PAYPAL_CURRENCY', 'EUR'),

    /*
    |--------------------------------------------------------------------------
    | PayPal Webhook ID
    |--------------------------------------------------------------------------
    |
    | L'ID du webhook configuré dans le panneau PayPal.
    |
    */
    'webhook_id' => env('PAYPAL_WEBHOOK_ID', ''),
];
