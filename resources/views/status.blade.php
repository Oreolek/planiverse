<article>
    <span title="{{ $status['account']['acct'] }}">
        <a href="{{ $status['account']['url'] }}">
            <img
                src="{{ $status['account']['avatar'] }}"
                alt="{{ $status['account']['acct'] }}"
                class="avatar"
            />
            {{ $status['account']['display_name'] }}
        </a>
    </span>
    <time datetime="{{ $status['created_at'] }}">
        @php
            $created_at = new Carbon\Carbon($status['created_at']);
        @endphp
        @if (env('SHOW_BEATS') === true)
            {{ '@' . $created_at->format('B') }} |
        @endif
        {{ $created_at->diffForHumans(null, false, true) }}
    </time>
    @if ($status['spoiler_text'] !== '')
        <details>
            <summary>{{ $status['spoiler_text'] }}</summary>

            @component('status_content', ['status' => $status])
            @endcomponent
        </details>
    @else
        @component('status_content', ['status' => $status])
        @endcomponent
    @endif

    <div class="actions">
        <!-- Reply -->
        <span>
            <a href="/status/{{ $status['id'] }}">&#8629;</a>
        </span>

        <!-- Reblog -->
        <span>
            @if ($status['reblogged'])
                <span class="reblogged">
                    <a href="/status/{{ $status['id'] }}?action=unreblog">&#8634;</a>
                </span>
            @else
                <a href="/status/{{ $status['id'] }}?action=reblog">&#8634;</a>
            @endif
            {{ $status['reblogs_count'] }}
        </span>

        <!-- Favourite -->
        <span>
            @if ($status['favourited'])
                <span class="favourited">
                    <a href="/status/{{ $status['id'] }}?action=unfavourite">&#9733;</a>
                </span>
            @else
                <a href="/status/{{ $status['id'] }}?action=favourite">&#9734;</a>
            @endif
            {{ $status['favourites_count'] }}
        </span>
    </div>
</article>
