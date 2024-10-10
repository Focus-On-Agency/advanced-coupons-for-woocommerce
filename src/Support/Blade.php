<?php

namespace Focuson\AdvancedCoupons\Support;

use Illuminate\View\FileViewFinder;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Blade
{
    protected $factory;

    public function __construct()
    {
        $this->initializeBlade();
    }

    protected function initializeBlade()
    {
        $filesystem = new Filesystem();

        // Path for the Blade cache
        $cachePath = WP_CONTENT_DIR . '/cache/advanced-coupons-cache';

        $compiler = new BladeCompiler($filesystem, $cachePath);

        $resolver = new EngineResolver();
        $resolver->register('blade', function () use ($compiler) {
            return new CompilerEngine($compiler);
        });

        // Path for the views
        $viewPaths = [__DIR__ . '/../../resources/views'];
        $finder = new FileViewFinder($filesystem, $viewPaths);

		$finder->addNamespace(env('APP_SLUG', 'advanced-coupons-for-woocommerce'), __DIR__ . '/../../resources/views');

        $dispatcher = new Dispatcher(new Container);

        $this->factory = new Factory($resolver, $finder, $dispatcher);
    }

    /**
     * Metodo per eseguire il rendering di una vista Blade
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    public function render($view, $data = [])
    {
        return $this->factory->make($view, $data)->render();
    }
}
