<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use GuzzleHttp\Psr7;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function show_status(Request $request, string $status_id)
    {
        // The user has a session and may be here to favourite/unfavourite
        // a status.
        if (session()->has('user') && $request->has('action'))
        {
            $user = session('user');
            if ($request->action === 'favourite')
            {
                $status = Mastodon::domain(env('MASTODON_DOMAIN'))
                    ->token($user->token)
                    ->post('/statuses/' . $status_id . '/favourite');
            }
            elseif ($request->action === 'unfavourite')
            {
                $status = Mastodon::domain(env('MASTODON_DOMAIN'))
                    ->token($user->token)
                    ->post('/statuses/' . $status_id . '/unfavourite');
            }
            elseif ($request->action === 'reblog')
            {
                $status = Mastodon::domain(env('MASTODON_DOMAIN'))
                    ->token($user->token)
                    ->post('/statuses/' . $status_id . '/reblog');
            }
            elseif ($request->action === 'unreblog')
            {
                $status = Mastodon::domain(env('MASTODON_DOMAIN'))
                    ->token($user->token)
                    ->post('/statuses/' . $status_id . '/unreblog');
            }
        }
        
        // If the status hasn't been returned from performing an action on it,
        // we need to query for it.
        if (!isset($session))
        {
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
}
