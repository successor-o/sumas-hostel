<?php

return [
    'default' => env('MAIL_MAILER', 'log'),
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'mailhog'),
            'port' => env('MAIL_PORT', 1025),
            'encryption' => env('MAIL_ENCRYPTION'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
        ],
        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],
    ],
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hostels@sumas.edu.ng'),
        'name' => env('MAIL_FROM_NAME', 'SUMAS Hostels'),
    ],
];
