<nav>
    <ul>
        @if ($links['prev'] !== null)
            <li><a href="{{ $links['prev'] }}">Previous</a></li>
        @endif

        @if ($links['next'] !== null)
            <li><a href="{{ $links['next'] }}">Next</a></li>
        @endif
    </ul>
</nav>
