<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoBenessere extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblInfoBenessere';
  
  // attributi NON mass-assignable
  protected $guarded = ['hotel_id'];

  public function hotel()
    {
      return $this->hasOne('App\Hotel','hotel_id','id');
    }


  public function setEtaMinimaAttribute($value)
    {
    if($value == '')
      {
      $this->attributes['eta_minima'] = 0;  
      }
     else
     {
      $this->attributes['eta_minima'] = $value;  	
     }
    }

  public function getEtaMinimaAttribute($value)
   {  
   if($value == 0)
   	{
    return '';
   	}
   else
   	{
   	return $value;
   	}
   }


  }
