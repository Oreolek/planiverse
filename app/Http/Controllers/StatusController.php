<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function show_status(Request $request, string $status_id)
    {
        if (session()->has('status'))
        {
            // The user is coming here after peforming an action on a status,
            // in which case we don't need to re-query it.

            $status = session('status');
        }
        else
        {
            // If the status hasn't been returned from performing an action,
            // we need to query for it.

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

    public function reblog_status(Request $request, string $status_id)
    {
        $user = session('user');

        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/reblog');

        return redirect()->route('status', ['status_id' => $status_id])
            ->with('status', $status);
    }

    public function unreblog_status(Request $request, string $status_id)
    {
        $user = session('user');

        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/unreblog');

        return redirect()->route('status', ['status_id' => $status_id])
            ->with('status', $status);
    }

    public function favourite_status(Request $request, string $status_id)
    {
        $user = session('user');

        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/favourite');

        return redirect()->route('status', ['status_id' => $status_id])
            ->with('status', $status);
    }

    public function unfavourite_status(Request $request, string $status_id)
    {
        $user = session('user');

        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/unfavourite');

        return redirect()->route('status', ['status_id' => $status_id])
            ->with('status', $status);
    }

    public function post_status(Request $request)
    {
        $user = session('user');

        # Verify we have an actual status to post.
        if (!$request->has('status'))
        {
            abort(400);
        }

        $params = [
            'status' => $request->status
        ];

        $inputs = [
            'in_reply_to_id',
            'media_ids',
            'sensitive',
            'spoiler_text',
            'visibility',
            'language'
        ];

        foreach ($inputs as $input)
        {
            if ($request->has($input))
            {
                $params[$input] = $request->input($input);
            }
        }

        $new_status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses', $params);

        return redirect()->route('home');
    }

    public function context(Request $request, string $status_id)
    {
        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/statuses/' . $status_id);

        $context = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/statuses/' . $status_id . '/context');

        $vars = [
            'status' => $status,
            'context' => $context,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1]
        ];

        return view('context', $vars);
    }
}
