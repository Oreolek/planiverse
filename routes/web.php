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
    ->name('home')
    ->middleware('authorize');

Route::get('/status/{status_id}', 'StatusController@show_status')
    ->name('status');

Route::get('/status/{status_id}/reblog', 'StatusController@reblog_status')
    ->middleware('authorize');

Route::get('/status/{status_id}/unreblog', 'StatusController@unreblog_status')
    ->middleware('authorize');

Route::get('/status/{status_id}/favourite', 'StatusController@favourite_status')
    ->middleware('authorize');

Route::get('/status/{status_id}/unfavourite', 'StatusController@unfavourite_status')
    ->middleware('authorize');

Route::get('/status/{status_id}/thread', 'StatusController@context');

Route::post('/status', 'StatusController@post_status')
    ->middleware('authorize');

Route::get('/notifications', 'NotificationsController@get_notifications')
    ->name('notifications')
    ->middleware('authorize');

Route::get('/login', 'LoginController@login')
    ->name('login');

Route::get('/callback', 'LoginController@callback');
