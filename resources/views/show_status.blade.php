<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $mastodon_domain }} | Status</title>

        <link rel="stylesheet" href="{{ url('css/styles.css') }}" />
    </head>
    <body>
        <h1>{{ $mastodon_domain }} | Status</h1>

        @component('navigation')
        @endcomponent

        <ul>
            @component('status', ['status' => $status])
            @endcomponent
        </ul>

        @if ($logged_in)
            <form method="post" action="{{ route('post_status') }}">
                <input
                    type="text"
                    name="spoiler_text"
                    placeholder="Spoiler/Warning"
                    value="{{ $status['spoiler_text'] }}"
                />
                <textarea rows="4" name="status" placeholder="Reply" required autofocus>{{ $reply_mentions }} </textarea>
                <select name="visibility">
                    <option value="public" @if ($status['visibility'] === 'public') selected @endif>Public</option>
                    <option value="unlisted" @if ($status['visibility'] === 'unlisted') selected @endif>Unlisted</option>
                    <option value="private" @if ($status['visibility'] === 'private') selected @endif>Private</option>
                    <option value="direct" @if ($status['visibility'] === 'direct') selected @endif>Direct</option>
                </select>
                <input type="submit" value="Post" />
                <input type="hidden" name="in_reply_to_id" value="{{ $status['id'] }}" />
                {{ csrf_field() }}
            </form>
        @endif
    </body>
</html>
