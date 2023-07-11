@php

    $totalPage = floor($clienti->total() / $clienti->perPage()) + 1;
    $link = "";
    $order = Request::get("order");
    $filter = Request::get("filter");
    if(isset($order) && $order != "") $link .= "&order=" . $order;
    if(isset($filter) && $filter != "") $link .= "&filter=" . $filter;
    $first = 1; $last = $totalPage;
    
@endphp

@if ($totalPage > 1)

    <nav aria-label="Page navigation example">
        <ul class="pagination">

            @if ($clienti->currentPage() > 1 && $totalPage > 12)
                <li class="page-item-first"><a class="page-link" href="{{$clienti->previousPageUrl() . $link}}">&larr; Precedente</a></li>
            @endif

            @if ($totalPage > 12)
                @php $first = 1; $last = 12; @endphp
            @endif

            @if ($clienti->currentPage() > 6 && $totalPage > 12)
                @php $first = $clienti->currentPage() - 6; $last = $clienti->currentPage() + 6; @endphp
            @endif
            
            @if ($clienti->currentPage() > 6 && ($totalPage - $clienti->currentPage()) < 7)
                @php $first = $totalPage - 12 ; $last = $totalPage; @endphp
            @endif

            @for($t = $first; $t <= $last; $t++ )
                <li class="page-item @if ($clienti->currentPage() == $t) active @endif"><a class="page-link" href="{{$clienti->url($t) . $link}}">{{$t}}</a></li>
            @endfor

            @if ($clienti->currentPage() < $totalPage && $totalPage > 12)
                <li class="page-item-last"><a class="page-link" href="{{$clienti->nextPageUrl() . $link}}">Successivo &rarr;</a></li>
            @endif

        </ul>
    </nav>

@endif