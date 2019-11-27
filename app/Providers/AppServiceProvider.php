<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Channel;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
        if($this->app->isLocal())
        {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       
//        \View::share('channels', Channel::all());

        \View::composer('*', function ($view) {
            $channels = Cache::rememberForever('channels', function(){
                return Channel::all();
            });
            $view->with('channels', $channels);
        });
    }
}
