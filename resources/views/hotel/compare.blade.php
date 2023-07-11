@extends('templates.page_compare')

@section('seo_title')
	{{trans("title.compare")}}
@endsection

@include('flash')

@section('content')
	
	<div class="clearfix"></div>
	
		<div class=" padding-top">
			@if( isset($referer_compare) && isset($url_compare) && $referer_compare != $url_compare )
				<a href="#" onclick="window.history.back(); return false;" >&larr; {{trans("labels.indietro")}}: <strong>{{$title_referer}}</strong></a>
			@endif
		</div>
	
	<h1>{{trans("title.compare")}}</h1>&nbsp;&nbsp;&nbsp;


	@if ( !isset($proprieta) || !count($proprieta) )
	
		<div style="margin: 10px 0 50px 0;">
			
		<p>
			{{trans("labels.compare_1")}}
		</p>
		
		<p>
			{{trans("labels.compare_2")}}
		</p>

		<p>
			{{trans("labels.compare_3")}}
		</p>

		</div>

	@else

	
		<table id="compare" class="margin-bottom hotel-{{count($clienti)}} risultati_ricerca " style="width:100%;">
					
			@foreach($proprieta as $key => $prop)
				
				@if (

					$prop 	== trans("labels.prezzo_pers") 			|| 
					($key 	== "lastminute" 	&& $lt == false) 	|| 
					($key 	== "offerte" 		&& $of == false) 	|| 
					($key 	== "prenotaprima" 	&& $pp == false) 	||
					($key 	== "bambinigratis" 	&& $bg == false)
					
				)
				
				@else
				
				<tr>
									
					<th style="min-width: 150px;">
						@if( $prop != "x")
							{{$prop}}
						@endif
					</th>
					
					@php
					
						if (count($clienti) == 2)
							$width = "50%";
						else
							$width = "33%";
							
					@endphp
					
					@foreach($clienti as $cliente)
					
						@if( $prop == "")
						
							<td class="disabled"></td>
							
						@endif
						
						@if( $key == "immagine")
		
							<td class="immagine" width="{{$width}}">
								
								<figure class="image">
									<img src='{{ Utility::asset($cliente->getListingImg("360x200", true)) }}' class="alignleft" alt="{{{$cliente->nome}}}">
								</figure>
								
								<h2>{{$cliente->nome}}</h2><span class="rating">{{$cliente->stelle->nome}}</span><br />
								
								<div class="item-listing-price">
									@if($cliente->prezzo_min > 0) <small>{{ trans('listing.p_min') }}</small> <span class="price">{{{ $cliente->prezzo_min }}} €</span>@endif
									@if($cliente->prezzo_max > 0) <small>{{ trans('listing.p_max') }}</small> <span class="price">{{{ $cliente->prezzo_max }}} €</span>@endif
									<a href="{{Utility::getUrlWithLang($locale,'/hotel.php?id=' . $cliente->id . '&price-list')}}" class="btn btn-cyano right">
										<i class="icon-menu"></i> Prezzi
									</a>
									
									<a href="{{Utility::getUrlWithLang($locale,'/hotel.php?id=' . $cliente->id)}}" class="btn btn-arancio">
										<i class="icon-mail-alt"></i> {{trans("hotel.scrivi")}}
									</a>
							
								</div>
								
							</td>
							
						@endif
						
						@if( $key == "posizione")
						
							<td class="posizione">
								
								 @php 
		
									$img 			= 'https://maps.googleapis.com/maps/api/staticmap';
									$center 		= '?center=' . $cliente->mappa_latitudine.  ',' . $cliente->mappa_longitudine;
									$zoom 			= '&zoom=15&size=360x200';
									$marker			= '&markers=icon:https://www.info-alberghi.com/images/markers/red.png|' .  $cliente->mappa_latitudine.  ',' . $cliente->mappa_longitudine;
									$keyg 			= '&key=' . Config::get("google.googlekey");
									$link 			= $img . $center . $zoom . $marker . $keyg;
								
								@endphp
								
								<img src="{{$link}}" ><br /><br />
								
								<span class="indirizzo"> {{{ $cliente->indirizzo }}}</span> - <span class="cap">{{{ $cliente->cap }}}</span><br />
								
								@if($locale == "it")
									<span class="localita">{{ $cliente->localita->nome }}</span> - <a href="{{Utility::getUrlWithLang($locale, 'stradario/' . $cliente->localita->macrolocalita->nome)}}"><span class="macrolocalita">{{ $cliente->localita->macrolocalita->nome }}</span> ({{ $cliente->localita->prov }})</a>
								@else
									<span class="localita">{{ $cliente->localita->nome }}</span> - <span class="macrolocalita">{{ $cliente->localita->macrolocalita->nome }}</span> ({{ $cliente->localita->prov }})
								@endif<br />
								
								@include("widget.item-distance")						
								
							</td>
							
						@endif
						
						@if( $key == "annuale")
						
							<td>
								@if( $cliente->annuale)
									{{ trans('listing.annuale') }}						
								@else

									{{  Utility::myFormatLocalized($cliente->aperto_dal, '%d %B %Y', $locale) }} &rarr; {{ Utility::myFormatLocalized($cliente->aperto_al, '%d %B %Y', $locale) }}
								@endif
								
								{!! $cliente->aperture !!}
								
							</td>
							
						@endif
						
						@if( $key == "tassaSoggiorno")
							
							@if ($cliente->ts)
								<td class="tassaSoggiorno">
									{!!$cliente->ts!!}
								</td>
							@else 
								<td class="disabled"></td>
							@endif
							
							
						@endif
						
						@if( $key == "lastminute")
								@if ($cliente->nlast > 0)
								<td>
									<a class="nobutton" href="{{Utility::getUrlWithLang($locale,'/hotel.php?id=' . $cliente->id . '&lastminute')}}">
										@if ($cliente->nlast == 1)
											{{trans("hotel.1_off")}}
										@else
											{{$cliente->nlast}} {{trans("hotel.n_off")}}
										@endif
								    </a>
								</td>
							@else
								<td class="disabled"></td>
							@endif
						@endif
						
			
						@if( $key == "bambinigratis" )
							@if ( $cliente->nbg > 0)
								<td>
									<a class="nobutton" href="{{Utility::getUrlWithLang($locale,'/hotel.php?id=' . $cliente->id . '&children-offers')}}" >
										@if ($cliente->nbg == 1)
											{{trans("hotel.1_off")}}
										@else
											{{$cliente->nbg}} {{trans("hotel.n_off")}}
										@endif
								    </a>
								</td>
							@else
								<td class="disabled"></td>
							@endif
						@endif
						
						
						
						@if( $key == "prenotaprima" )
								@if ($cliente->nopp > 0)
								<td>
									<a class="nobutton" href="{{Utility::getUrlWithLang($locale,'/hotel.php?id=' . $cliente->id . '&lastminute')}}">
										@if ($cliente->nopp == 1)
											{{trans("hotel.1_off")}}
										@else
											{{$cliente->nopp}} {{trans("hotel.n_off")}}
										@endif
								    </a>
								</td>
							@else
								<td class="disabled"></td>
							@endif
						@endif
						
						@if( $key == "offerte" )
								@if ($cliente->noff > 0)
								<td>
									<a class="nobutton" href="{{Utility::getUrlWithLang($locale,'/hotel.php?id=' . $cliente->id . '&lastminute')}}">
										@if ($cliente->noff == 1)
											{{trans("hotel.1_off")}}
										@else
											{{$cliente->noff}} {{trans("hotel.n_off")}}
										@endif
								    </a>
								</td>
							@else
								<td class="disabled"></td>
							@endif
						@endif
						
						@if( $key == "poi")
						
							<td>@include('composer.puntiDiForza')</td>
						
						@endif
						
						@if( $key == "piscina")
							
							@if ( $cliente->infoPiscina)
								<td>@include('composer.infoPiscina')</td>
							@else
								<td class="disabled"></td>
							@endif
						
						@endif
		
						@if( $key == "spa")
							
							@if ($cliente->infoBenessere)
								<td>@include('composer.infoBenessere')</td>
							@else
								<td class="disabled"></td>
							@endif
							
						@endif	
						
						
			
						
					@endforeach
					
				</tr>
				
				@endif
				
			@endforeach
			
		
		</table>
	
	@endif
		
	
@endsection

