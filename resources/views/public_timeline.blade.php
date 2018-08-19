<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $mastodon_domain }} | Public Timeline</title>

        <link rel="stylesheet" href="/css/styles.css" />
    </head>
    <body>
        <h1>{{ $mastodon_domain }} | Public Timeline</h1>

        @foreach ($statuses as $status)
            @component('status', ['status' => $status])
            @endcomponent
        @endforeach

        @component('pagination', ['links' => $links])
        @endcomponent
    </body>
</html>
