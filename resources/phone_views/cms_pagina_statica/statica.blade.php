@extends('templates.cms_pagina_statica')
@section('seo_title') {{$cms_pagina->seo_title}} @endsection
@section('seo_description') {{$cms_pagina->seo_description}} @endsection
@include('flash')

@section('css')

	.titolo-localita { padding:15px 25px;  background: rgba(0,0,0,0.7); color:#fff; margin:60px 10px 10px 10px  !important;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
	}
	
	.titolo-localita h1 { margin: 0 0 5px; }
	
	#page-statica { padding:0 15px 15px; line-height:1.5em;   }
	#page-statica h1 { line-height: 30px; font-size:26px; }
	#page-statica article:first-child h2 { margin-top:0; }
	#page-statica a { color:#38A6E9 ;}
	#page-statica img { width:100%; height:auto;}
	#page-statica hr { border-top: 1px solid #ddd; }
	
	.sfondo-pagina { 
		
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
		background-color: transparent;
		background-position: center center;
		background-image: url('{{Utility::asset("images/pagine/300x200/".$cms_pagina->immagine)}}');
		
	}

	.titolo-localita { margin-top: 12px !important}
	
@endsection

@section('content')

	<div class="clearfix"></div>
	
	<div class="container sfondo-pagina"  >
		<div class="row">
			<div class="col-xs-12">
				@include('phone_views/covid-banner')
				<div class="titolo-localita" >
					<header role="banner">
						<h1>{!! $cms_pagina->h1 !!}</h1>
						{!! $cms_pagina->descrizione_1 !!}
						@include("share")
						@include("chiuso")
					</header>
				</div>
				
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div id="page-statica">
					@include("widget.content")
				</div>
			</div>
		</div>
	</div>	

@endsection