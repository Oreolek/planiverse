<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $mastodon_domain }} | {{ $account['acct'] }}</title>

        <link rel="stylesheet" href="{{ url('css/styles.css') }}" />
    </head>
    <body>
        <h1>{{ $mastodon_domain }} | {{ $account['acct'] }}</h1>

        @component('navigation')
        @endcomponent

        <div>
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
            {!! $account['note'] !!}
        </div>
    </body>
</html>
