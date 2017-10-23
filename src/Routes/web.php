<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/8/17
 * Time: 10:19 PM
 */

Route::get('/', 'IndexConroller@getIndex')->name('avatar_index');


Route::group(['prefix' => 'composer'], function ($router) {
    Route::get('/', 'ComposerController@getIndex')->name('composer_index');
    Route::post('/main', 'ComposerController@getMain')->name('composer_main');
    Route::post('plugin-on-off', 'ComposerController@getOnOff')->name('on_off');
});
Route::group(['prefix' => 'modules'], function ($router) {
    Route::get('/', 'ModulesController@getIndex')->name('modules_index');
    Route::get('/{repository}/{package}/explore', 'ModulesController@getExplore');
});
Route::group(['prefix' => 'plugins'], function ($router) {
    Route::get('/', 'PluginsController@getIndex')->name('plugins_index');
    Route::get('/{repository}/{package}/explore', 'PluginsController@getExplore');

});
Route::group(['prefix' => 'market'], function ($router) {
    Route::get('/', 'MarketController@getIndex')->name('composer_market');
});



