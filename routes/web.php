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

Route::get('/', 'HomeController')->name('home');
Route::get('/video/{streamer}', 'VideoController')->name('video');

Route::get('login/{provider}', 'SocialController@redirect');
Route::get('login/{provider}/callback','SocialController@callback');