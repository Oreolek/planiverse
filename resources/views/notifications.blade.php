<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $mastodon_domain }} | Notifications</title>

        <link rel="stylesheet" href="{{ url('css/styles.css') }}" />
    </head>
    <body>
        <h1>{{ $mastodon_domain }} | Notifications</h1>

        @component('navigation')
        @endcomponent

        <ul>
            @foreach ($notifications as $notification)
                @component('notification', ['notification' => $notification])
                @endcomponent
            @endforeach
        </ul>

        @component('pagination', ['links' => $links])
        @endcomponent
    </body>
</html>
