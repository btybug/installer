<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/8/17
 * Time: 10:19 PM
 */

Route::get('/','IndexConroller@getIndex')->name('avatar_index');

Route::group(['prefix'=>'composer'],function ($router){
    Route::get('/','ComposerController@getIndex')->name('composer_index');
    Route::post('/main','ComposerController@getMain')->name('composer_main');
});



