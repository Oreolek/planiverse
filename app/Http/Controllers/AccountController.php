<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Http\Request;

use App\Helpers\Links;
use App\Helpers\PaginationParameters;

/**
 * Controller for Account functions.
 */
class AccountController extends Controller
{
    /**
     * Fetch an Account from the Mastodon API and present it to the user.
     *
     * The returned view will show the details of the Account and the actions
     * the user can perform (follow, mute, etc.)
     *
     * @param string $account_id The ID of the Account to query.
     *
     * @return Illuminate\View\View The Account details page.
     */
    public function view_account(string $account_id)
    {
        $user = session('user');

        # Get the Account from the API.
        $account = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/accounts/' . $account_id);

        if (session()->has('relationship'))
        {
            # The user is coming here after peforming an action on an account,
            # in which case we don't need to re-query the relationship.

            $relationship = session('relationship');
        }
        else
        {
            # If the relationship hasn't been returned from performing an action,
            # we need to query for it.

            $relationships = Mastodon::domain(env('MASTODON_DOMAIN'))
                ->token($user->token)
                ->get('/accounts/relationships', ['id' => $account_id]);

            $relationship = $relationships[0];
        }

        # Get the Account's Statuses from the API.
        $statuses = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/accounts/' . $account_id . '/statuses');

        $vars = [
            'account' => $account,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'relationship' => $relationship,
            'statuses' => $statuses,
            'links' => new Links(
                Mastodon::getResponse()->getHeader('link'),
                'public'
                )
        ];

        return view('account', $vars);
    }

    /**
     * Follow an Account and redirect to the "account" view.
     *
     * @param string $account_id The ID of the Account to query.
     *
     * @return Illuminate\Routing\Redirector Redirect to the account details page.
     */
    public function follow_account(string $account_id)
    {
        $user = session('user');

        # Query the API to follow the Account.
        $relationship = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/accounts/' . $account_id . '/follow');

        # Redirect with the resulting relationship as a temporary session variable.
        return redirect()->route('account', ['account_id' => $account_id])
            ->with('relationship', $relationship);
    }

    /**
     * Unfollow an Account and redirect to the "account" view.
     *
     * @param string $account_id The ID of the Account to query.
     *
     * @return Illuminate\Routing\Redirector Redirect to the account details page.
     */
    public function unfollow_account(Request $request, string $account_id)
    {
        $user = session('user');

        # Unfollow via the API.
        $relationship = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/accounts/' . $account_id . '/unfollow');

        # Redirect with the resulting relationship as a temporary session variable.
        return redirect()->route('account', ['account_id' => $account_id])
            ->with('relationship', $relationship);
    }
}
