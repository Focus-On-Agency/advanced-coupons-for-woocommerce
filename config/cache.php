<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | Questo opzione controlla quale cache driver dovrebbe essere utilizzato
    | di default per l'applicazione. Sono disponibili diversi driver come 
    | "file", "database", "redis", ecc. 
    |
    */

    'default' => env('CACHE_DRIVER', 'file'), // Usa file come driver di default


    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Qui puoi definire tutti i "store" di cache per l'applicazione.
    | Puoi persino configurare più store per lo stesso driver.
    |
    */

    'stores' => [

        // Cache basata su file (predefinita)
        'file' => [
            'driver' => 'file',
            'path'   => WP_CONTENT_DIR . '/cache/' . env('APP_SLUG', 'woo-advanced-discounts'),
        ],

        // Cache basata su Redis
        'redis' => [
            'driver'     => 'redis',
            'connection' => 'default',
        ],

        // Cache nel database (opzionale)
        'database' => [
            'driver'    => 'database',
            'table'     => 'cache',
            'connection'=> null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | Quando si utilizza un sistema di cache come Redis o Memcached, puoi
    | impostare un prefisso per evitare collisioni di chiavi con altre app.
    |
    */

    'prefix' => env('APP_SLUG', 'woo-advanced-discounts'),
];
