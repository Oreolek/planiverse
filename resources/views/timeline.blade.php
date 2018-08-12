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
            @component('status', ['status' => $status])
            @endcomponent
        @endforeach
    </body>
</html>
