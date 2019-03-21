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

Route::get('/', 'HomeController@index')->name('home');
Route::post('/', 'HomeController@subscribe')->name('subscribe');

Route::get('/video/{streamerId}/{streamerName}', 'VideoController')->name('video');
Route::any('/webhook', 'WebhookController@webhook')->name('wh');

Route::get('login/{provider}', 'SocialController@redirect');
Route::get('login/{provider}/callback','SocialController@callback');