<?php

namespace ReeStyleIT\LaravelCarty\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use ReeStyleIT\LaravelCarty\Contract\CartyContract;

class CartyServiceProvider extends ServiceProvider
{

    public function boot():void
    {
        $this->publishes([
            __DIR__ . '/../../config/carty.php' => config_path('carty.php'),
            'config'
        ]);
    }

    public function register(): void
    {
        $this->app->singleton(
            CartyContract::class, function (Application $application) {
            return new \ReeStyleIT\Carty\Carty(config('carty'));
        });
    }

    public function provides(): array
    {
        return [
            CartyContract::class,
        ];
    }

}
