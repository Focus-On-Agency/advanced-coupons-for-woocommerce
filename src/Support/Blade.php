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
        // Inizializza i componenti di Blade
        $filesystem = new Filesystem();

        // Percorso per i file compilati
        $cachePath = WP_CONTENT_DIR . '/cache/advanced-coupons-cache';

        // Compilatore di Blade
        $compiler = new BladeCompiler($filesystem, $cachePath);

        // Risolutore di motori per gestire i template Blade
        $resolver = new EngineResolver();
        $resolver->register('blade', function () use ($compiler) {
            return new CompilerEngine($compiler);
        });

        // Inizializza il Finder per i percorsi delle viste
        $viewPaths = [__DIR__ . '/../../resources/views'];
        $finder = new FileViewFinder($filesystem, $viewPaths);

		$finder->addNamespace(env('APP_SLUG', 'advanced_coupons_for_woocommerce'), __DIR__ . '/../../resources/views');

        // Aggiungi un dispatcher per gli eventi
        $dispatcher = new Dispatcher(new Container);

        // Inizializza la Factory di Blade con il resolver, il finder e il dispatcher degli eventi
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
