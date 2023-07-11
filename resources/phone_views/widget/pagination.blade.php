@php

    $totalPage = floor($clienti->total() / $clienti->perPage()) + 1;
    $first = 1; $last = $totalPage;

    $purl = $clienti->previousPageUrl();
    $nurl = $clienti->nextPageUrl();

    if (isset($order_by)):
        $purl .= "&order=" . $order_by;
        $nurl .= "&order=" . $order_by;
    endif;

@endphp

@if ($totalPage > 1)

    <nav aria-label="Page navigation example">
        <ul class="pagination" style="padding:10px">

            @if ($clienti->currentPage() > 1 && $totalPage > 5)
                <li class="page-item-first"><a class="page-link" href="{{$purl}}">&larr;</a></li>
            @endif

            @if ($totalPage > 5)
                @php $first = 1; $last = 5; @endphp
            @endif

            @if ($clienti->currentPage() > 2 && $totalPage > 5)
                @php $first = $clienti->currentPage() - 2; $last = $clienti->currentPage() + 2; @endphp
            @endif
            
            @if ($clienti->currentPage() > 2 && ($totalPage - $clienti->currentPage()) < 5)
                @php $first = $totalPage - 4 ; $last = $totalPage; @endphp
            @endif

            @for($t = $first; $t <= $last; $t++ )
                
                @php $url = $clienti->url($t); @endphp
                @if(isset($order_by))
                    @php $url .= "&order=" . $order_by; @endphp
                @endif
                
                <li class="page-item @if ($clienti->currentPage() == $t) active @endif"><a class="page-link" href="{{$url}}">{{$t}}</a></li>

            @endfor

            @if ($clienti->currentPage() < $totalPage && $totalPage > 5)
                <li class="page-item-last"><a class="page-link" href="{{$nurl}}">&rarr;</a></li>
            @endif

        </ul>
    </nav>

@endif