<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/chart', 'ChartController@index')->name('chart');
Route::get('/chart/{coin_type}', 'ChartController@index');

Route::get('/simulation/{coin_type}', 'SimulationController@index')->name('simulation');

Route::get('/setting', 'SettingController@index')->name('setting');

Route::get('/setting/config', 'SettingController@config_form')->name('setting_config_form');
Route::post('/setting/config', 'SettingController@config_submit')->name('setting_config_submit');

Route::get('/setting/coin/{type}', 'SettingController@coin_form')->name('setting_coin_form');
Route::put('/setting/coin/{type}', 'SettingController@coin_submit')->name('setting_coin_submit');

Route::get('/test', 'TestController@index')->name('test');



Route::get('/bithumb', 'BithumbController@index')->name('bithumb');
Route::get('/xrp', 'XrpController@index')->name('xrp');
Route::get('/line', 'LineController@index')->name('line');


