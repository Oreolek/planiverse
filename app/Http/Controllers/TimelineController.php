<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Http\Request;

use App\Helpers\Links;
use App\Helpers\PaginationParameters;

/**
 * Controller for the public and user timelines.
 */
class TimelineController extends Controller
{

    /**
     * Show the public timeline.
     *
     * The user does not have to be logged in to see this.
     *
     * @param Request $request The request containing pagination parameters.
     *
     * @return Illuminate\View\View The public timeline.
     */
    public function public_timeline(Request $request)
    {
        $params = (new PaginationParameters($request))
            ->to_array();
        $params['local'] = true;

        # Query for the public timeline.
        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/timelines/public', $params);

        $vars = [
            'statuses' => $timeline,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'links' => new Links(
                Mastodon::getResponse()->getHeader('link'),
                'public'
           	)
        ];

        return view('public_timeline', $vars);
    }

    /**
     * Show the home timeline.
     *
     * @param Request $request The request containing pagination parameters.
     *
     * @return Illuminate\View\View The user's home timeline.
     */
    public function home_timeline(Request $request)
    {
        $user = session('user');

        $params = (new PaginationParameters($request))
            ->to_array();

        # Query for the user's timeline.
        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/timelines/home', $params);

        $vars = [
            'statuses' => $timeline,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'links' => new Links(
                Mastodon::getResponse()->getHeader('link'),
                'home'
           	)
        ];

        return view('home_timeline', $vars);
    }
}
