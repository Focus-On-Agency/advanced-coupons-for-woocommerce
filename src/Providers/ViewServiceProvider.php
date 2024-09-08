<?php

namespace Focuson\WoocommerceDiscountAdvanced\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Upload configurations from the given path
     * 
     * @return void
     */
    public function register()
    {
        $this->app['config']->set('view.paths', [
            __DIR__ . '/../../resources/views',
        ]);

		$cachePath = WP_CONTENT_DIR . '/cache/'. config('app.slug').'/views';
        $sessionPath = WP_CONTENT_DIR . '/cache/'. config('app.slug').'/sessions';

		if (!file_exists($cachePath)) {
			mkdir($cachePath, 0755, true);
		}

        if (!file_exists($sessionPath)) {
            mkdir($sessionPath, 0755, true);
        }

		$this->app['config']->set('view.compiled', $cachePath);

        $this->app['config']->set('session.driver', 'file');
        $this->app['config']->set('session.files', $sessionPath);

    }
}
