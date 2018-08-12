<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;
use Illuminate\Support\Facades\DB;
use Socialite;

class LoginController extends Controller
{
    public function login()
    {
        # Check if this app is already registered.
        $app = DB::table('apps')->where('server', env('MASTODON_DOMAIN'))->first();

        if ($app == null)
        {
            # Register this app with the API server.
            $app_info = Mastodon::domain(env('MASTODON_DOMAIN'))
                ->createApp(env('APP_NAME'), env('MASTODON_REDIRECT'), config('services.mastodon.scopes'), env('APP_URL'));

            $client_id = $app_info['client_id'];
            $client_secret = $app_info['client_secret'];

            DB::table('apps')->insert([
                'server' => env('MASTODON_DOMAIN'),
                'client_name' => env('APP_NAME'),
                'redirect_uris' => env('MASTODON_REDIRECT'),
                'scopes' => join(' ', config('services.mastodon.scopes')),
                'website' => env('APP_URL'),
                'response_id' => $app_info['id'],
                'client_id' => $client_id,
                'client_secret' => $client_secret
            ]);
        }
        else
        {
            $client_id = $app->client_id;
            $client_secret = $app->client_secret;
        }

        # Set configs required for the redirect.
        config(['services.mastodon.domain' => env('MASTODON_DOMAIN')]);
        config(['services.mastodon.client_id' => $client_id]);
        config(['services.mastodon.client_secret' => $client_secret]);

        # Save this info to the session.
        session(['mastodon_domain' => env('MASTODON_DOMAIN')]);
        session(['client_id' => $client_id]);
        session(['client_secret' => $client_secret]);

        # Redirect the user to their instance to log in.
        return Socialite::driver('mastodon')->redirect();
    }

    public function callback()
    {
        $domain = session('mastodon_domain');
        $client_id = session('client_id');
        $client_secret = session('client_secret');

        config(['services.mastodon.domain' => $domain]);
        config(['services.mastodon.client_id' => $client_id]);
        config(['services.mastodon.client_secret' => $client_secret]);

        # Get user data (token, etc.)
        $user = Socialite::driver('mastodon')->user();
        session(['user' => $user]);

        return redirect()->route('friends');
    }
}
