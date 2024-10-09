<?php

namespace Focuson\AdvancedCoupons\Support;

class BasePlugin
{
    protected $view;
    protected $config;
	protected $cache;

    public function __construct()
    {
        $app = App::getInstance();
		$this->cache = $app->cacheInstance;
        $this->view = $app->bladeInstance;
        $this->config = $app->configInstance;
    }
}
