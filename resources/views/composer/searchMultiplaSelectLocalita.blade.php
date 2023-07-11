<?php

//crea l'array da dare in pasto alla select per creare la select multipla delle localita
//tramite lo script jquery.multiselect.min.js
// @parameters : $macro (collection macro eager loading localita)

/*
$localita = DB::table('tblHotel')
->leftJoin('tblLocalita', 'tblHotel.localita_id'  , '=', 'tblLocalita.id' )
->select(DB::raw('DISTINCT(tblLocalita.nome), COUNT(tblHotel.localita_id) AS conteggio, tblLocalita.id AS id'))
->orderBy('tblLocalita.nome', 'asc')
->groupBy('tblHotel.localita_id')
->having('conteggio', '>', 0)
->get();*/

$array_localita = (array_key_exists('multiple_loc', $prefill)) ? $prefill['multiple_loc'] : [];

//$select = '<div class="drop"><select name="multiple_loc[]" id="multiple_loc" ';
$select = '<div class="drop"><select name="multiple_loc[]" id="multiple_loc" multiple="multiple">';

foreach ($macro as $m) {
  
  $select.= "<optgroup label=".$m->nome." >";
  $localita = $m->localita()->orderBy('nome', 'asc')->get();
  
	foreach ($localita as $l) {
			///////////////////////////////////////////////////////////////
			// prendo solo le localita che hanno almeno 1 cliente attivo //
			///////////////////////////////////////////////////////////////
			
			if ($l->clientiAttivi()->count()) {
				// messa in session dal RichiestaMailMultiplaRequest form validation
				
				if (in_array($l->id, $array_localita)) { 
					$select.= "<option value='$l->id' selected='selected'>$l->nome</option>";
				} else {
					$select.= "<option value='$l->id'>$l->nome</option>";
				}
			}
	} 
 
  $select.= "</optgroup>";
  
}

$select.= "</select></div>";

echo $select;
?>


