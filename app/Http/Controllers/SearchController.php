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
     * Show the page that lets users search across Accounts and Statuses.
     *
     * @return Illuminate\View\View The search page.
     */
    public function show_search()
    {
        if (session()->has('results'))
        {
            # The user is coming here after peforming a search.

            $results = session('results');
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
        if (!$request->has('search_term'))
        {
            abort(400);
	}

        # Query the search end-point.
        $results = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/search', ['q' => $request->search_term]);

        return redirect()->route('show_search')
            ->with('results', $results);
    }
}
