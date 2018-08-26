<?php

namespace App\Helpers;

use Mastodon;
use GuzzleHttp\Psr7;
use Illuminate\Http\Request;

class Pagination
{
    public static function compile_links(string $route)
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

    public static function compile_params(Request $request)
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
