<hr />
<article>
    @component('event_info', [
        'account' => $status['account'],
        'created_at' => $status['created_at'],
        'type' => $status['in_reply_to_id'] === null ? null : 'reply'
    ])
    @endcomponent

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
        <!-- Context -->
        <span title="Expand thread">
            <a href="/status/{{ $status['id'] }}/thread">&#10568;</a>
        </span>

        <!-- Reply -->
        <span title="Reply">
            <a href="/status/{{ $status['id'] }}">&#8629;</a>
        </span>

        <!-- Reblog -->
        <span title="Reblog">
            @if ($status['reblogged'])
                <span class="reblogged">
                    <a href="/status/{{ $status['id'] }}/unreblog">&#8634;</a>
                </span>
            @else
                <a href="/status/{{ $status['id'] }}/reblog">&#8634;</a>
            @endif
            {{ $status['reblogs_count'] }}
        </span>

        <!-- Favourite -->
        <span title="Favourite">
            @if ($status['favourited'])
                <span class="favourited">
                    <a href="/status/{{ $status['id'] }}/unfavourite">&#9733;</a>
                </span>
            @else
                <a href="/status/{{ $status['id'] }}/favourite">&#9734;</a>
            @endif
            {{ $status['favourites_count'] }}
        </span>
    </div>
</article>
<hr />
