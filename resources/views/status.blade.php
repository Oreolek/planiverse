<hr />
<article>
    @component('account_time_info', [
        'account' => $status['account'],
        'created_at' => $status['created_at']
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
<hr />
