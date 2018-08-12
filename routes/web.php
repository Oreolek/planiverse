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
	if (!session()->has('user'))
    {
        return redirect()->route('public');
    }
	else
	{
		return redirect()->route('home');
	}
});

Route::get('/timeline/public', 'TimelineController@public_timeline')
	->name('public');

Route::get('/timeline/home', 'TimelineController@home_timeline')
	->name('home');

Route::get('/login', 'LoginController@login')
	->name('login');

Route::get('/callback', 'LoginController@callback');
