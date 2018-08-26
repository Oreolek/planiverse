<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Http\Request;
use App\Helpers\Pagination;

class TimelineController extends Controller
{
    public function public_timeline(Request $request)
    {
        $params = Pagination::compile_params($request);
        $params['local'] = true;

        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/timelines/public', $params);

        $vars = [
            'statuses' => $timeline,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'links' => Pagination::compile_links('public')
        ];

        return view('public_timeline', $vars);
    }

    public function home_timeline(Request $request)
    {
        $user = session('user');

        $params = Pagination::compile_params($request);

        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/timelines/home', $params);

        $vars = [
            'statuses' => $timeline,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'links' => Pagination::compile_links('home')
        ];

        return view('home_timeline', $vars);
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
}
