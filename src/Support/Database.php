<?php

namespace Focuson\AdvancedCoupons\Support;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public function __construct()
    {
        $capsule = new Capsule;

        // Configura i dettagli della connessione. Puoi usare le costanti di WP per i dettagli di connessione.
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => DB_HOST,
            'database'  => DB_NAME,
            'username'  => DB_USER,
            'password'  => DB_PASSWORD,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => $GLOBALS['wpdb']->prefix, // Utilizza il prefisso di WordPress
        ]);

        // Rende Eloquent disponibile globalmente.
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
