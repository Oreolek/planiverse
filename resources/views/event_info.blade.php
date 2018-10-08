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
