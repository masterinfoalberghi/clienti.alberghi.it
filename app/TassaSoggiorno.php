<?php

namespace App;

use Lang;
use Utility;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TassaSoggiorno extends Model
{
	
	// tabella in cui vengono salvati i record
    protected $table = 'tblTassaSoggiorno';
    // attributi NON mass-assignable
    // 
    protected $guarded = ['id'];

	public function scopeAttiva($query, $hotel_id)
    {
		return $query->where("hotel_id", $hotel_id)->where("attiva", 1);	
    }
	
	public function scopeApplicata($query, $hotel_id)
    {
		return $query->where("hotel_id", $hotel_id)->where("applicata", 1);	
    }
	
	public static function getTassaLabel ($hotel_id, $withLabel = true) {
		
		$label = "";
		if ($withLabel)
			$label = "<b>". Lang::get("labels.tassa_soggiorno") . "</b>";
				
		$tassaLabel = [];
		$bamibiniLabel = "";
		 
		$tassaSoggiorno = TassaSoggiorno::attiva($hotel_id)->first();
				
		if ($tassaSoggiorno):
			
			if ($tassaSoggiorno->applicata) {
			
				$tassaLabel[] = $label;
				
				if ($tassaSoggiorno->inclusa == 1)
					$tassaLabel[] = Lang::get("labels.tassa_inclusa");
				else 
					$tassaLabel[] = Lang::get("labels.tassa_esclusa");
				
				if ( $tassaSoggiorno->valore != "0.00")
					$tassaLabel[] = $tassaSoggiorno->valore . Lang::get("labels.tassa_esclusa_valore");
				
				if ($tassaSoggiorno->max_giorni != 0 )
					$tassaLabel[] = Lang::get("labels.tassa_massimo") . $tassaSoggiorno->max_giorni . Lang::get("labels.tassa_giorno");
				
				
							
				if ($tassaSoggiorno->bambini_esenti == 1 && $tassaSoggiorno->eta_bambini_esenti == 0)
					$tassaLabel[] = Lang::get("labels.tassa_bambini_esenti");
					
				if ($tassaSoggiorno->bambini_esenti == 1 && $tassaSoggiorno->eta_bambini_esenti != 0)
					$tassaLabel[] = Lang::get("labels.tassa_bambini_esenti_valore_1") . $tassaSoggiorno->eta_bambini_esenti . Lang::get("labels.tassa_bambini_esenti_valore_2");
				
				if ($tassaSoggiorno->validita_data == 1 ) {
						
					$data_iniziale = Carbon::createFromFormat("Y-m-d", $tassaSoggiorno->data_iniziale);
					$data_finale = Carbon::createFromFormat("Y-m-d", $tassaSoggiorno->data_finale);
					
					$data_iniziale = Utility::getLocalDate($data_iniziale, '%e %b');
					$data_finale = Utility::getLocalDate($data_finale, '%e %b');
									
					$tassaLabel[]= Lang::get("labels.tassa_valida") . $data_iniziale . Lang::get("labels.tassa_al") . $data_finale;
				}
				
			} else {
				
				$tassaLabel[] = $label;
				$tassaLabel[] = Lang::get("labels.tassa_non_applicata");
				$tassaLabel["NA"] = 1;
				
			} 
					
		endif;
		
		return $tassaLabel;
		
	}
	
}
