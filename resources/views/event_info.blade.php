<aside>
    <span class="account" title="{{ $account['acct'] }}">
        <a href="{{ route('account', ['account_id' => $account['id']]) }}">
            <img
                src="{{ $account['avatar'] }}"
                alt="{{ $account['acct'] }}"
                class="avatar"
            />
            {{ $account['display_name'] }}
        </a>
    </span>

    <span class="event-indicators">
        @if ($visibility !== null)
            <span class="visibility">
                @if ($visibility === 'public')
                    <span title="public">&#9675;</span>
                @elseif ($visibility === 'unlisted')
                    <span title="unlisted">&#9676;</span>
                @elseif ($visibility === 'private')
                    <span title="private">&#128274;</span>
                @elseif ($visibility === 'direct')
                    <span title="direct">&#9993;</span>
                @endif
            </span>
        @endif

        @if ($type !== null)
            <span class="event-action">
                @if ($type === 'mention')
                    mentioned
                @elseif ($type === 'reblog')
                    reblogged
                @elseif ($type === 'favourite')
                    favourited
                @elseif ($type === 'follow')
                    followed you.
                @elseif ($type === 'reply')
                    &#8624;
                @endif
            </span>
        @endif
    </span>

    <time datetime="{{ $created_at }}">
        @php
            $created_at_datetime = new Carbon\Carbon($created_at);
        @endphp
        @if (env('SHOW_BEATS') === true)
            {{ '@' . $created_at_datetime->format('B') }} |
        @endif
        {{ $created_at_datetime->diffForHumans(null, false, true) }}
    </time>
</aside>
