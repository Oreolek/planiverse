<span title="{{ $account['acct'] }}">
    <a href="{{ $account['url'] }}">
        <img
            src="{{ $account['avatar'] }}"
            alt="{{ $account['acct'] }}"
            class="avatar"
        />
        {{ $account['display_name'] }}
    </a>
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
