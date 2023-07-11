<?php

//crea l'array da dare in pasto alla select per creare la select multipla delle localita
//tramite lo script jquery.multiselect.min.js
// @parameters : $macro (collection macro eager loading localita)

$select = '<div class="drop"><select name="multiple_loc[]" id="multiple_loc" multiple="multiple" size="10">';

foreach ($macro as $m) 
  {
  $select.= "<optgroup label='$m->nome'>";
  $localita = $m->localita()->orderBy('nome', 'asc')->get();
  
  // messa in session dal RichiestaMailMultiplaRequest form validation
  if (Session::has('id_loc'))
    {
    foreach ($localita as $l) 
      {
      
      // messa in session dal RichiestaMailMultiplaRequest form validation
      if (Session::get('id_loc') == $l->id) 
        {
        $select.= "<option value='$l->id' selected='selected'>$l->nome</option>";
        } 
      else
        {
        $select.= "<option value='$l->id'>$l->nome</option>";
        }
      }
    } 
  else
    {
    foreach ($localita as $l) 
      {
      
      $select.= "<option value='$l->id'>$l->nome</option>";
      }
    }
  
  $select.= "</optgroup>";
  }

$select.= "</select></div>";

echo $select;
?>