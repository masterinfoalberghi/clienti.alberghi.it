<?php

use App\ParolaChiave;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class addIntervalliDateMappaRIcercaParolwCHiave extends Seeder
{


		public static $mappaServizi =
			// Inizio, fine ,descrizione)
			array(
							"4" => ["2018-2-1", "2018-9-30","2 Giugno"],
						  "6" => ["2018-2-1", "2018-9-30","1 Maggio"],
						  "7" => ["2018-5-1", "2018-9-30","Agosto"],
						  "8" => ["2018-5-1", "2018-9-30","Luglio"],
						  "9" => ["2018-2-1", "2018-9-30","Giugno"],
						  "10" =>["2018-2-1", "2018-9-30", "Maggio"],
						  "11" =>["2018-2-1", "2018-4-25", "25 Aprile"],
						  "22" =>["2018-2-1", "2018-9-30", "Terme"],
						  "25" =>["2018-5-1", "2018-9-30", "Ferragosto"],
						  "26" =>["2018-3-1", "2018-9-30", "Notte rosa"],
						  "64" =>["2018-2-1", "2018-9-30", "Fiera Rimini"],
						  "68" =>["2018-2-1", "2018-4-1", "Pasqua"],
						);

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	foreach (self::$mappaServizi as $id => $value) 
    		{
    		list($dal, $al, $descrizione) = $value;
    		$dal_carbon = Carbon::createFromFormat('Y-m-d', $dal);
    		$al_carbon = Carbon::createFromFormat('Y-m-d', $al);
    		$kw = ParolaChiave::find($id);
    		$kw->mappa_dal = $dal_carbon;
    		$kw->mappa_al = $al_carbon;
    		$kw->save();
    		}
    }

}
