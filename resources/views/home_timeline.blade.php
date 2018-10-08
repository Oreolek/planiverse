<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $mastodon_domain }} | Timeline</title>

        <link rel="stylesheet" href="{{ url('css/styles.css') }}" />
    </head>
    <body>
        <h1>{{ $mastodon_domain }} | Timeline</h1>

        @component('navigation')
        @endcomponent

        <form method="post" action="{{ route('post_status') }}">
            <input type="text" name="spoiler_text" placeholder="Spoiler/Warning" />
            <textarea rows="4" name="status" placeholder="Status" required autofocus></textarea>
            <select name="visibility">
                <option value="public">Public</option>
                <option value="unlisted">Unlisted</option>
                <option value="private">Private</option>
                <option value="direct">Direct</option>
            </select>
            <input type="submit" value="Post" />
            {{ csrf_field() }}
        </form>

        <ul>
            @foreach ($statuses as $status)
                @component('status', ['status' => $status])
                @endcomponent
            @endforeach
        </ul>

        @component('pagination', ['links' => $links])
        @endcomponent
    </body>
</html>
