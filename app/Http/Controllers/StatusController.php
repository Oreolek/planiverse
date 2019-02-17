<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Http\Request;

/**
 * Controller for managing Statuses.
 */
class StatusController extends Controller
{
    /**
     * Show a Status.
     *
     * Conditionally shows a reply form if the user is logged in.
     *
     * @param string $status_id The ID of the Status to display.
     *
     * @return Illuminate\View\View The Status view page.
     */
    public function show_status(string $status_id)
    {
        if (session()->has('status'))
        {
            # The user is coming here after peforming an action on a status,
            # in which case we don't need to re-query it.

            $status = session('status');
        }
        else
        {
            # If the status hasn't been returned from performing an action,
            # we need to query for it.

            if (session()->has('user'))
            {
                # If the user is logged in, send the token to ensure they
                # can see private and direct statuses. Otherwise the API
                # returns a 404.

                $status = Mastodon::domain(env('MASTODON_DOMAIN'))
                    ->token(session('user')->token)
                    ->get('/statuses/' . $status_id);
            }
            else
            {
                $status = Mastodon::domain(env('MASTODON_DOMAIN'))
                    ->get('/statuses/' . $status_id);
            }
        }

        # Compile a list of accounts to include in the reply.
        $reply_mentions = [];
        if (session()->has('user'))
        {
            # Include all mentions, excluding the current user.
            foreach ($status['mentions'] as $mention)
            {
                if ($mention['acct'] !== session('user')->user['acct'])
                {
                    array_push($reply_mentions, '@' . $mention['acct']);
                }
	    }
        }

        $vars = [
            'status' => $status,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1],
            'logged_in' => session()->has('user'),
            'reply_mentions' => implode(' ', $reply_mentions)
        ];

        return view('show_status', $vars);
    }

    /**
     * Reblog a Status.
     *
     * @param string $status_id The ID of the Status to reblog.
     *
     * @return Illuminate\Routing\Redirector Redirect to the Status view page.
     */
    public function reblog_status(string $status_id)
    {
        $user = session('user');

        # Reblog request to the API.
        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/reblog');

        return redirect()->route('status', ['status_id' => $status_id])
            ->with('status', $status);
    }

    /**
     * Unreblog a Status.
     *
     * @param string $status_id The ID of the Status to unreblog.
     *
     * @return Illuminate\Routing\Redirector Redirect to the Status view page.
     */
    public function unreblog_status(string $status_id)
    {
        $user = session('user');

        # Unreblog request to the API.
        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/unreblog');

        return redirect()->route('status', ['status_id' => $status_id])
            ->with('status', $status);
    }

    /**
     * Favourite a Status.
     *
     * @param string $status_id The ID of the Status to favourite.
     *
     * @return Illuminate\Routing\Redirector Redirect to the Status view page.
     */
    public function favourite_status(string $status_id)
    {
        $user = session('user');

        # Favourite the Status.
        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/favourite');

        return redirect()->route('status', ['status_id' => $status_id])
            ->with('status', $status);
    }

    /**
     * Unfavourite a Status.
     *
     * @param string $status_id The ID of the Status to unfavourite.
     *
     * @return Illuminate\Routing\Redirector Redirect to the Status view page.
     */
    public function unfavourite_status(string $status_id)
    {
        $user = session('user');

        # Unfavourite request to the API.
        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses/' . $status_id . '/unfavourite');

        return redirect()->route('status', ['status_id' => $status_id])
            ->with('status', $status);
    }

    /**
     * Post a new Status.
     *
     * @param Request $request Request containing the form submission.
     *
     * @return Illuminate\Routing\Redirector Redirect to the home timeline page.
     */
    public function post_status(Request $request)
    {
        $user = session('user');

        # Verify we have an actual status to post.
        if (!$request->has('status'))
        {
            abort(400);
        }

        $params = [
            'status' => $request->status
        ];

        $inputs = [
            'in_reply_to_id',
            'media_ids',
            'sensitive',
            'spoiler_text',
            'visibility',
            'language'
        ];

        # Grab each of the optional inputs from the form.
        # Not all of these are present in the HTML of the view yet.
        foreach ($inputs as $input)
        {
            if ($request->has($input))
            {
                $params[$input] = $request->input($input);
            }
        }

        # Post the Status via the API.
        $new_status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->post('/statuses', $params);

        return redirect()->route('home');
    }

    /**
     * Show the context of a Status.
     *
     * Show a Status in its thread of ancestors and descendants.
     *
     * @param string $status_id The ID of the Status to display.
     *
     * @return Illuminate\View\View The context view page.
     */
    public function context(string $status_id)
    {
        # Get the Status itself.
        $status = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/statuses/' . $status_id);

        # Get the context.
        $context = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/statuses/' . $status_id . '/context');

        $vars = [
            'status' => $status,
            'context' => $context,
            'mastodon_domain' => explode('//', env('MASTODON_DOMAIN'))[1]
        ];

        return view('context', $vars);
    }
}
