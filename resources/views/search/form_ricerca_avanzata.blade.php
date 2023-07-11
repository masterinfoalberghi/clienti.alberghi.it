@extends('templates.page')

@section('seo_title')
	
	{{ trans('labels.menu_ric') }}

@endsection

@section('seo_description')
	
	{{ trans('labels.menu_ric') }}

@endsection

@section('css')

	a.link { color:#222 !important; font-weight:normal !important; text-decoration:none !important; font-size:13px !important }
	a.link:hover { text-decoration: underline !important}
	.button_select { position:absolute; top:5px; right:10px; }
	.col-form  a.button_select_link {  display:inline-block !important; padding:0 !important; margin:0 0 0 10px !important; color:#666 !important; font-size:12px;  }
	.col-form  a.button_select_link.selectall { color:#222 !important; }
	.col-form  a.button_select_link:hover { text-decoration:underline !important; }
	
@endsection

@section('css-files')

	<link type="text/css" rel="stylesheet" href="{{ Utility::asset('/css/style-ricerca-avanzata.min.css') }}"  />
	<link type="text/css" rel="stylesheet" href="{{ Utility::asset('/css/multiselect.min.css') }}"  />
	
@endsection

@section('js-files')

	<script type="text/javascript" src="{{Utility::asset('/js/jquery.multiselect.min.js')}}"></script>
	
@endsection

@section('js')

	var $qualsiasi_prezzo 			= '{{ trans("labels.qualsiasi_prezzo")  }}';
    var $fino 						= '{{ trans("labels.fino")  }}';
    var $qualsiasi_dist 			= '{{ trans("labels.qualsiasi_dist")  }}';
    var $entro 						= '{{ trans("labels.entro")  }}';
    var $piu_opzioni 				= '{!! trans("labels.piu_opzioni")  !!}';
    var $meno_opzioni 				= '{{ trans("labels.meno_opzioni")  }}';
	var $f_prezzo_real 				= '<?php echo (array_key_exists('f_prezzo_real', $prefill)) ? $prefill["f_prezzo_real"] : 0 ?>';
	var $distanza_centro_real 		= '<?php echo (array_key_exists('distanza_centro_real', $prefill)) ? $prefill["distanza_centro_real"] : 0 ?>';
	var $distanza_stazione_real 	= '<?php echo (array_key_exists('distanza_stazione_real', $prefill)) ? $prefill["distanza_stazione_real"] : 0 ?>';
	var $distanza_spiaggia_real 	= '<?php echo (array_key_exists('distanza_spiaggia_real', $prefill)) ? $prefill["distanza_spiaggia_real"] : 0 ?>';
	
	$('#multiple_loc').multiSelect({ selectableOptgroup: true });
	
	$('#select-all').click(function(e){
		e.preventDefault();
		$('#multiple_loc').multiSelect('select_all');
	});
	
	$('#deselect-all').click(function(e){
		e.preventDefault();
		$('#multiple_loc').multiSelect('deselect_all');
	});
	
	@include("js.js-above.ricerca")
	
	
@endsection

<?php $today = date("d/m/Y", mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?>

@section('content')
	
	<div class="form_multi_email">
	
	<h1>{{ trans('labels.menu_ric') }}</h1>
	
	<div class="clearfix"></div>
	
	<div class="panel-body grey ricerca_avanzata" id="body-hotel">

		@include('errors')
		
			{{-- Form::open(['url' => Utility::getLocaleUrl($locale).'richiesta_ricerca_avanzata.php', 'method' => "post"]) --}}
			{!! Form::open(['url' => Utility::getUrlWithLang($locale,'/richiesta_ricerca_avanzata.php'), 'method' => "post"]) !!}

			<div class="form-group">
				<div class="col-form force">
					<label>{{ trans('listing.localita') }}</label>
					@include('composer.searchMultiplaSelectLocalita')
					<div class="button_select">
						<a class="button_select_link selectall " href='#' id='select-all'><b>{!! trans("labels.seleziona_tutto") !!}</b></a>
						<a class="button_select_link" href='#' id='deselect-all'>{!! trans("labels.deseleziona_tutto") !!}</a>
					</div>
					
					<div class="clear"></div>
				</div>
			</div>
   	
		    <div class="form-group stelle">
		      
		      @foreach ($stelle as $id => $nome)
		        {!! Form::checkbox("categorie[]",$id,(array_key_exists('cat_' . $id, $prefill) && $prefill['cat_' . $id] == 1) ? true : false,["id"=>"cat_".$id,  "class"=>"beautiful_checkbox"]) !!} {!! $nome !!} &nbsp;&nbsp;
		      @endforeach
		      
		      {!! Form::checkbox("annuale",1,(array_key_exists('annuale', $prefill) && $prefill['annuale'] == 1 ) ? true : false,["id"=>"annuale", "class"=>"beautiful_checkbox"]) !!} <strong>{{ trans('listing.annuale') }}?</strong>
		      
		    </div>
	
			<div class="form-group row">
				
				<div class="col-form force small-3" style="margin-right: 0.5%;">
					{{-- SLIDER fascia di prezzo --}}
					{!! Form::label('f_prezzo',trans('labels.fasce_prezzo').': ') !!} 
					<input type="text" name="f_prezzo" id="f_prezzo" readonly  >
					<div id="slider-range-min"></div>
				</div>
				
				<div class="col-form force small-3" style="margin-right: 0.5%;">
					{{-- SLIDER distanza dal centro --}}
					{!! Form::label('distanza_centro',trans('labels.distanza_centro').': ') !!}
					<input type="text" name="distanza_centro" id="distanza_centro" readonly >
					<div id="slider-distanza-centro"></div>
				</div>
				
				<div class="col-form force small-3" style="margin-right: 0.5%;">
					{{-- SLIDER distanza dalla stazione --}}
					{!! Form::label('distanza_stazione',trans('labels.distanza_stazione').': ') !!}
					<input type="text" name="distanza_stazione" id="distanza_stazione" readonly >
					<div id="slider-distanza-stazione"></div>
				</div>
				
				<div class="col-form force small-3">
					{{-- SLIDER distanza dalla spiaggia --}}
					{!! Form::label('distanza_spiaggia',trans('labels.distanza_spiaggia').': ') !!}
					<input type="text" name="distanza_spiaggia" id="distanza_spiaggia" readonly >
					<div id="slider-distanza-spiaggia"></div>
				</div>
			
			</div>
  
  
			<div class="form-group row">
				<div class="col-form small-6 force"  style="margin-right: 0.5%;">
					
					<label>{{ trans('listing.trattamento') }}</label>
					{!! Form::select('trattamento',Utility::Trattamenti(trans('listing.seleziona').'....'),(array_key_exists('trattamento', $prefill)) ? $prefill['trattamento'] : null,["class"=>"form-control","id"=>"trattamento"]) !!}
				</div>
				
				<div class="col-form small-3 force arrow_tooltip datapicker">
					<label>{{ trans('labels.data') }}</label>
					
					<?php 
						
						$date = array_key_exists('a_partire_da', $prefill) ? $prefill['a_partire_da'] : date("d/m/Y", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
							
					?>
					
					<a href="#" id="a_partire_da_button">{{$date}}</a>
					{!! Form::input('hidden','a_partire_da', $date ,["class"=>"data datepicker arrivo", "id" => "a_partire_da"]) !!}
				</div>
				<div class="clear"></div>
				<div  class="daterange" style="width:50.75%; float:right;"></div>
				<div class="clear"></div>
			
			</div>
   	
			{{-- Servizi --}}
			<?php $count = 1; $count_servizi = count($prefill['servizi']); ?>
						
			<?php if ($count_servizi >0): ?>
				<div class="servizio_ricerca_avanzata_container open" style="display:block">
			<?php else: ?>
				<div class="servizio_ricerca_avanzata_container">
			<?php endif; ?>
				
				<div class="servizio_ricerca_avanzata">
				@foreach ($categorie as $categoria)
				
					@if ($count == 3 || $count == 6)
						</div>
						<div class="servizio_ricerca_avanzata">   
					@endif
					
					<div class="categoria">{!!$categoria->nome!!}</div class="categoria">
					
					<div>
						@foreach ($categoria->servizi as $servizio)
							<div class="checkbox">
								<?php $is_checked = in_array( $servizio->id, $prefill['servizi']); ?>
								{!! Form::checkbox('servizi[]', $servizio->id, $is_checked ,["id"=>"servizio_".$servizio->id, "class"=>"beautiful_checkbox"]) !!}&nbsp;{{ $servizio->servizi_lingua->first()->nome}}<br />
							</div>
						@endforeach
					</div>
					<?php $count ++; ?>
				
				@endforeach
				
			</div>
			</div>
			<div class="clear"></div>

  
			@include('esca_snippet')
  
			{!! Form::hidden('locale',$locale) !!}
			{!! Form::hidden('IP',isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')  !!}
			{!! Form::hidden('referer',isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '' )!!}
			{!! Form::hidden('codice_cookie',(array_key_exists('codice_cookie', $prefill)) ? $prefill['codice_cookie'] : "") !!}
			
			{{-- Questo campo serve alla validazione del campo trattamento che deve essere different da il valore di un altro campo --}}
			{!! Form::hidden('seleziona',0) !!}
			
			{{-- ATTENZIONE gli hidden devon essere valorizzati al default delle slider (campo 'value') --}}
			{!! Form::hidden('f_prezzo_real', (array_key_exists('f_prezzo_real', $prefill)) ? $prefill["f_prezzo_real"] : 0 ,["id" => "f_prezzo_real"]) !!}
			{!! Form::hidden('distanza_centro_real',(array_key_exists('distanza_centro_real', $prefill)) ? $prefill["distanza_centro_real"] : 0,["id" => "distanza_centro_real"]) !!}
			{!! Form::hidden('distanza_stazione_real',(array_key_exists('distanza_stazione_real', $prefill)) ? $prefill["distanza_stazione_real"] : 0,["id" => "distanza_stazione_real"]) !!}
			{!! Form::hidden('distanza_spiaggia_real',(array_key_exists('distanza_spiaggia_real', $prefill)) ? $prefill["distanza_spiaggia_real"] : 0,["id" => "distanza_spiaggia_real"]) !!}
  
			
			<div class="cerca_button">
				
				<div class="left">
					<input class="button green mailmultipla" type="submit" value="{{strtoupper(trans('labels.cerca'))}}">
					
					@if($count_servizi == 0)
					<a href="#" style="margin-left:15px;" class="button link opzioni">{{ trans("labels.piu_opzioni") }}</a>
					@else
					<a href="#" style="margin-left:15px;" class="button link opzioni open">{{ trans("labels.meno_opzioni") }}</a>
					@endif
					
				</div>
				
				<div class="right">
					<a href="{{url("/trova_hotel.php")}}" class="link">{{ trans("labels.ric_nome") }} &#8594;</a>
				</div>
				
				<div class="clear"></div>
			</div>

		{!! Form::close() !!}

		</div>

@endsection