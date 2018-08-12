<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;

class TimelineController extends Controller
{
    public function public_timeline()
    {
        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/timelines/public', ['local' => true]);

        return view('timeline', ['statuses' => $timeline]);
    }

    public function home_timeline()
    {
        if (!session()->has('user'))
        {
            return redirect()->route('login');
        }

        $user = session('user');
        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/timelines/home');

        return view('timeline', ['statuses' => $timeline]);
    }
}
