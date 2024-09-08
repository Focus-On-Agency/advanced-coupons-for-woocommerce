<?php

return [
    'default' => 'mysql',

    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => defined('DB_HOST') ? DB_HOST : '127.0.0.1',
            'database'  => defined('DB_NAME') ? DB_NAME : 'wordpress',
            'username'  => defined('DB_USER') ? DB_USER : 'root',
            'password'  => defined('DB_PASSWORD') ? DB_PASSWORD : '',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => defined('DB_PREFIX') ? DB_PREFIX : 'wp_', // Prefisso delle tabelle
            'strict'    => false,
        ],
    ],

    'migrations' => 'migrations',
];

