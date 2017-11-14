<?php

namespace Btybug\Providers;

//use TorMorten\Eventy;

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
//        \Eventy::action('admin.menus', [
//            "title" => "Developer console",
//            "custom-link" => "#",
//            "icon" => "fa fa-folder-open",
//            "is_core" => "yes",
//            "main" => true,
//            "children" => [[
//                "title" => "AVATAR",
//                "custom-link" => '/admin/avatar',
//                "icon" => "fa fa-angle-right",
//                "is_core" => "yes"
//            ]]
//        ]);
        $tabs = [
            'avatar_packages' => [
                [
                    'title' => 'Composer',
                    'url' => '/admin/avatar/composer',
                ], [
                    'title' => 'Market',
                    'url' => '/admin/avatar/market',
                ],
            ],
            'extra_packages' => [
                [
                    'title' => 'Modules',
                    'url' => '/admin/avatar/extra-packages',
                ], [
                    'title' => 'Apps',
                    'url' => '/admin/avatar/apps',
                ],
            ]
        ];
//        \Eventy::action('my.tab', $tabs);
        global $_PLUGIN_PROVIDERS;
//        dd($_PLUGIN_PROVIDERS);
        if (isset($_PLUGIN_PROVIDERS['pluginProviders'])) {
            foreach ($_PLUGIN_PROVIDERS['pluginProviders'] as $namespace => $options) {
                $this->app->register($namespace, $options['options'], $options['force']);
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
