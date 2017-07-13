<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/12/17
 * Time: 9:41 PM
 */

namespace Avatar\Avatar\Repositories;


class Plugins
{
    protected $mainComposer;
    protected $backUp;
    protected $plugins;


    public function __construct()
    {
        $this->mainComposer = json_decode(\File::get(plugins_path('composer.json')), true);
        $this->plugins = $this->sortPlugins();
    }

    private function sortPlugins()
    {
        $plugins = $this->mainComposer['require'];
        unset($plugins['php']);
        return $plugins;
    }

    public function getPlugins()
    {
        $plugins = [];
        foreach ($this->plugins as $pluginPath => $version) {
            $plugins[$pluginPath] = json_decode(\File::get($this->pluginPath($pluginPath)), true);
        }
        return collect($plugins);
    }

    private function pluginPath($plugin)
    {
        return plugins_path('vendor/' . $plugin . '/composer.json');
    }
}