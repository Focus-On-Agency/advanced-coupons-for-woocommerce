<?php

namespace Focuson\AdvancedCoupons\Support;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\FileStore;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Container\Container;

class App
{
    protected static $instance = null;
    public $bladeInstance;
	public $cacheInstance;
    public $configInstance;

    private function __construct()
    {
        $this->bladeInstance = new Blade();
        $this->configInstance = new Config();
		$this->initializeCache();
		$this->initializeDatabase();
		$this->registerProviders();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	protected function registerProviders()
    {
        foreach (glob(__DIR__ . '/../Providers/*.php') as $providerFile)
		{
            $fileName = basename($providerFile, '.php');
			$namespaceBase = str_replace('Support', '', __NAMESPACE__);
			$providerClass = $namespaceBase . 'Providers\\' . $fileName;

            // Se la classe esiste, crea un'istanza e chiama il metodo `register`
            if (class_exists($providerClass)) {
                $provider = new $providerClass($this);
                
                // Controlla se il provider ha il metodo `register` e lo chiama
                if (method_exists($provider, 'register')) {
                    $provider->register();
                }
            }
        }
    }

	protected function initializeDatabase()
    {
        $capsule = new Capsule;

        // Configura la connessione con i dettagli di WordPress
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => DB_HOST,
            'database'  => DB_NAME,
            'username'  => DB_USER,
            'password'  => DB_PASSWORD,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => $GLOBALS['wpdb']->prefix, // Usa il prefisso di WordPress
        ]);

        // Rendi Eloquent disponibile globalmente
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

	protected function initializeCache()
    {
        $container = new Container;

        $container['config'] = [
            'cache.default' => 'file',
            'cache.stores.file' => [
                'driver' => 'file',
                'path' => WP_CONTENT_DIR . '/cache/advanced-coupons-cache',
            ],
        ];

        $filesystem = new Filesystem();
        $fileStore = new FileStore($filesystem, $container['config']['cache.stores.file']['path']);
        $this->cacheInstance = new CacheManager($container);
        $this->cacheInstance->extend('file', function ($app) use ($fileStore) {
            return new \Illuminate\Cache\Repository($fileStore);
        });
    }

    public function view($view, $data = [])
    {
        return $this->bladeInstance->render($view, $data);
    }

    public function config($key, $default = null)
    {
        return $this->configInstance->get($key, $default);
    }
}
