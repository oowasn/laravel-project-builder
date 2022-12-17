<?php

namespace Zdslab\Laravelinit\Providers;

use Illuminate\Support\ServiceProvider;

class InitProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'laravelinit');
    }

    public function register() {
        $this->registerPublishables();
    }

    private function registerPublishables() {

    }
}