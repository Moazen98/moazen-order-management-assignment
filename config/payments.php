<?php

return [

    'default' => env('PAYMENT_GATEWAY', 'paypal'),

    'gateways' => [

        'paypal' => [
            'key' => env('PAYPAL_CLIENT_ID'),
            'secret'    => env('PAYPAL_SECRET'),
        ],

        'credit_cart' => [
            'key'    => env('CREDIT_CARD_KEY'),
            'secret' => env('CREDIT_CARD_SECRET'),
        ],

    ],

];
