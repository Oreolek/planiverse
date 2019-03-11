<li><article class="
    @is_reblogged($status) reblogged @endis_reblogged
    @is_reblog($status) reblog @endis_reblog
    @is_favourited($status) favourited @endis_favourited
    @is_muted($status) muted @endis_muted
    @has_spoiler($status) has_spoiler @endhas_spoiler
">
    @component('event_info', [
        'account' => $status['account'],
        'created_at' => $status['created_at'],
        'type' => $status['in_reply_to_id'] === null ? null : 'reply',
        'visibility' => $status['visibility']
    ])
    @endcomponent

    <div>
        @has_spoiler($status)
            <details>
                <summary>{{ $status['spoiler_text'] }}</summary>

                @component('status_content', ['status' => $status])
                @endcomponent
            </details>
        @else
            @component('status_content', ['status' => $status])
            @endcomponent
        @endhas_spoiler
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
            @is_reblogged($status)
                <span class="reblogged">
                    <a href="{{ route('unreblog', ['status_id' => $status['id']]) }}">&#8634;</a>
                </span>
            @else
                <a href="{{ route('reblog', ['status_id' => $status['id']]) }}">&#8634;</a>
            @endis_reblogged
            {{ $status['reblogs_count'] }}
        </span>

        <!-- Favourite -->
        <span title="Favourite">
            @is_favourited($status)
                <span class="favourited">
                    <a href="{{ route('unfavourite', ['status_id' => $status['id']]) }}">&#9733;</a>
                </span>
            @else
                <a href="{{ route('favourite', ['status_id' => $status['id']]) }}">&#9734;</a>
            @endis_favourited
            {{ $status['favourites_count'] }}
        </span>
    </div>
</article></li>
