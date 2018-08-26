<article>
	@component('account_time_info', [
		'account' => $notification['account'],
		'created_at' => $notification['created_at']
	])
    @endcomponent

    <span class="action-notification">
    	@if ($notification['type'] === 'mention')
    		mentioned
    	@elseif ($notification['type'] === 'reblog')
    		reblogged
    	@elseif ($notification['type'] === 'favourite')
    		favourited
    	@elseif ($notification['type'] === 'follow')
    		followed you.
    	@endif
    </span>

    @if ($notification['status'] ?? null !== null)
    	@component('status', ['status' => $notification['status']])
    	@endcomponent
    @endif
</article>
