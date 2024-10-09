<?php

namespace Focuson\AdvancedCoupons\Support;

class BaseController
{
    protected static $view;
    protected static $config;
    protected static $cache;

    public function __construct()
    {
		dd(1);
        $app = App::getInstance();
		self::$cache = $app->cacheInstance;
		self::$view = $app->bladeInstance;
		self::$config = $app->configInstance;
    }
}
