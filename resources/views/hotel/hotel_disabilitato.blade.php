@extends(

	'templates.cms_pagina_statica', 
	[
		'for_canonical' => "",
		'previous_page_url' => "",
		'next_page_url' => "",
		'cms_pagina' => ""
	]
)

@section('seo_title')
	{{ $title }} - {!! trans('hotel.disattivato') !!}
@endsection

@section('seo_description')
	{{ $description }}
@endsection


@section('robots')
	@if (isset($cliente) && !$cliente->attivo)
		<meta name="robots" content="noindex, follow" />
    @elseif (isset($cliente) && $cliente->attivo == -1)
		<meta name="robots" content="noindex, nofollow" />
    @endif
@endsection

@section("css")
	
	@include("desktop.css.hotel-disabilitato")
		
@endsection


@section('content')
	
	<div class="warningHD margin-top ">
	    <header>
	        <img src="{{Utility::asset('/images/sad.png')}}" style="display: inline-block; " />
	        <div class="h1">{!! trans('hotel.disattivato') !!}</div>
	    </header>
		<div class="content padding-top-4">
			<h1>{{{$cliente->nome}}}</h1><span class="rating" style="display:inline-block;">{{{is_null($cliente->stelle) ? '' : $cliente->stelle->nome}}}</span><br />
			<span itemprop="streetAddress">{{{ $cliente->indirizzo}}}</span> - <span itemprop="postalCode">{{{ $cliente->cap }}}</span> - <span itemprop="addressLocality">{{{ $cliente->localita->nome.' ('. $cliente->localita->prov . ')' }}}</span><br/><br />
		</div>
	</div>
	<div class="clearfix"></div>
	
	<div class="margin-bottom">
	@include('hotel.simili')
	</div>

@endsection





