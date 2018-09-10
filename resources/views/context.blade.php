<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $mastodon_domain }} | Thread</title>

        <link rel="stylesheet" href="{{ url('css/styles.css') }}" />
    </head>
    <body>
        <h1>{{ $mastodon_domain }} | Thread</h1>

        @component('navigation')
        @endcomponent

        @foreach ($context['ancestors'] as $ancestor)
            @component('status', ['status' => $ancestor])
            @endcomponent
        @endforeach

        @component('status', ['status' => $status])
        @endcomponent

        @foreach ($context['descendants'] as $descendant)
            @component('status', ['status' => $descendant])
            @endcomponent
        @endforeach
    </body>
</html>
