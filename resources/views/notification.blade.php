<li><article>
    @component('event_info', [
        'account' => $notification['account'],
        'created_at' => $notification['created_at'],
        'type' => $notification['type']
    ])
    @endcomponent

    @if ($notification['status'] ?? null !== null)
        @component('status', ['status' => $notification['status']])
        @endcomponent
    @endif
</article></li>
