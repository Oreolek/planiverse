<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Http\Request;
use App\Helpers\Pagination;

class NotificationsController extends Controller
{
    public function get_notifications(Request $request)
    {
        $user = session('user');
        $params = Pagination::compile_params($request);

        $notifications = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/notifications', $params);

        $vars = [
            'notifications' => $notifications,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'links' => Pagination::compile_links('notifications')
        ];

        return view('notifications', $vars);
    }
}
