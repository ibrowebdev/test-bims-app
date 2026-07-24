<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'bims' => [
        'client_id' => env('BIMS_CLIENT_ID', 'BIMS-25-ZZSGBP'),
        'client_secret' => env('BIMS_CLIENT_SECRET', '4na9GXevfCCJ7z6qxXjN2TvdwJNVkFhRujSvJ0DL'),
        'redirect_uri' => env('BIMS_REDIRECT_URI', 'https://test-bims-app.laravel.cloud/auth/callback/'),
        'host' => env('BIMS_HOST', 'https://account.bimsaccount.kdns.site/'),
    ],
];
