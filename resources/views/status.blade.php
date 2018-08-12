<div class="status">
    <div class="tooltip">
        <a href="{{ $status['account']['url'] }}">
            <img
                src="{{ $status['account']['avatar'] }}"
                alt="{{ $status['account']['acct'] }}"
                class="avatar"
            />
            {{ $status['account']['display_name'] }}
        </a>
        <span class="tooltiptext">{{ $status['account']['acct'] }}</span>
    </div>
    @if ($status['reblog'] === null)
        <p>{!! $status['content'] !!}</p>
        @foreach ($status['media_attachments'] as $attachment)
            @if ($attachment['type'] === 'image')
                <p>
                    <img
                        src="{{
                            $attachment['remote_url'] === null
                                ? $attachment['url']
                                : $attachment['remote_url']
                            }}"
                        alt="{{ $attachment['description'] }}"
                    />
                </p>
            @endif
        @endforeach
    @else
        @component('status', ['status' => $status['reblog']])
        @endcomponent
    @endif

    <div class="actions">
        <!-- Reply -->
        <span>
            &#8629;
        </span>

        <!-- Reblog -->
        <span>
            &#8644; {{ $status['reblogs_count'] }}
        </span>

        <!-- Favourite -->
        <span>
            @if ($status['favourited'] === true)
                <span class="favourited">&#9733;</span>
            @else
                &#9734;
            @endif
            {{ $status['favourites_count'] }}
        </span>
    </div>
</div>
