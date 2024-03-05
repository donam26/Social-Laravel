 <!-- pagination  -->
 <nav aria-label="...">
    <ul class="pagination pagination-sm">
        @if ($pagination['curr_page'] <= 1)
            <li class="page-item disabled"><a class="page-link" href="#" aria-disabled="true">Previous</a></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $pagination['base_url'] . '/' . intval($pagination['curr_page'] - 1) . '?' . $pagination['params'] }}">Previous</a></li>
        @endif

        @for ($page = 1; $page <= $pagination['total_page']; $page++)
            @if ($page == $pagination['curr_page'])
                <li class="page-item active" aria-current="page"><a class="page-link" href="{{ $pagination['base_url'] . '?' . $pagination['params'] }}">{{ $page }}</a></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $pagination['base_url'] . '/' . $page . '?' . $pagination['params'] }}">{{ $page }}</a></li>
            @endif
        @endfor

        @if ($pagination['curr_page'] >= $pagination['total_page'])
            <li class="page-item disabled"><a class="page-link" href="#" aria-disabled="true">Next</a></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $pagination['base_url'] . '/' . intval($pagination['curr_page'] + 1) . '?' . $pagination['params'] }}">Next</a></li>
        @endif
    </ul>
</nav>