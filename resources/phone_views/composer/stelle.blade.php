<?php

/**
 *
 * visualizzo le stelle all'hotel:
 * @parameters: categoria_id, hotel_id
 *
 *
 *
 */

$stelle = "";
$sup = '';

if ($categoria_id == 6) 
  {
  $sup = 'Y';
  $categoria_id = 3;
  }

// ECCEZIONE "Centro Mare e Vita Cervia"
if ($hotel_id != 1201) 
  {
  $stelle .= '<ul class="star-rating">';
  for ($num_star = 0; $num_star < $categoria_id; $num_star++) 
    {
    $stelle .= '<li><a>' . $num_star . '</a></li>';
    }
  if ($sup == 'Y') 
    {
    $stelle .= '<li class="sup">Sup</li>';
    }
  }
  $stelle .= '</ul>';


echo $stelle;
?>