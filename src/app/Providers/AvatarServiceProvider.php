<?php

namespace Avatar\Avatar\Providers;

//use TorMorten\Eventy;

use Avatar\Avatar\Repositories\Plugins;
use Illuminate\Support\ServiceProvider;


class AvatarServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'core_avatar');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'core_avatar');
        \Eventy::action('admin.menus', [
            "title" => "Developer console",
            "custom-link" => "#",
            "icon" => "fa fa-folder-open",
            "is_core" => "yes",
            "main" => true,
            "children" => [[
                "title" => "Plugins",
                "custom-link" => '/admin/avatar',
                "icon" => "fa fa-angle-right",
                "is_core" => "yes"
            ]]
        ]);
        global $_PLUGIN_PROVIDERS;
//        dd($_PLUGIN_PROVIDERS);
        if(isset($_PLUGIN_PROVIDERS['pluginProviders'])){
            foreach ($_PLUGIN_PROVIDERS['pluginProviders'] as $namespace=>$options){
                $this->app->register($namespace,$options['options'],$options['force']);
            }
        }

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
