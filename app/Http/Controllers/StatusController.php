<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use GuzzleHttp\Psr7;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function show_status(Request $request, string $status_id)
    {
        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/statuses/' . $status_id);

        $vars = [
            'status' => $status,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'logged_in' => session()->has('user')
        ];

        return view('show_status', $vars);
    }
}
