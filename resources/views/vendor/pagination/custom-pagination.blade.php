<div class="section padding-top-0">
    <div class="container text-center">
        <nav>
            @if ($paginator->hasPages())
                <ul class="pagination justify-content-center pagination-lg margin-top-20">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="disabled page-item"><span class="page-link">&laquo;</span></li>
                    @else
                        <li class="page-item"><a href="{{ $paginator->previousPageUrl() }}" class="page-link"
                                rel="prev">&laquo;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="disabled page-item"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="active page-item"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a href="{{ $url }}"
                                            class="page-link">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" class="page-link"
                                rel="next">&raquo;</a>
                        </li>
                    @else
                        <li class="disabled page-item"><span class="page-link">&raquo;</span></li>
                    @endif
                </ul>
            @endif
        </nav>
        <!-- end Snippets -->

    </div><!-- end container -->
</div>
