<li><article>
    @component('event_info', [
        'account' => $status['account'],
        'created_at' => $status['created_at'],
        'type' => $status['in_reply_to_id'] === null ? null : 'reply',
        'visibility' => $status['visibility']
    ])
    @endcomponent

    <div>
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
    </div>

    <div class="actions">
        <!-- Context -->
        <span title="Expand thread">
            <a href="{{ route('thread', ['status_id' => $status['id']]) }}">&#10568;</a>
        </span>

        <!-- Reply -->
        <span title="Reply">
            <a href="{{ route('status', ['status_id' => $status['id']]) }}">&#8629;</a>
        </span>

        <!-- Reblog -->
        <span title="Reblog">
            @if (isset($status['reblogged']) && $status['reblogged'])
                <span class="reblogged">
                    <a href="{{ route('unreblog', ['status_id' => $status['id']]) }}">&#8634;</a>
                </span>
            @else
                <a href="{{ route('reblog', ['status_id' => $status['id']]) }}">&#8634;</a>
            @endif
            {{ $status['reblogs_count'] }}
        </span>

        <!-- Favourite -->
        <span title="Favourite">
            @if (isset($status['favourited']) && $status['favourited'])
                <span class="favourited">
                    <a href="{{ route('unfavourite', ['status_id' => $status['id']]) }}">&#9733;</a>
                </span>
            @else
                <a href="{{ route('favourite', ['status_id' => $status['id']]) }}">&#9734;</a>
            @endif
            {{ $status['favourites_count'] }}
        </span>
    </div>
</article></li>
