<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services;

class AppServiceProvider extends ServiceProvider {

    /** @var string[] */
    private $singletonServices = [
        Services\NewsLoader\Facade::class,
    ];

    /** @var string[] */
    private $singletonDevServices = [
        \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
    ];

    public function boot() {

    }

    public function register() {
        if ($this->app->environment() !== 'production') {
            foreach ($this->singletonDevServices as $service) {
                $this->app->singleton($service);
            }
        }
        foreach ($this->singletonServices as $service) {
            $this->app->singleton($service);
        }
    }
}
