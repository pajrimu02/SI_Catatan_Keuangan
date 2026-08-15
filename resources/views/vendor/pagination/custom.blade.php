@if ($paginator->hasPages())
    <nav aria-label="Pagination">
        <ul class="pagination-modern">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item-modern disabled">
                    <span><i class="fa-solid fa-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item-modern">
                    <a href="{{ $paginator->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                </li>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item-modern disabled"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item-modern active"><span>{{ $page }}</span></li>
                        @else
                            <li class="page-item-modern"><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item-modern">
                    <a href="{{ $paginator->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                </li>
            @else
                <li class="page-item-modern disabled">
                    <span><i class="fa-solid fa-chevron-right"></i></span>
                </li>
            @endif

        </ul>
    </nav>

    <style>
        .pagination-modern {
            display: flex;
            list-style: none;
            gap: 6px;
            padding: 0;
            margin: 0;
            justify-content: center;
            flex-wrap: wrap;
        }

        .page-item-modern a,
        .page-item-modern span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 6px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            text-decoration: none;
            background: #fff;
            border: 1px solid #e5e7eb;
            transition: all 0.18s ease;
        }

        .page-item-modern a:hover {
            background: #111827;
            color: #fff;
            border-color: #111827;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .page-item-modern.active span {
            background: #111827;
            color: #fff;
            border-color: #111827;
            box-shadow: 0 4px 10px rgba(17,24,39,0.25);
        }

        .page-item-modern.disabled span {
            background: transparent;
            border-color: transparent;
            color: #9ca3af;
            cursor: default;
        }
    </style>
@endif