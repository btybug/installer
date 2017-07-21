<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/12/17
 * Time: 9:41 PM
 */

namespace Avatar\Avatar\Repositories;

//TODO replace base_path() with plugins_path()
class Plugins
{
    protected $mainComposer;
    protected $backUp;
    protected $plugins;


    public function __construct()
    {
        composer:
        if(\File::exists(base_path())){
            $this->mainComposer = json_decode(\File::get(base_path('composer.json')), true);
            $this->plugins = $this->sortPlugins();
        }else{
            if(\File::makeDirectory(base_path(config('avatar.plugins.path')))){
                if( \File::put(base_path('composer.json'),'{}')){
                    goto composer;
                };
            }

         throw new \Exception('qaqa');
        }

    }

    private function sortPlugins()
    {
        $plugins = isset($this->mainComposer['require-dev'])?$this->mainComposer['require-dev']:[];
        unset($plugins['php']);
        return $plugins;
    }

    public function getPlugins()
    {
        $plugins = [];
        foreach ($this->plugins as $pluginPath => $version) {
            if (\File::exists($this->pluginPath($pluginPath)))
            $plugins[$pluginPath] = json_decode(\File::get($this->pluginPath($pluginPath)), true);
        }
        return collect($plugins);
    }

    private function pluginPath($plugin)
    {
        return base_path('vendor/' . $plugin . '/composer.json');
    }

    public function onOff(array $data)
    {
        $result=false;
        switch ($data['action']){
            case 'on':$result=$this->enable($data['namespace']);break;
            case 'off':$result=$this->disable($data['namespace']);break;
        }
        return $result;
    }

    public function enable($pluginPath)
    {
        $plugins=$this->getInstaleds();
        foreach ($plugins as $key=>$plugin){
            if($plugin['name']==$pluginPath){
//                $plugin=$plugins[$pluginPath];
                $store=$this->getStorage();
                $plugins[$key]['autoload']=$store[$pluginPath];
                unset($store[$pluginPath]);
                $this->addStorage($store);
                $this->setInstaleds($plugins);continue;

            }
        }
        return $this->command('dump-autoload');

    }

    public function disable($pluginPath)
    {
        $plugins=$this->getInstaleds();
        foreach ($plugins as $key=>$plugin){
            if($plugin['name']==$pluginPath){
//                $plugin=$plugins[$pluginPath];
                $store=$this->getStorage();
                $store[$pluginPath]=$plugin['autoload'];
                $this->addStorage($store);
                unset($plugins[$key]['autoload']);
                $this->setInstaleds($plugins);
               continue;
            }
        }
        return $this->command('dump-autoload');
    }

    public function getInstaleds()
    {
        if(\File::exists(base_path('vendor'.DS.'composer'.DS.'installed.json'))){
            return json_decode(\File::get(base_path('vendor'.DS.'composer'.DS.'installed.json')), true);
        }

    }
    public function setInstaleds($data)
    {
        if(\File::exists(base_path('vendor'.DS.'composer'.DS.'installed.json'))){
            return \File::put(base_path('vendor'.DS.'composer'.DS.'installed.json'),json_encode($data,true));
        }

    }

    public function command($command)
    {
        $path = str_replace('\\', '\\\\', base_path());
        set_time_limit(-1);
        putenv('COMPOSER_HOME=' . __DIR__ . '/../../extracted/bin/composer');
        if (!file_exists($path)) {
            return false;
        }
        if (file_exists(__DIR__ . '/../../composer/extracted')) {
            require_once(__DIR__ . '/../../composer/extracted/extracted/vendor/autoload.php');
            $input = new \Symfony\Component\Console\Input\StringInput($command . ' -vvv -d ' . $path);
            $output = new \Symfony\Component\Console\Output\StreamOutput(fopen('php://output', 'w'));
            $app = new \Composer\Console\Application();
             $app->run($input, $output);
             return true;
        }
        return false;
    }

    public function addStorage(array $data)
    {
        \File::put(storage_path('packagis.txt'),json_encode($data,true));
    }

    public function getStorage()
    {
        if(\File::exists(storage_path('packagis.txt'))){
            return json_decode(\File::get(storage_path('packagis.txt')),true);
        }
    }

    public function composerRequireDev($package)
    {
        $plugin=explode(':',$package);
        $this->mainComposer['require-dev'][$plugin[0]]=$plugin[1];
        \File::put(base_path('composer.json'),json_encode($this->mainComposer,true));
        return $this->command('update --dev --no-interaction');
    }
    public function composerRemoveDev($package)
    {
        if(!isset($this->mainComposer['require-dev'][$package])){
            echo 'Warning wrong package name!!!';
            exit;
        }
        unset($this->mainComposer['require-dev'][$package]);
        \File::put(base_path('composer.json'),json_encode($this->mainComposer));
        return $this->command('update --dev --no-interaction');
    }
}