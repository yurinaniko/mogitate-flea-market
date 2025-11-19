<nav class="pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="disabled">
            <svg viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}">
            <svg viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
        </a>
    @endif
    {{-- Numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span>{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach
    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">
            <svg viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
        </a>
    @else
        <span class="disabled">
            <svg viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
        </span>
    @endif
</nav>