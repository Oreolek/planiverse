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
    ->name('reblog')
    ->middleware('authorize');

Route::get('/status/{status_id}/unreblog', 'StatusController@unreblog_status')
    ->name('unreblog')
    ->middleware('authorize');

Route::get('/status/{status_id}/favourite', 'StatusController@favourite_status')
    ->name('favourite')
    ->middleware('authorize');

Route::get('/status/{status_id}/unfavourite', 'StatusController@unfavourite_status')
    ->name('unfavourite')
    ->middleware('authorize');

Route::get('/status/{status_id}/thread', 'StatusController@context')
    ->name('thread');

Route::post('/status', 'StatusController@post_status')
    ->name('post_status')
    ->middleware('authorize');

Route::get('/notifications', 'NotificationsController@get_notifications')
    ->name('notifications')
    ->middleware('authorize');

Route::get('/account/{account_id}', 'AccountController@view_account')
    ->name('account')
    ->middleware('authorize');

Route::get('/account/{account_id}/follow', 'AccountController@follow_account')
    ->name('follow')
    ->middleware('authorize');

Route::get('/account/{account_id}/unfollow', 'AccountController@unfollow_account')
    ->name('unfollow')
    ->middleware('authorize');

Route::get('/accounts/search', 'AccountController@show_search')
    ->name('show_search_accounts')
    ->middleware('authorize');

Route::post('/accounts/search', 'AccountController@search')
    ->name('search_accounts')
    ->middleware('authorize');

Route::get('/login', 'LoginController@login')
    ->name('login');

Route::get('/callback', 'LoginController@callback');
