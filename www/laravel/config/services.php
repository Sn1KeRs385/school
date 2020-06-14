<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'Dadata' => [
        'key' => env('DADATA_KEY'),
    ],

    'Sms' => [
        'login' => env('SMS_LOGIN'),
        'password' => env('SMS_PASSWORD'),
        'sender' => env('SMS_SENDER'),
    ],

    'sberbank' => [
        'url' => env('SBERBANK_API_URL', 'https://3dsec.sberbank.ru/payment/rest/'),
        'status_url' => env('APP_API_URL', 'http://localhost/'),
    ],

];
