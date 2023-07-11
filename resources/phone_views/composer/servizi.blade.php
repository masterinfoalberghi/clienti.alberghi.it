<?php 
/**
 *
 * visualizzo servizi associati all'hotel:
 * @parameters: cat_servizi (array ctaegorie e servizi in lingua corrispondenti)
 *
 *
 	dd($cat_servizi);
    array:6 [▼
      "Servizi gratuiti" => array:3 [▼
        0 => "aria condizionata in camera"
        1 => "servizio navetta per aeroporto/stazione"
        2 => "kit animali"
      ]
      "Servizi in hotel" => array:2 [▼
        0 => "late check out"
        1 => "telo mare"
      ]
      "Servizi per bambini" => array:3 [▼
        0 => "passeggini"
        1 => "scaldabiberon"
        2 => "merenda pomeridiana"
      ]
      "Cucina" => array:2 [▼
        0 => "cucina vegana"
        1 => "cucina per celiaci"
      ]
      "Servizi in camera" => array:2 [▼
        0 => "wi-fi"
        1 => "mediaset premiun"
      ]
      "Sport e benessere" => array:2 [▼
        0 => "piscina"
        1 => "idromassaggio"
      ]
    ]
 */
 
 ?>


@if (count($cat_servizi))
	@foreach ($cat_servizi as $categoria_nome => $array_servizi)
      <?php $key = strtolower(str_replace(' ', '_',$categoria_nome)); ?> 
			{{-- @if ($categoria_nome!= "Servizi gratuiti") --}}
			<div class="row ">
				<div class="col-xs-12 servizihotel">
					<h3>{{trans('hotel.'.$key)}}</h3>
					<ul> 
			      		@foreach ($array_servizi as $id_servizio => $nome_servizio)
								
			      			<li><i class="icon-ok"></i> {!! $nome_servizio !!}<br /></li>
			      		@endforeach
				    </ul>
				</div>	
			</div>
			{{-- @endif --}}
		
	@endforeach
@endif
