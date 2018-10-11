<?php

namespace App\Helpers;

use Mastodon;
use GuzzleHttp\Psr7;

/**
 * Container class to hold pagination links.
 */
class Links
{
    public $next;
    public $prev;

    /**
	 * Generate pagination links for the given route.
	 *
	 * After receiving a paginated response from the Mastodon API,
	 * parse the "link" header from the Mastodon response and use
	 * it to generate next and previous links for the requested route.
	 *
	 * @param string[] $links_header An array of values of the "link" header,
	 *    as might be returned from Guzzle's getHeader() method.
	 * @param string $route The name of the route to generate links for.
	 */
    function __construct(array $links_header, string $route)
    {
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
                        $this->$link['rel'] = route($route) . '?' . $query_param;
                    }
                }
            }
        }
    }
}