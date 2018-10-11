<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Http\Request;

use App\Helpers\Links;
use App\Helpers\PaginationParameters;

/**
 * Controller for the notifications page.
 */
class NotificationsController extends Controller
{
	/**
	 * Get and display notifications.
	 *
	 * @param Request $request The request containing pagination parameters.
	 *
	 * @return Illuminate\View\View The notifications page.
	 */
    public function get_notifications(Request $request)
    {
        $user = session('user');
        $params = (new PaginationParameters($request))
            ->to_array();

        # Fetch notifications from the API.
        $notifications = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/notifications', $params);

        $vars = [
            'notifications' => $notifications,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'links' => new Links(
                Mastodon::getResponse()->getHeader('link'),
                'notifications'
           	)
        ];

        return view('notifications', $vars);
    }
}
