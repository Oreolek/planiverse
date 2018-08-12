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

Route::get('/', function() {
	return redirect()->route('public');
});

Route::get('/timeline/public', 'TimelineController@public_timeline')
	->name('public');

Route::get('/timeline/friends', 'TimelineController@home_timeline')
	->name('friends');

Route::get('/login', 'LoginController@login');

Route::get('/callback', 'LoginController@callback');
