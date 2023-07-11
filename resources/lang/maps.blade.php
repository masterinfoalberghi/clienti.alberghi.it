var $zoom = 		13;
@if (isset($google_maps) && isset($google_maps["coords"]))
	var $lat = 			'{{$google_maps["coords"]['lat']}}';
	var $lon = 			'{{$google_maps["coords"]['long']}}';
@else
	var $lat = 			'44.063333';
	var $lon = 			'12.580833';
@endif

@if ( isset($cliente) && !is_null($cliente) && $cliente instanceof App\Hotel )
	
	var $img = '{{$cliente->getListingImg('220x148', true)}}';
	
	console.log($img);
	var $nome = '{{$cliente->nome}}';
	var $rating = '{{$cliente->stelle->nome}}';
	var $loc = '{{ $cliente->localita->nome }}';

@endif

var $vedimappa = 		'{{trans('listing.vedi_hotel')}}';




	