<?php
namespace App\Http\Controllers\Admin;

use App\CmsPagina;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\VetrineOfferteTopRequest;
use App\Utility;
use App\VetrinaBambiniGratisTopLingua;
use App\VetrinaOffertaTop;
use App\VetrinaOffertaTopLingua;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SessionResponseMessages;

class SUPERVetrinaTopController extends AdminBaseController
{

	protected static $formule = ['' => '', 'sd' => 'Solo dormire', 'bb' => 'Bed&Brakfast', 'fb' => 'Pensione completa', 'hb' => 'Mezza pensione', 'ai' => 'All inclusive', 'tt' => 'Tutti'];
	protected static $mesi = [
	'1' => 'Gennaio',
	'2' => 'Febbraio',
	'3' => 'Marzo',
	'4' => 'Aprile',
	'5' => 'Maggio',
	'6' => 'Giugno',
	'7' => 'Luglio',
	'8' => 'Agosto',
	'9' => 'Settembre',
	'10' => 'Ottobre',
	'11' => 'Novembre',
	'12' => 'Dicembre',
	];


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// QUESTE COSTANTI DEFINISCON IL LIMITE DEL TESTO SIA PER I CONTROLLI LATO CLIENT JS, SIA PER IL VALIDATOR DI LARAVEL //
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// minima data selezionabile come inizio validità
	const MIN_DAL = 0;

	// massima data selezionabile come fine validità (8 mesi)
	const MAX_AL_NUMBER = 8;


	const LIMIT_TITOLO = 70;
	const LIMIT_TESTO = 600;

	const LIMIT_TITOLO_it = 70;
	const LIMIT_TESTO_it = 600;

	const LIMIT_TITOLO_en = 90;
	const LIMIT_TESTO_en = 620;

	const LIMIT_TITOLO_fr = 100;
	const LIMIT_TESTO_fr = 630;

	const LIMIT_TITOLO_de = 120;
	const LIMIT_TESTO_de = 650;


	protected function _getYears()
	{
		$current_year = Carbon::now()->year;
		return array($current_year-1,$current_year,$current_year+1,$current_year+2);
	}


	protected function _getMesi($anni = [])
	{
		$mesi = [];
		foreach ($anni as $anno) 
			{
			unset($mesi_anno);
			foreach (self::$mesi as $count => $mese) 
				{
				$mesi_anno[$count .'-'.$anno] = $mese;	
				}
				$mesi[$anno] = $mesi_anno;
		}	

		return $mesi;

	}


	/**
	 * [setMesiInRequest i mesi sono raggruppati per anni, ma li devo mettere in un unico array e lo metto nella reques
	 *


		"mese2016" => array:2 [▼
		    0 => "11-2016"
		    1 => "12-2016"
		  ]
		  "mese2017" => array:4 [▼
		    0 => "1-2017"
		    1 => "2-2017"
		    2 => "6-2017"
		    3 => "7-2017"
		  ]
		
			$mesi = array:6 [▼
							  0 => "11-2016"
							  1 => "12-2016"
							  2 => "1-2017"
							  3 => "2-2017"
							  4 => "6-2017"
							  5 => "7-2017"
							]

	 */
	protected function preparaMesiPerRequest($request)
		{
			$anni = self::_getYears();

			$mesi = [];

			foreach ($anni as $anno) 
				{
				if($request->filled('mese'.$anno))
					{
						foreach ($request->get('mese'.$anno) as $count => $mese)
							{
							$mesi[] = $mese;	
							}
					}
				}

			return $mesi;

		}


