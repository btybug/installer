<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/8/17
 * Time: 10:19 PM
 */

Route::get('/','IndexConroller@getIndex');
Route::post('/main','IndexConroller@getMain');
Route::get('/composer-update','IndexConroller@composerUpdate')->name('composer_update');