@extends('templates.cms_filter_listing')
@section('seo_title'){{ $seo_title }}@endsection
@section('seo_description'){{ $seo_description }}@endsection
@include('flash')
@section('content')

    @include('cms_filter_listing.container_centrale')
    <div class="wrapper_risultati_ricerca container">

        @if (isset($pagination) && $pagination ) 
            @include("widget.pagination", ["clienti" => $clienti, "position" => 0])
		@endif

        <div class="risultati_ricerca row">

            @if ($clienti->count())
                @include('cms_filter_listing.clienti', array('clienti' => $clienti))  
            @else
                @include('cms_filter_listing.nessun_hotel')
            @endif

        </div>

        @if (isset($pagination) && $pagination ) 
            @include("widget.pagination", ["clienti" => $clienti, "position" => 1])
		@endif
    </div>

@endsection