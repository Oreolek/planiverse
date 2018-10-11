<nav>
    <ul>
        @if (isset($links->prev))
            <li><a href="{{ $links->prev }}">Previous</a></li>
        @endif

        @if (isset($links->next))
            <li><a href="{{ $links->next }}">Next</a></li>
        @endif
    </ul>
</nav>
