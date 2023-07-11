<?php

// visualizzo le info sul periodo bambino gratis del listing
// @parameters : $bambino_gratis (object or NULL)
//               $id (id hotel)


if (!is_null($bambino_gratis)) {
  
  $snippet = '<div class="text text-bimbigratis">';
  $snippet.= '<span class="scadenza">dal' . Utility::getLocalDate($bambino_gratis->valido_al, '%d/%m/%y') . 'al' . Utility::getLocalDate($bambino_gratis->valido_al, '%d/%m/%y') . '</span>';
  $snippet.= '<span class="info"><strong><a href="' . url(Utility::getLocaleUrl($locale).'hotel.php?id='.$id) . '#bimbi_gratis">';
  $snippet.= ' BAMBINI GRATIS fino a ' . $bambino_gratis->_fino_a_anni() . ' ' . $bambino_gratis->anni_compiuti;
  $snippet.= '</a></strong></span>';
  $snippet.= '</div>';
  
  echo $snippet;

}

?>