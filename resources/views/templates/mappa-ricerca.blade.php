
@if (!isset($canonical_uri))
@php
	$canonical_uri = '/';
@endphp
@endif

<!DOCTYPE html>
<html>
	<head>

		<script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
		<script  src="{{Utility::asset('/desktop/js/mappa.min.js')}}"></script>
		 <title>{{$title}}</title>
		<link rel="canonical" href="{{ url($canonical_uri) }}">
		<style>
		
			@include('desktop.css.above')
        	@include('desktop.css.mappa')
    		@include('vendor.fontello.css.animation')
    		@include('vendor.fontello.css.fontello')

			html, body { width: 100%; height: 100%; display: block; margin:0; padding: 0; }
			#map-container { width: 100%; height: 100%; display: block; background: #f5f5f5; }
			#map { width: 100%; height:100%; display: block; }

		</style>
		
		@include("gtm")

	</head>

	<body>
		
		@include("gtm-noscript")
		<div id="map-container"  class="ricerca_container">
			<div id="logo-mappa">
				<button  id="closeinfowindow_container_ricerca"  class="btn btn-danger"><i class="icon-left"></i> Chiudi mappa</button>
				<a href="{{Utility::getUrlWithLang($locale, '/')}}">
					<img src="{{ Utility::asset('images/logo.png') }}" alt="Info Alberghi srl" width="160"/>
				</a>
			</div>
			{{-- <div id="filtri-mappa"></div> --}}
			<div id="map" @if ($label != '') class="con_filtro"@endif></div>
			@if ($label != '')
			<div id="filtri_map">
				{!!$label!!}
			</div>
			@endif
			<div id="infowindow_container" class="ricerca_container"></div>
			<a id="closeinfowindow_container" href="#"><i class="icon-cancel"></i></a>
			<div id="filter-inner">
				{!! Form::open(['url' => url(Utility::getUrlLocaleFromAppLocale($locale).'/mappa-ricerca'), 'method' => "get", 'id' => 'filter-map']) !!}
					<div id="header"  class="ricerca_container">
						<span>{!!$start_label!!}</span>
						{{ trans('listing.trovati') }} {{$clienti_count}} {{ strtolower(trans('labels.hp_hotel')) }}
					</div>
					<div>
						<a title="Categorie" class="placeholder first">{{ ucfirst(trans('labels.menu_cat')) }}</a>
						@foreach ($stelle as $id => $nome)
						  	<div class="sep_categoria">
						  		<input type="checkbox" name="categorie[]" value="{{$id}}" @if (is_array(old('categorie')) && in_array($id, old('categorie'))) checked @endif id="cat_{{$id}}" class="default_checkbox" /> <label class="stelle" for="cat_{{$id}}">{!! $nome !!} </label>&nbsp;&nbsp;
						  	</div>
						@endforeach
						
				    	<div class="sep_gruppi">
					    	<input type="checkbox" name="listing_tipologie" value="2" @if (old('listing_tipologie') ) checked @endif  id="listing_tipologie" class="default_checkbox" /><label for="listing_tipologie">{{ ucfirst(trans('labels.rr_cat_res')) }}</label>
				    	</div>
				    	
					</div>
					{{-- <hr> --}}
					<div class="sep_annuale">
						<a title="Aperture" class="placeholder">{{ ucfirst(trans('listing.filtri_apertura')) }}</a>
						<input type="checkbox" name="annuale" value="1" @if (old('annuale')) checked @endif  id="annuale" class="default_checkbox" /> <label for="annuale">{{ trans('listing.annuale') }}</label>
					</div>
					{{-- <hr> --}}
					
					<div>
						<a title="Servizi" class="placeholder">{{ ucfirst(trans('labels.menu_serv')) }}</a>
						@foreach ($gruppi as $gruppo)
						<div class="sep_gruppi">
							<input type="checkbox" name="gruppi_servizi[]" value="{{$gruppo->id}}" @if (is_array(old('gruppi_servizi')) && in_array($gruppo->id, old('gruppi_servizi'))) checked @endif  id="gruppo_serv_{{$gruppo->id}}" class="default_checkbox" /><label for="gruppo_serv_{{$gruppo->id}}">{!! $gruppo->getNomeLocale($locale) !!}</label>
						</div>
						@endforeach
						<input type="checkbox" name="reception_24h" value="1" @if (old('reception_24h') || $reception_24h) checked @endif  id="reception_24h" class="default_checkbox" /><label for="reception_24h">Reception H24</label>
						@foreach ($servizi_singoli as $id => $servizio)
							<div class="sep_gruppi">
								<input type="checkbox" name="servizi_non_gruppo[]" value="{{$id}}" @if (is_array(old('servizi_non_gruppo')) && in_array($id, old('servizi_non_gruppo'))) checked @endif  id="servizi_non_gruppo_{{$id}}" class="default_checkbox" /><label for="servizi_non_gruppo_{{$id}}">{!! ucfirst($servizio) !!}</label>
							</div>
						@endforeach
					</div>

					@if (isset($trattamenti) && count($trattamenti))
						{{-- <hr> --}}
						<a title="Trattamenti" class="placeholder">{{ ucfirst(trans('labels.menu_trat')) }}</a>
					  	@foreach(["ai" => trans('hotel.trattamento_ai'), "pc" => trans('hotel.trattamento_pc'), "mp" => trans('hotel.trattamento_mp'), "bb" => trans('hotel.trattamento_bb'), "sd" => trans('hotel.trattamento_sd')] as $id => $nome_trattamento)
					    	<div class="sep_gruppi">
						    	<input type="checkbox" name="trattamenti[]" value="{{$id}}" @if (is_array(old('trattamenti')) && in_array($id, old('trattamenti'))) checked @endif  id="trattamento_{{$id}}" class="default_checkbox" /><label for="trattamento_{{$id}}">{!! $nome_trattamento !!}</label>
					    	</div>
					 	 @endforeach
					@endif
					
						{{-- <hr> --}}
					@if (isset($parola_chiave_check) && count($parola_chiave_check) || count($kw_arr))
						<a title="Offerte" class="placeholder">{{trans('hotel.n_off')}}</a>
						@if (isset($parola_chiave_check) && count($parola_chiave_check))
				    		@foreach ($parola_chiave_check as $key => $chiave)
				    			@php
				    				$crea_check_off = $key;
				    			@endphp
			    				<div class="sep_gruppi">
					    		<input type="checkbox" name="parola_chiave[]" value="{{$key}}" @if (is_array(old('parola_chiave')) && in_array($key, old('parola_chiave'))) checked @endif  id="parola_chiave{{$key}}" class="default_checkbox" /><label for="parola_chiave{{$key}}">{!! $chiave !!}</label>
					    		</div>
				    		@endforeach
						@endif
						@if (count($kw_arr))
				    		@foreach ($kw_arr as $key => $chiave)
			    				<div class="sep_gruppi">
				    			<input type="checkbox" name="parola_chiave[]" value="{{$key}}" @if (is_array(old('parola_chiave')) && in_array($key, old('parola_chiave'))) checked @endif  id="parola_chiave{{$key}}" class="default_checkbox" /><label for="parola_chiave{{$key}}">{!! $chiave !!}</label>
						    	</div>
				    		@endforeach
						@endif
					@endif

					@if (isset($crea_check_pp) && $crea_check_pp)
						{{-- <hr> --}}
						<a title="Early Booking" class="placeholder">Early Booking</a>
							@foreach ($prenota_prima_check as $key => $chiave)
							@php
								$crea_check_pp = $key;
							@endphp
					    	<div class="sep_gruppi">
						    	<input type="checkbox" name="listing_offerta_prenota_prima" value="{{$key}}" @if (old('listing_offerta_prenota_prima') || $key == $listing_offerta_prenota_prima) checked @endif  id="listing_offerta_prenota_prima" class="default_checkbox" /><label for="listing_offerta_prenota_prima">{!! $chiave !!}</label>
					    	</div>
					    	@endforeach
					@endif

					@if (isset($crea_check_bg) && $crea_check_bg)
						{{-- <hr> --}}
						<a title="Bambini gratis" class="placeholder">{{trans('hotel.offerte_bg_top_box')}}</a>
							@foreach ($bambini_gratis_check as $key => $valore)
							@php
								$crea_check_bg = $key;
							@endphp
					    	<div class="sep_gruppi">
						    	<input type="checkbox" name="listing_bambini_gratis" value="{{$key}}" @if (old('listing_bambini_gratis') || $key == $listing_bambini_gratis) checked @endif  id="listing_bambini_gratis" class="default_checkbox" /><label for="listing_bambini_gratis">{!! $valore !!}</label>
					    	</div>
					    	@endforeach
					@endif


					@if (isset($crea_check_green) && $crea_check_green)
						{{-- <hr> --}}
						<a title="Green booking" class="placeholder">Green booking</a>
							@foreach ($green_check as $key => $valore)
							@php
								$crea_check_green = $key;
							@endphp
					    	<div class="sep_gruppi">
						    	<input type="checkbox" name="listing_green_booking" value="{{$key}}" @if (old('listing_green_booking') || $key == $listing_green_booking) checked @endif  id="listing_green_booking" class="default_checkbox" /><label for="listing_green_booking">{!! $valore !!}</label>
					    	</div>
					    	@endforeach
					@endif


					@if (isset($crea_check_bonus) && $crea_check_bonus)
						{{-- <hr> --}}
						<a title="Bonus vacanze" class="placeholder">Bonus vacanze</a>
							@foreach ($bonus_check as $key => $valore)
							@php
								$crea_check_bonus = $key;
							@endphp
					    	<div class="sep_gruppi">
						    	<input type="checkbox" name="listing_bonus_vacanze_2020" value="{{$key}}" @if (old('listing_bonus_vacanze_2020') || $key == $listing_bonus_vacanze_2020) checked @endif  id="listing_bonus_vacanze_2020" class="default_checkbox" /><label for="listing_bonus_vacanze_2020">{!! $valore !!}</label>
					    	</div>
					    	@endforeach
					@endif


					@if (isset($crea_check_eco) && $crea_check_eco)
						{{-- <hr> --}}
						<a title="Eco sotenibili" class="placeholder">{{trans('hotel.eco')}}</a>
							@foreach ($eco_check as $key => $valore)
							@php
								$crea_check_eco = $key;
							@endphp
					    	<div class="sep_gruppi">
						    	<input type="checkbox" name="listing_eco_sostenibile" value="{{$key}}" @if (old('listing_eco_sostenibile') || $key == $listing_eco_sostenibile) checked @endif  id="listing_eco_sostenibile" class="default_checkbox" /><label for="listing_eco_sostenibile">{!! $valore !!}</label>
					    	</div>
					    	@endforeach
					@endif

					
					

					{!! Form::hidden('lat',$lat) !!}
					{!! Form::hidden('long',$long) !!}
					{!! Form::hidden('start_label',$start_label) !!}
					{!! Form::hidden('macrolocalita_id',$macrolocalita_id) !!}
					{!! Form::hidden('cms_pagina_uri',$cms_pagina_uri) !!}
					{!! Form::hidden('canonical_uri',$canonical_uri) !!}
					{!! Form::hidden('localita_id',$localita_id) !!}
					{!! Form::hidden('ancora',$ancora) !!}
					{!! Form::hidden('localita',$localita) !!}
					{!! Form::hidden('adding_group_id',$adding_group_id) !!}

					@if (isset($crea_check_off))
						{!! Form::hidden('crea_check_off',$crea_check_off) !!}
					@endif

					@if (isset($crea_check_pp))
						{!! Form::hidden('crea_check_pp',$crea_check_pp) !!}
					@endif		

					@if (isset($crea_check_bg))
						{!! Form::hidden('crea_check_bg',$crea_check_bg) !!}
					@endif

					@if (isset($crea_check_green))
						{!! Form::hidden('crea_check_green',$crea_check_green) !!}
					@endif

					@if (isset($crea_check_bonus))
						{!! Form::hidden('crea_check_bonus',$crea_check_bonus) !!}
					@endif

					@if (isset($crea_check_eco))
						{!! Form::hidden('crea_check_eco',$crea_check_eco) !!}
					@endif

					@if (isset($crea_check_residence))
						{!! Form::hidden('crea_check_residence',$crea_check_residence) !!}
					@endif


					{!! Form::hidden('clientiJsonField','',["id" => "clientiJsonField"]) !!}
				
				{{-- <input type="submit" name="vai" value="vai" /> --}}	
				{!! Form::close() !!}
				{{-- <hr> --}}
				
			</div>
		</div>

		<script>


			// Dizionario 
			var dizionario		= {};

	        dizionario.spiaggia = "{{trans('labels.spiaggia')}}"
	        dizionario.stazione = "{{trans('labels.stazione')}}"
	        dizionario.fiera = "{{trans('labels.fiera')}}"


	        dizionario.prezzo_min = "{{trans('labels.prezzo_min')}}"
	        dizionario.p_max = "{{trans('labels.p_max')}}"

	      

			var console=console?console:{log:function(){}}
			var apikey = 'AIzaSyCAyCUJ63a6dtvWfdAaqCmLxrWqOombjM8';
			var __lat = {{$lat}};
			var __lon = {{$long}};
			var __clienti_json = "{{$clientiJson}}";
			var __own_poi_json = "{{$own_poiJson}}";
			var __count = {{$clienti_count ?? 0}};
			var __locale = "{{$locale}}";
			

			//__clienti_json = JSON.parse(__clienti_json.replace(/ 0+(?![\. }])/g, ' '));
			__clienti_json = JSON.parse(__clienti_json.replace(/&quot;/g,'"'));


			__own_poi_json = __own_poi_json.replace(/&lt;/g,'<');
			__own_poi_json = __own_poi_json.replace(/&gt;/g,'>');
			__own_poi_json = __own_poi_json.replace(/&#039;/g,"'");
			__own_poi_json = __own_poi_json.replace(/&quot;/g,'"');
			
			//console.log('__own_poi_json = '+__own_poi_json);

			__own_poi_json = JSON.parse(__own_poi_json);


			$(function() {

				// Carico asincrono il framework di google maps
				$.getScript( '//maps.googleapis.com/maps/api/js?key=' + apikey + '&amp;language=it' )
					.done(function( script, textStatus ) { 
						window.initializeListingRicerca(__lat, __lon, __clienti_json, __count, __own_poi_json, dizionario, __locale);
					}
				);

			});



			$( document ).ready(function() {

				function postForm(){
			         var data = $("#filter-map").serialize();
					
					var current_url = window.location.href;
					//console.log('current_url = '+current_url);
					
					$.ajax({
					    url: '{{url("mappa-ricerca")}}',
					    type: 'POST',
					    data: data,
					    async: false,
					    success: function(clientiJson) {
					    	//console.log('clientiJson = '+clientiJson);
					    	$('#clientiJsonField').val(clientiJson);
					    }
					});
				};

				/*$(".default_checkbox").change(function(e){
					e.preventDefault();
					postForm();
					clientiJS =  $("#clientiJsonField").val();
					//console.log('clientiJS = '+clientiJS);
					window.resetMarkers();
					clientiJS = JSON.parse(clientiJS.replace(/&quot;/g,'"'));
					window.createMarkers(clientiJS);

				});*/


				$(".default_checkbox").change(function(e){
					e.preventDefault();
					$("#filter-map").submit();
				});



				$("#closeinfowindow_container_ricerca").click(function(e){
					e.preventDefault();
					var url = "{{ url( $cms_pagina_uri ) }}";
					document.location.href = url;				
				});

			});






		</script>



	</body>

</html>