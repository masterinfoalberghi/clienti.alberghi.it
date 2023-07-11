@extends('templates.cms_filter_listing')
@section('seo_title'){{ $seo_title }}@endsection
@section('seo_description'){{ $seo_description }}@endsection
@include('flash')

@section('content')  

<div class="listing-ajax">

    @if (isset($pagination) && $pagination ) 
         @include("widget.pagination", ["clienti" => $clienti])
    @endif

    @include("widget.filtri_label")
    
    @if (count($clienti)>0)
        
        <section id="listing">
        
            <header class="hidden">
                <h2>{{trans("title.lista")}}</h2>
            </header>
            
            @include('cms_filter_listing.clienti', array('clienti' => $clienti))
        
        </section>
        
    @endif
    
    <div class="clearfix"></div>
    
    @if (isset($pagination) && $pagination ) 
         @include("widget.pagination", ["clienti" => $clienti])
    @endif
    
    @include("widget.loading")
    
</div>

<div class="clearfix"></div>

@endsection