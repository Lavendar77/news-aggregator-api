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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'newsapi' => [
        'base_url' => env('NEWSAPI_BASE_URL', 'https://newsapi.org'),
        'api_key' => env('NEWSAPI_KEY'),
    ],

    'guardian' => [
        'base_url' => env('GUARDIAN_BASE_URL', 'https://content.guardianapis.com'),
        'api_key' => env('GUARDIAN_KEY'),
    ],

    'nytimes' => [
        'base_url' => env('NYTIMES_BASE_URL', 'https://api.nytimes.com'),
        'api_key' => env('NYTIMES_KEY'),
    ],

];
