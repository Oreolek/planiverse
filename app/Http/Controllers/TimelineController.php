<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use GuzzleHttp\Psr7;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function public_timeline(Request $request)
    {
        $params = $this->compile_params($request);
        $params['local'] = true;

        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/timelines/public', $params);

        $vars = [
            'statuses' => $timeline,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'links' => $this->compile_links('public')
        ];

        return view('public_timeline', $vars);
    }

    public function home_timeline(Request $request)
    {
        $user = session('user');

        $params = $this->compile_params($request);

        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/timelines/home', $params);

        $vars = [
            'statuses' => $timeline,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'links' => $this->compile_links('home')
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

    private function compile_links(string $route)
    {
        $links = [
            'next' => null,
            'prev' => null
        ];

        # Parse out the links header returned from the Mastodon API.
        $links_header = Mastodon::getResponse()->getHeader('link');
        foreach (Psr7\parse_header($links_header) as $link)
        {
            # Find the prev and next links.
            if ($link['rel'] === 'prev' || $link['rel'] === 'next')
            {
                $url = parse_url(trim($link[0], '<>'));
                foreach (explode('&', $url['query']) as $query_param)
                {
                    # Grab the ID query parameters from the link.
                    if (strpos($query_param, 'max_id=') === 0
                        || strpos($query_param, 'since_id=') === 0)
                    {
                        # Construct new links with the correct offset.
                        $links[$link['rel']] = route($route) . '?' . $query_param;
                    }
                }
            }
        }

        return $links;
    }

    private function compile_params(Request $request)
    {
        $params = [];

        if ($request->has('max_id') && $request->has('since_id'))
        {
            # This scenario makes no sense. Someone's trying to dicker with the URL.
            abort(400);
        }
        elseif ($request->has('max_id'))
        {
            $params['max_id'] = $request->max_id;
        }
        elseif ($request->has('since_id'))
        {
            $params['since_id'] = $request->since_id;
        }

        return $params;
    }
}
