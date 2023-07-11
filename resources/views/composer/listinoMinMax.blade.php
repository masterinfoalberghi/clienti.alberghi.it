<?php

// visualizzo il listino con i prezzi min e max
// @parameters : $listinoMinMax (collection listinoMinMax)

if (isset($listinoMinMax) && count($listinoMinMax) > 0) 
  {
  echo '<div class="margin-bottom">
  		<table class="content" >
	      <thead>
	      <tr>
	      	<th colspan="6" class="title-header"><h4 class="title">'. $titolo . '</h4></th>
	      </tr>
	       <tr>
            <th width="20%">'.trans('hotel.periodo').'</th>
            <th width="16%">'.strtoupper(trans('hotel.trattamento_ai')).'</th>
            <th width="16%">'.strtoupper(trans('hotel.trattamento_pc')).'</th>
            <th width="16%">'.strtoupper(trans('hotel.trattamento_mp')).'</th>
            <th width="16%">B & B</th>
            <th width="16%">'.strtoupper(trans('hotel.trattamento_sd')).'</th>
          </tr>
        </thead>';
	       

  echo '<tbody>';   
  
  foreach ($listinoMinMax as $listino) 
  { 
    echo '<tr>' .
	    '<td width="20%">' . Utility::getLocalDate($listino->periodo_dal, '<b>%e %b</b>') . ' - ' . Utility::getLocalDate($listino->periodo_al, '<b>%e %b</b>') . '</td>';
	    echo $listino->getPrezzoeNumeroOfferte($listino->prezzo_ai);
	    //echo $listino->getPrezzoeNumeroOfferte($listino->prezzo_pc);
	    echo $listino->getPrezzoeNumeroOfferte($listino->prezzo_fb);
	    //echo $listino->getPrezzoeNumeroOfferte($listino->prezzo_mp);
	    echo $listino->getPrezzoeNumeroOfferte($listino->prezzo_hb);
	    echo $listino->getPrezzoeNumeroOfferte($listino->prezzo_bb);
	    echo $listino->getPrezzoeNumeroOfferte($listino->prezzo_sd);
    '</tr>';
    
    
  }
  
  echo '</tbody></table></div>';
  
  }
?>

