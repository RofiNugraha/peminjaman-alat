@if ($paginator->hasPages())
<nav>
    <ul class="pagination justify-content-center">

        @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link">&laquo;</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}">&laquo;</a>
        </li>
        @endif

        @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        $start = max(1, $current);
        $end = min($start + 2, $last);

        if ($end - $start < 2) { $start=max(1, $end - 2); } @endphp @for ($page=$start; $page <=$end; $page++) <li
            class="page-item {{ $page == $current ? 'active' : '' }}">
            <a class="page-link" href="{{ $paginator->url($page) }}">
                {{ $page }}
            </a>
            </li>
            @endfor

            @if ($end < $last - 1) <li class="page-item disabled">
                <span class="page-link">...</span>
                </li>
                @endif

                @if ($end < $last) <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($last) }}">
                        {{ $last }}
                    </a>
                    </li>
                    @endif

                    @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}">&raquo;</a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link">&raquo;</span>
                    </li>
                    @endif

    </ul>
</nav>
@endif