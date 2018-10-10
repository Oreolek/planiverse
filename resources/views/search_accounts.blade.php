<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $mastodon_domain }} | Search</title>

        <link rel="stylesheet" href="{{ url('css/styles.css') }}" />
    </head>
    <body>
        <h1>{{ $mastodon_domain }} | Search</h1>

        @component('navigation')
        @endcomponent

        <form method="post" action="{{ route('search_accounts') }}">
            <input type="text" name="account" placeholder="Account" required autofocus />
            <input type="submit" value="Search" />
            {{ csrf_field() }}
        </form>

        <ul>
            @foreach ($accounts as $account)
                <li>
                    <span class="account" title="{{ $account['acct'] }}">
                        <a href="{{ $account['url'] }}">
                            <img
                                src="{{ $account['avatar'] }}"
                                alt="{{ $account['acct'] }}"
                                class="avatar"
                            />
                            {{ $account['display_name'] }}
                            @if ($account['bot'] ?? false) &#129302; @endif
                            @if ($account['locked']) &#128274; @endif
                        </a>
                    </span>
                </li>
            @endforeach
        </ul>
    </body>
</html>
