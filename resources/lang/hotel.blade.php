var $eta 			= "<?php echo trans('listing.eta') ?>";
var $eta_singolo 	= "<?php echo trans('listing.eta_singolo') ?>";
var $data_default	= "<?php echo trans('labels.data_default') ?>"

var $lat = 			'{{$cliente->mappa_latitudine}}';
var $lon = 			'{{$cliente->mappa_longitudine}}';
var $image = 		'{{Utility::asset("images/map_icons/" . $cliente->categoria_id . ".gif" , false, true)}}';

var $window = 		'<div id="info_window"> {{{ $cliente->nome }}}  {{{ $cliente->stelle->nome }}} <br> {{{$cliente->indirizzo}}} - {{{ $cliente->cap }}} - {{{ $cliente->localita->macrolocalita->nome.' ('. $cliente->localita->prov . ')' }}} </div>';
var $hotel_add = 	'{{{$cliente->indirizzo}}} - {{{ $cliente->cap }}} - {{{ $cliente->localita->macrolocalita->nome.' ('. $cliente->localita->prov . ')' }}}';

