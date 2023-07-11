<?php

// visualizzo la offerta nel listing:
// @parameters : $offerta (object oppure NULL)
//               $id (id hotel)

if (!is_null($offerta) && !is_null($offerta_lingua)) 
  {
  
  $snippet = '<div class="text text-offer">';
  $snippet .= Lang::get('listing.'.$offerta->tipologia) . ' - ';
  $snippet .= '<span class="scadenza">'.Lang::get('listing.scade_il').' '. Utility::getLocalDate($offerta->valido_al, '%d/%m/%y') . '</span>&nbsp;&nbsp;';
  $snippet .= '<span class="info"><strong><a href="' . url(Utility::getLocaleUrl($locale).'hotel.php?id='.$id) . '#offerte_last">';
  $snippet .= $offerta_lingua->titolo;
  $snippet .= '</a></strong></span>';
  $snippet .= '</div>';
  
  echo $snippet;
  }
?>