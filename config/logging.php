<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
        ],

        'single' => [
            'driver' => 'single',
            'path' => WP_CONTENT_DIR . '/logs/woo-advanced-discounts.log',
            'level' => 'debug',
        ],
    ],
];
