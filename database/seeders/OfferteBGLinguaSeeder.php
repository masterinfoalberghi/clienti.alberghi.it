<?php

namespace Database\Seeders;

use App\BambinoGratis;
use App\BambinoGratisLingua;
use App\Utility;
use Illuminate\Database\Seeder;

class OfferteBGLinguaSeeder extends Seeder
{


		private function mapBgToBgLingua($bg)
			{

			$note_it = $bg->note;

			$traduzioni['it'] = $note_it;

			foreach (Utility::linguePossibili() as $lingua) 
				{
				if ($lingua != 'it') 
					{

					if ($note_it != '') 
						{
						$traduzioni[$lingua] = Utility::translate($note_it, $lingua);
						} 
					else 
						{
						$traduzioni[$lingua] = '';
						}
					
					
					}
				}

			foreach ($traduzioni as $lang_id => $value) 
				{
				
				$offertaLingua = new BambinoGratisLingua;
				$offertaLingua->lang_id = $lang_id;
				$offertaLingua->note = $value;
				$offertaLingua->approvata = $bg->approvata;
				$offertaLingua->data_approvazione = $bg->data_approvazione;
				
				$bg->offerte_lingua()->save($offertaLingua);
				
				}
			
			}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
	    {

	    //$all_bg = BambinoGratis::attivo()->get();
	    $all_bg = BambinoGratis::attivo()->where('hotel_id', 430)->get();
	    
	    foreach ($all_bg as $bg) 
	    	{
	    	$this->mapBgToBgLingua($bg);
	    	}

	    }
}
