<?php

namespace Avatar\Avatar\Providers;

//use TorMorten\Eventy;

use App\helpers\EmailScHelper;
use App\helpers\Tabs;
use Blade;
use File;
use Illuminate\Support\ServiceProvider;
use View;


class AvatarServiceProvider extends ServiceProvider
{



    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'core_avatar');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'core_avatar');
//dd(plugins_path('qaq'));
    }



    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->register(RouteServiceProvider::class);
    }

}
