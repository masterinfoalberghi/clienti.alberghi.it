<?php

// visualizzo le info sul coupon del listing
// @parameters : $coupon (object or NULL)
//               $id (id hotel)


if (!is_null($coupon)) {
  $snippet = '<a href="' . url(Utility::getLocaleUrl($locale).'hotel/'.$cliente->id) . '">';
  $snippet .= '<div class="info-box">';
  $snippet .= '<strong class="title">'.Lang::get('listing.coupon').'</strong>';
  $snippet .= '<strong class="price">'.$coupon->valore.' €</strong>';
  $snippet .= '<span class="txt"> ' .Lang::get('listing.valido_dal').' <strong class="date">'.Utility::getLocalDate($coupon->periodo_dal, '%d/%m').'</strong> '. Lang::get('listing.valido_al') .' <strong class="date">'.Utility::getLocalDate($coupon->periodo_al, '%d/%m').'</strong></span>';
  $snippet .= '</div>';
  $snippet .= '</a>';
  
  echo $snippet;

}


?>