<li><article>
    @component('event_info', [
        'account' => $notification['account'],
        'created_at' => $notification['created_at'],
        'type' => $notification['type'],
        'visibility' => null
    ])
    @endcomponent

    @if ($notification['status'] ?? null !== null)
        <ul>
            @component('status', ['status' => $notification['status']])
            @endcomponent
        </ul>
    @endif
</article></li>
