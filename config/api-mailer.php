<?php
return [
    'from' => [
        'name'    => env('APIMAILER_FROM_NAME', 'Example'),
        'address' => env('APIMAILER_FROM_EMAIL', 'hello@example.com')
    ],

    'default' => env('APIMAILER_DEFAULT', 'mailgun'),

    'drivers' => [
        'sendgrid'   => ['api_key' => env('APIMAILER_SENDGRID_APIKEY')],
        'sendinblue' => ['api_key' => env('APIMAILER_SENDINBLUE_APIKEY')],
        'mailgun'    => [
            'api_key' => env('APIMAILER_MAILGUN_APIKEY'),
            'domain'  => env('APIMAILER_MAILGUN_DOMAIN')
        ],
    ]
];