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

    'firebase' => [
        'baseUrl' => 'https://fcm.googleapis.com/fcm/send',
        'serverKey' => env('FIREBASE_SERVER_KEY'),
    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'payments' => [
        'hyperPay' => [
            'mode' => env('HYPER_PAY_MODE'),
            'currency' => env('HYPER_PAY_CURRENCY'),
            'saveCards' => false,
            'data' => [
                'live' => [
                    'url' => env('HYPER_PAY_LIVE_URL'),
                    'accessToken' => env('HYPER_PAY_LIVE_ACCESS_TOKEN'),
                    'entityId' => [
                        'VISA' => env('HYPER_PAY_LIVE_VISA_ENTITY_ID'),
                        'MADA' => env('HYPER_PAY_LIVE_MADA_ENTITY_ID'),
                    ],
                ],
                'sandbox' => [
                    'url' => env('HYPER_PAY_SANDBOX_URL'),
                    'accessToken' => env('HYPER_PAY_SANDBOX_ACCESS_TOKEN'),
                    'entityId' => [
                        'VISA' => env('HYPER_PAY_SANDBOX_VISA_ENTITY_ID'),
                        'MADA' => env('HYPER_PAY_SANDBOX_MADA_ENTITY_ID'),
                    ],
                ],
            ]
        ],
    ],
];
