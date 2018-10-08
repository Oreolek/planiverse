<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function view_account(Request $request, string $account_id)
    {
        $user = session('user');

        $account = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/accounts/' . $account_id);

        $vars = [
            'account' => $account,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1]
        ];

        return view('account', $vars);
    }
}
