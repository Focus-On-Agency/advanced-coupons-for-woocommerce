<?php

namespace Focuson\AdvancedCoupons\Support;

use Focuson\AdvancedCoupons\Support\App;

class BaseServiceProvider
{
    protected $app;
    protected $view;
    protected $config;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->view = $app->bladeInstance;
        $this->config = $app->configInstance;
    }
}
