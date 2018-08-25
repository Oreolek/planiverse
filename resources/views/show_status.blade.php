<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $mastodon_domain }} | Status</title>

        <link rel="stylesheet" href="/css/styles.css" />
    </head>
    <body>
        <h1>{{ $mastodon_domain }} | Status</h1>

        @component('navigation')
        @endcomponent

        @component('status', ['status' => $status])
        @endcomponent

        @if ($logged_in)
            <form method="post" action="/timeline/home">
                <input
                    type="text"
                    name="spoiler_text"
                    placeholder="Spoiler/Warning"
                    value="{{ $status['spoiler_text'] }}"
                />
                <textarea rows="4" name="status" placeholder="Reply" required autofocus>{{ '@' . $status['account']['acct'] }} @foreach ($status['mentions'] as $mention){{ '@' . $mention['acct'] }} @endforeach</textarea>
                <input type="submit" value="Post" />
                <input type="hidden" name="in_reply_to_id" value="{{ $status['id'] }}" />
                {{ csrf_field() }}
            </form>
        @endif
    </body>
</html>
