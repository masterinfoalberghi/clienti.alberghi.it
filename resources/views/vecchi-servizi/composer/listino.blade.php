<?php

// visualizzo il listino standard
// @parameters : $listini (array listini), titolo (nel composer viene costruito aggiungendo anno_listino cioè anno ricavato dagli anni dei periodi)

if (count($listini)) 
  {
  echo '<div class="margin-bottom">';
  echo '<table class="content" >
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
			</thead>
	    <tbody>';
	    
  foreach ($listini as $listino) 
    {
    echo '<tr>'.
	    '<td width="20%">' . Utility::myFormatLocalized($listino->periodo_dal, '<b>%e %b</b> ', $locale) . ' - ' . Utility::myFormatLocalized($listino->periodo_al, '<b>%e %b</b>', $locale) . '</td>';
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
