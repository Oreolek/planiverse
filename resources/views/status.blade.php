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
</div>
