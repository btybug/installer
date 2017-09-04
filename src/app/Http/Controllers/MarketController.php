<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 8/13/17
 * Time: 2:11 PM
 */

namespace Avatar\Avatar\Http\Controllers;


use Illuminate\Routing\Controller;

class MarketController extends Controller
{
    public function getIndex()
    {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://packagist.org/search.json?q=sahak.avatar");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
        $output=(json_decode($output,true));
//        dd($output);
        return view('core_avatar::Composer.market',compact('output'));
    }
}