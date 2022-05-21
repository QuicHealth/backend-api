<?php

namespace App\Providers;

use App\Classes\Monnify;
use Illuminate\Support\ServiceProvider;

class MonnifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'monnify');

        $this->app->singleton('monnify', function ($app) {
            $baseUrl = config('monnify.base_url');
            $instanceName = 'monnify';


            return new Monnify(
                $baseUrl,
                $instanceName,
                config('monnify')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
