<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $mastodon_domain }} | Timeline</title>

        <link rel="stylesheet" href="/css/styles.css" />
    </head>
    <body>
        <h1>{{ $mastodon_domain }} | Timeline</h1>

        <form method="post" action="/timeline/home">
            <input type="text" name="spoiler_text" placeholder="Spoiler/Warning" />
            <textarea rows="4" name="status" placeholder="Status" required autofocus></textarea>
            <input type="submit" value="Post" />
            {{ csrf_field() }}
        </form>

        @foreach ($statuses as $status)
            @component('status', ['status' => $status])
            @endcomponent
        @endforeach

        @if ($links['prev'] !== null)
            <span><a href="{{ $links['prev'] }}">Previous</a></span>
        @endif

        @if ($links['next'] !== null)
            <span><a href="{{ $links['next'] }}">Next</a></span>
        @endif
    </body>
</html>
