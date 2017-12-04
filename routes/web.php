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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/hashwords', 'HashWordsController@index')->middleware('auth');
Route::get('gethashword', 'HashWordsController@show')->middleware('auth');
Route::get('nouserwords', 'HashWordsController@nouserwords')->middleware('auth');
Route::get('encode', 'HashWordsController@encode')->middleware('auth');
Route::get('deletehash', 'HashWordsController@deleteHash')->middleware('auth');
Route::get('editword', 'WordsController@index')->middleware('auth');
