<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Http\Request;

/**
 * Controller for search functions.
 */
class SearchController extends Controller
{
    /**
     * Process a search request.
     *
     * @param Request $request The POST request with search parameters.
     *
     * @return Illuminate\Routing\Redirector Redirect to the search page.
     */
    public function search(Request $request)
    {
        $user = session('user');

        # Verify we have an actual search term.
        if ($request->has('search_term'))
        {
            # Query the search end-point.
            $results = Mastodon::domain(env('MASTODON_DOMAIN'))
                ->token($user->token)
                ->get('/search', ['q' => $request->search_term]);
	}
        else
        {
            $results = null;
        }

        $vars = [
            'results' => $results,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1]
        ];

        return view('search', $vars);
    }
}
