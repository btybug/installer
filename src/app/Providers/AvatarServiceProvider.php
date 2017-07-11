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
        \Eventy::action('admin.menus', [
            "title" => "Developer console",
            "custom-link" => "#",
            "icon" => "fa fa-folder-open",
            "is_core" => "yes",
            "main"=>true,
            "children" => [[
                "title" => "Composer",
                "custom-link" => "/admin/avatar",
                "icon" => "fa fa-angle-right",
                "is_core" => "yes"
            ]]
        ]);
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
