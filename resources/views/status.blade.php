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
    <time datetime="{{ $status['created_at'] }}">
        @php
            $created_at = new Carbon\Carbon($status['created_at']);
        @endphp
        @if (env('SHOW_BEATS') === true)
            {{ '@' . $created_at->format('B') }} |
        @endif
        {{ $created_at->diffForHumans(null, false, true) }}
    </time>
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
            <a href="/status/{{ $status['id'] }}">&#8629;</a>
        </span>

        <!-- Reblog -->
        <span>
            &#8644; {{ $status['reblogs_count'] }}
        </span>

        <!-- Favourite -->
        <span>
            @if ($status['favourited'])
                <span class="favourited"><a href="/status/{{ $status['id'] }}/unfavourite">&#9733;</a></span>
            @else
                <a href="/status/{{ $status['id'] }}/favourite">&#9734;</a>
            @endif
            {{ $status['favourites_count'] }}
        </span>
    </div>
</div>
