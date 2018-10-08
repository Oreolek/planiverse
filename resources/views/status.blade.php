<li><article>
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
            <a href="{{ route('thread', ['id' => $status['id']]) }}">&#10568;</a>
        </span>

        <!-- Reply -->
        <span title="Reply">
            <a href="{{ route('status', ['id' => $status['id']]) }}">&#8629;</a>
        </span>

        <!-- Reblog -->
        <span title="Reblog">
            @if ($status['reblogged'])
                <span class="reblogged">
                    <a href="{{ route('unreblog', ['id' => $status['id']]) }}">&#8634;</a>
                </span>
            @else
                <a href="{{ route('reblog', ['id' => $status['id']]) }}">&#8634;</a>
            @endif
            {{ $status['reblogs_count'] }}
        </span>

        <!-- Favourite -->
        <span title="Favourite">
            @if ($status['favourited'])
                <span class="favourited">
                    <a href="{{ route('unfavourite', ['id' => $status['id']]) }}">&#9733;</a>
                </span>
            @else
                <a href="{{ route('favourite', ['id' => $status['id']]) }}">&#9734;</a>
            @endif
            {{ $status['favourites_count'] }}
        </span>
    </div>
</article></li>
