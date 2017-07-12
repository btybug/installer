<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/8/17
 * Time: 10:06 PM
 */

namespace Avatar\Avatar\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\Console\Tests\Input\StringInput;
use Avatar\Avatar\Repositories\Plugins;

class IndexConroller extends Controller
{
    public function getIndex(Request $request)
    {
        $packages=new Plugins();
        $plugins=$packages->getPlugins();
        if($request->p && isset($plugins[$request->p])){
            $selected=$plugins[$request->p];
        }elseif($request->p && !isset($plugins[$request->p])){
            abort('404');
        }elseif (!$request->p && !isset($plugins[$request->p])){
            foreach ($plugins as $plugin){
                $selected=$plugin;
                continue;
            }
        }
        return view('core_avatar::index',compact('plugins','selected'));
    }

}