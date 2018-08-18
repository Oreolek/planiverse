<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use GuzzleHttp\Psr7;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function show_status(string $status_id)
    {
        if (session()->has('return_status'))
        {
            // This route can be called as a redirect after favouriting, etc.,
            // in which case the status returned by the API will have been stored
            // in the user's session.
            $status = session('return_status');
            session()->forget('return_status');
        }
        else
        {
            // If the status isn't in the session, we need to query for it.
            $status = Mastodon::domain(env('MASTODON_DOMAIN'))
                ->get('/statuses/' . $status_id);
        }

        $vars = [
            'status' => $status,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'logged_in' => session()->has('user')
        ];

        return view('show_status', $vars);
    }

    public function favourite_status(string $status_id)
    {
        $user = session('user');

        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/favourite');

        session(['return_status' => $status]);

        return redirect()->route('status', ['status_id' => $status_id]);
    }

    public function unfavourite_status(string $status_id)
    {
        $user = session('user');

        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/unfavourite');

        session(['return_status' => $status]);

        return redirect()->route('status', ['status_id' => $status_id]);
    }
}
