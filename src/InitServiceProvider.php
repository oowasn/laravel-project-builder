<?php

namespace Zdslab\Laravelinit;

use Illuminate\Support\ServiceProvider;

class InitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../publishable/resources/views', 'init');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register() {
        $this->registerPublishables();
        $this->loadHelpers();
    }

    private function registerPublishables()
    {
        $publishablePath = dirname(__DIR__).'/publishable';

        $publishable = [
            'login-assets' => [
                "{$publishablePath}/login-assets/" => app_path('public'),
            ]
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }


}