	protected function _pass_limitations_to_form(&$data, $offerta = null)
	{

		$data["LIMIT_TITOLO"] = self::LIMIT_TITOLO;
		$data["LIMIT_TESTO"] = self::LIMIT_TESTO;

		$data["LIMIT_TITOLO_it"] = self::LIMIT_TITOLO_it;
		$data["LIMIT_TESTO_it"] = self::LIMIT_TESTO_it;

		$data["LIMIT_TITOLO_en"] = self::LIMIT_TITOLO_en;
		$data["LIMIT_TESTO_en"] = self::LIMIT_TESTO_en;

		$data["LIMIT_TITOLO_fr"] = self::LIMIT_TITOLO_fr;
		$data["LIMIT_TESTO_fr"] = self::LIMIT_TESTO_fr;

		$data["LIMIT_TITOLO_de"] = self::LIMIT_TITOLO_de;
		$data["LIMIT_TESTO_de"] = self::LIMIT_TESTO_de;


		/*    if (is_null($offerta))
      {
      $data["MIN_DAL"] = self::MIN_DAL;
      }
    else
      {
      $oggi = Carbon::now();
      // se l'offerta che edito parte da prima di oggi, allora quella data sarà il minimo inizio
      $data["MIN_DAL"] = ($offerta->valido_dal->lt($oggi)) ? '-' . $oggi->diffInDays($offerta->valido_dal) : 0;
      }*/

		$data["MIN_DAL"] = self::MIN_DAL;
		$data["MAX_AL"] = "+". self::MAX_AL_NUMBER . "M";

	}



	// metodo richiamato solo nello store
	// perché serve PRIMA di PASSARE il teso AL TRADUTTORE GT
	protected function _leggiTesto(&$titolo, &$testo)
	{
		// prima di passarla al traduttore
		// la versione italiana deve avere i <br>
		$titolo = nl2br($titolo);
		$testo = nl2br($testo);

		$titolo = str_replace("\n", '', $titolo); // remove new lines
		$titolo = str_replace("\r", '', $titolo); // remove carriage returns


		$testo = str_replace("\n", '', $testo); // remove new lines
		$testo = str_replace("\r", '', $testo); // remove carriage returns
	}


	// metodo da chiamare SEMPRE prima di salvare nel DB
	// rimette i tag html e LIMITA il numeto di <BR>
	protected function _scriviTesto(&$testo)
	{
		$testo = strip_tags($testo, "<br>"); // tolgo tutto html tranne <br>
		$testo = nl2br($testo); // sostituisco il br html per andare a capo sul web
		$testo = str_replace("\n", '', $testo); // remove new lines
		$testo = str_replace("\r", '', $testo); // remove carriage returns
		$testo = str_replace("\t", '', $testo); // remove carriage returns

		$testo = str_replace("<br /> ", '<br />', $testo); // remove spaces after <br>

		/* DOVE SONO PIU' DI 1 VOGLIO AL MASSIMO 2 br (a capo + 1 riga vuota)*/
		$pattern = '/(<br \/>){2,1000}/i';
		$replacement = '<br /><br />';

		$new_testo = preg_replace($pattern, $replacement, $testo);

		if (!is_null($new_testo)) {
			$testo = $new_testo;
		}

	}


	// metodo da chiamare SEMPRE quando si legge dal DB
	// ripristina i caratteri per visualizzazione corretta in text editor
	protected function _preparaPerWeb(&$testo)
	{

		$testo = preg_replace('#<br\s*/?>#i', "\n", $testo); // rimetto gli a capo NON web

	}


	// metodo richiamato solo nello store
	// sostituisce ## con lo span no-translate per il testo da NON tradurre
	protected function _processNoTranslateTag(&$titolo, &$testo)
	{
		$content_processed = preg_replace_callback(
			'|#(.+?)#|s',
			function($matches){
				return "<span translate=\"no\">".$matches[1]."</span>";
			},
			$titolo
		);

		$titolo = $content_processed;

		$content_processed = preg_replace_callback(
			'|#(.+?)#|s',
			function($matches){
				return "<span translate=\"no\">".$matches[1]."</span>";
			},
			$testo
		);

		$testo = $content_processed;
	}


	public static function getMeseStr($s)
	{

		if (empty($s)) {
			return "NESSUN MESE SELEZIONATO !!!!";
		}

		list($n,$y) = explode('-',$s);	

		if ($n > 0 && $n < 13) {
			return self::$mesi[$n] . '-'. $y;
		}
		else {
			return "NESSUN MESE SELEZIONATO !!!!";
		}


	}



}
