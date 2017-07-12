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

class IndexConroller extends Controller
{
    public function getIndex()
    {
        return view('core_avatar::index');
    }

}