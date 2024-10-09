<?php

namespace Focuson\AdvancedCoupons\Support;

use Focuson\AdvancedCoupons\Support\App;

class Cache
{
    protected static $cacheInstance;

    protected static function initialize()
    {
        if (!self::$cacheInstance) {
            $app = App::getInstance();
            self::$cacheInstance = $app->cacheInstance;
        }
    }

    public static function remember($key, $minutes, $callback)
    {
        self::initialize();
        return self::$cacheInstance->remember($key, $minutes, $callback);
    }

    public static function forget($key)
    {
        self::initialize();
        return self::$cacheInstance->forget($key);
    }

    public static function put($key, $value, $minutes)
    {
        self::initialize();
        return self::$cacheInstance->put($key, $value, $minutes);
    }

    public static function get($key, $default = null)
    {
        self::initialize();
        return self::$cacheInstance->get($key, $default);
    }
}
