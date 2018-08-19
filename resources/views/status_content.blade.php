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
