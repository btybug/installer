<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/8/17
 * Time: 10:06 PM
 */

namespace Btybug\Http\Controllers;

use Illuminate\Routing\Controller;

class IndexConroller extends Controller
{
    public function getIndex()
    {
        return view('core_avatar::index');
    }
}