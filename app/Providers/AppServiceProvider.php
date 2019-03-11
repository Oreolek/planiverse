<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('is_reblogged', function ($status) {
            return (isset($status['reblogged']) && $status['reblogged'] === true);
        });
        Blade::if('is_reblog', function ($status) {
            return (isset($status['reblog']) && !empty($status['reblog']));
        });
        Blade::if('is_muted', function ($status) {
            return (isset($status['muted']) && $status['muted'] === true);
        });
        Blade::if('is_favourited', function ($status) {
            return (isset($status['favourited']) && $status['favourited'] === true);
        });
        Blade::if('has_spoiler', function ($status) {
            return (isset($status['spoiler_text']) && !empty($status['spoiler_text']));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
