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

        if (session()->has('relationship'))
        {
            // The user is coming here after peforming an action on an account,
            // in which case we don't need to re-query the relationship.

            $relationship = session('relationship');
        }
        else
        {
            // If the relationship hasn't been returned from performing an action,
            // we need to query for it.

            $relationships = Mastodon::domain(env('MASTODON_DOMAIN'))
                ->token($user->token)
                ->get('/accounts/relationships', ['id' => $account_id]);

            $relationship = $relationships[0];
        }

        $vars = [
            'account' => $account,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'relationship' => $relationship
        ];

        return view('account', $vars);
    }

    public function follow_account(Request $request, string $account_id)
    {
        $user = session('user');

        $relationship = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/accounts/' . $account_id . '/follow');

        return redirect()->route('account', ['account_id' => $account_id])
            ->with('relationship', $relationship);
    }

    public function unfollow_account(Request $request, string $account_id)
    {
        $user = session('user');

        $relationship = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/accounts/' . $account_id . '/unfollow');

        return redirect()->route('account', ['account_id' => $account_id])
            ->with('relationship', $relationship);
    }
}
