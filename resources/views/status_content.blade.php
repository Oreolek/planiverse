@if ($status['reblog'] === null)
    <p>{!! $status['content'] !!}</p>
    @foreach ($status['media_attachments'] as $attachment)
        @if ($attachment['type'] === 'image')
            <figure>
                <a href="{{
                    $attachment['remote_url'] === null
                        ? $attachment['url']
                        : $attachment['remote_url']
                    }}"
                    target="_blank"
                >
                    <img
                        src="{{ $attachment['preview_url'] }}"
                        alt="{{ 
                            $attachment['description'] === null
                                ? $attachment['url']
                                : $attachment['description'] 
                        }}"
                    />
                </a>
            </figure>
        @elseif ($attachment['type'] === 'video' || $attachment['type'] === 'gifv')
            <video controls>
                <source src="{{
                    $attachment['remote_url'] === null
                        ? $attachment['url']
                        : $attachment['remote_url']
                    }}"
                />
            </video>
        @endif
    @endforeach
@else
    <ul>
        @component('status', ['status' => $status['reblog']])
        @endcomponent
    </ul>
@endif
