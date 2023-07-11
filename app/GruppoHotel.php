<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GruppoHotel extends Model
{
	// tabella in cui vengono salvati i record 
	protected $table = 'tblGruppoHotel';
	// attributi NON mass-assignable
	protected $guarded = ['id'];


	public function hotels()
	  {
	  return $this->hasMany('App\Hotel','gruppo_id','id');
	  }



	 public function getHotels()
	 	{
	 	if (!$this->hotels()->count()) 
	 		{
	 		return "Nessun hotel";
	 		} 
	 	else 
	 		{
	 		$hotels = [];
	 		$collection = $this->hotels()->pluck('nome','id');
	 		foreach ($collection as $id => $nome) 
	 			{
	 			$hotels[] = '(' . $id .')' . ' ' .$nome;
	 			}

	 		return implode(', ', $hotels);
	 		}
	 	
	 	}

}
