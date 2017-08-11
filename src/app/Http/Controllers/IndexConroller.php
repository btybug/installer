<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/8/17
 * Time: 10:06 PM
 */

namespace Avatar\Avatar\Http\Controllers;


use Avatar\Avatar\illustrator\CmsModules;
use Avatar\Avatar\Repositories\Plugins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\Console\Tests\Input\StringInput;

class IndexConroller extends Controller
{
    public function getIndex(Request $request)
    {

        $selected=null;
        $packages = new Plugins();
//        dd($packages->find('sahak.avatar/avatar')->units());
        $plugins = $packages->getPlugins();
        if ($request->p && isset($plugins[$request->p])) {
            $selected = $packages->find($plugins[$request->p]['name']);
        } elseif ($request->p && !isset($plugins[$request->p])) {
            abort('404');
        } elseif (!$request->p && !isset($plugins[$request->p])) {
            foreach ($plugins as $plugin) {
                $selected = $packages->find($plugin['name']);
                continue;
            }
        }
        $storage=$packages->getStorage();
        $enabled=true;
        if(isset($selected->name) && isset($storage[$selected->name])){
            $enabled=false;
        }
        return view('core_avatar::index', compact('plugins', 'selected','enabled'));
    }

    public function getExplore($repository,$package)
    {
        $plugins=new Plugins();
        $plugin=$plugins->find($repository.'/'.$package);
        $units=$plugin->units();
        return view('core_avatar::Explores.index',compact('plugin','units'));
    }

}