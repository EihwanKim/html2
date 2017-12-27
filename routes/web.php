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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/chart', 'ChartController@index')->name('chart');
Route::get('/simulation/{coin_name}', 'SimulationController@index')->name('simulation');
Route::get('/bithumb', 'BithumbController@index')->name('bithumb');
Route::get('/xrp', 'XrpController@index')->name('xrp');
Route::get('/line', 'LineController@index')->name('line');
