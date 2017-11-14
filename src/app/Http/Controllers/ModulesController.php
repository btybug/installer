<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 8/15/17
 * Time: 2:43 PM
 */

namespace Btybug\Http\Controllers;


use Btybug\Repositories\Plugins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\Console\Tests\Input\StringInput;

class ModulesController extends Controller
{
    public function getIndex(Request $request)
    {

        $selected = null;
        $packages = new Plugins();
        $packages->modules();
        $plugins = $packages->getPlugins();
        if ($request->p && isset($plugins[$request->p])) {
            $selected = $packages->find($plugins[$request->p]['name']);
        } elseif ($request->p && !isset($plugins[$request->p])) {
            abort('404');
        } elseif (!$request->p && !isset($plugins[$request->p])) {
            foreach ($plugins as $key => $plugin) {
                $selected = $packages->find($key);
                continue;
            }
        }
        $storage = $packages->getStorage();
        $enabled = true;
        if (isset($selected->name) && isset($storage[$selected->name])) {
            $enabled = false;
        }
        return view('core_avatar::Modules.index', compact('plugins', 'selected', 'enabled'));
    }

    public function getExplore($repository, $package)
    {
        $plugins = new Plugins();
        $plugins->modules();
        $plugin = $plugins->find($repository . '/' . $package);
        $units = $plugin->units();
        return view('core_avatar::Explores.index', compact('plugin', 'units'));
    }
}