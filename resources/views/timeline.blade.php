<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>jank.town | Public Timeline</title>

        <link rel="stylesheet" href="/css/styles.css" />
    </head>
    <body>
        @foreach ($statuses as $status)
            <div class="status">
                <div class="tooltip">
                    <a href="{{ $status['account']['url'] }}">
                        <img src="{{ $status['account']['avatar'] }}" alt="{{ $status['account']['acct'] }}" class="avatar" />
                        {{ $status['account']['display_name'] }}
                    </a>
                    <span class="tooltiptext">{{ $status['account']['acct'] }}</span>
                </div>
                <p>{!! $status['content'] !!}</p>
            </div>
        @endforeach
    </body>
</html>
