<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\IbgeService;

class IbgeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IbgeService::class, function ($app) {
            return new IbgeService();
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