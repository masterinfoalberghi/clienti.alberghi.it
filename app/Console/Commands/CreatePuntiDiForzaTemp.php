<?php

namespace App\Console\Commands;

use App\CmsPagina;
use App\Hotel;
use App\Utility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatePuntiDiForzaTemp extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'create:pdf_temp';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Popola le colonne pti di forza e pti di forza slug della tabella hotel;';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		///////////////////////////////////////////////////
		// trovo i pdf raggruppati per nome e per lingua //
		///////////////////////////////////////////////////


		/*
        Collection di:

        .........

        578 => {#1645
              +"nome": "aria condizionata"
              +"lang_id": "it"
              +"n": 196
            }
        578 => {#1645
              +"nome": "aria condizionata"
              +"lang_id": "it"
              +"n": 196
            }

        .....

         */


		$pdf_count = Utility::getPtiForzaGrouped();

		$slugs = [];

		/* ottengo un array di noni e slug associati*/
		foreach ($pdf_count as $value) {
			$slugs[strtolower($value->nome)] = Str::slug(strtolower($value->nome));
		}

		$clienti = Hotel::with([
			'puntiDiForza',
			'localita.macrolocalita'
			])
		->attivo()
		->get();

		$new_pages = [];
		$inserted_uri = [];

		foreach ($clienti as $cliente) {

			$tmp_punti_di_forza_it = '';
			$tmp_punti_di_forza_en = '';
			$tmp_punti_di_forza_fr = '';
			$tmp_punti_di_forza_de = '';

			$tmp_punti_di_forza_slug_it = '';
			$tmp_punti_di_forza_slug_en = '';
			$tmp_punti_di_forza_slug_fr = '';
			$tmp_punti_di_forza_slug_de = '';


			foreach ($cliente->puntiDiForza as $puntiDiForza) {

				$pdf_it = strtolower($puntiDiForza->translate('it')->first()->nome);
				
				if ($puntiDiForza->evidenza == 1) 
					$pdf_it  = "<b>" . $pdf_it . "</b>";

				if (isset($slugs[$pdf_it])) {

					$uri = $slugs[$pdf_it].'/'.strtolower($cliente->localita->macrolocalita->nome).'.php';

					// check se c'è già la pagina!!!
					// check se l'ho già messo tra gli slug da inserire MA non è ancora fisicamente nella pagina perché farò in insert cmulativo alla fine

					if (CmsPagina::where('uri', $uri)->count() == 0 && !in_array($uri, $inserted_uri)) {
						
						// creo la pagina
						$page = [];
						$page['lang_id'] = 'it';
						$page['attiva'] = 1;
						$page['template'] = 'listing';
						$page['listing_attivo'] = 1;
						$page['listing_macrolocalita_id'] = $cliente->localita->macrolocalita_id;
						$page['listing_localita_id'] = 0;
						$page['uri'] = $uri;
						$page['ancora'] = $pdf_it;
						$page['punto_di_forza'] = $slugs[$pdf_it];
						
						$new_pages[] = $page;
						$inserted_uri[] = $uri;
						$this->info("Creata pagina con uri: ".$uri."<br>");
						
					}


					// creo il pdf con il link alla pagina
					// $pdf_it = '<a href="' .url($uri).'">'.$pdf_it .'</a>';

				}


				$pdf_en = $puntiDiForza->translate('en')->first()->nome;
				$pdf_fr = $puntiDiForza->translate('fr')->first()->nome;
				$pdf_de = $puntiDiForza->translate('de')->first()->nome;



				$tmp_punti_di_forza_it .= $pdf_it . ',';
				$tmp_punti_di_forza_en .= $pdf_en . ',';
				$tmp_punti_di_forza_fr .= $pdf_fr . ',';
				$tmp_punti_di_forza_de .= $pdf_de . ',';


				$tmp_punti_di_forza_slug_it .= Str::slug($puntiDiForza->translate('it')->first()->nome, '-') . ',';
				$tmp_punti_di_forza_slug_en .= Str::slug($puntiDiForza->translate('en')->first()->nome, '-') . ',';
				$tmp_punti_di_forza_slug_fr .= Str::slug($puntiDiForza->translate('fr')->first()->nome, '-') . ',';
				$tmp_punti_di_forza_slug_de .= Str::slug($puntiDiForza->translate('de')->first()->nome, '-') . ',';

			}

			$tmp_punti_di_forza_it = rtrim($tmp_punti_di_forza_it, ',');
			$tmp_punti_di_forza_en = rtrim($tmp_punti_di_forza_en, ',');
			$tmp_punti_di_forza_fr = rtrim($tmp_punti_di_forza_fr, ',');
			$tmp_punti_di_forza_de = rtrim($tmp_punti_di_forza_de, ',');

			$tmp_punti_di_forza_slug_it = rtrim($tmp_punti_di_forza_slug_it, ',');
			$tmp_punti_di_forza_slug_en = rtrim($tmp_punti_di_forza_slug_en, ',');
			$tmp_punti_di_forza_slug_fr = rtrim($tmp_punti_di_forza_slug_fr, ',');
			$tmp_punti_di_forza_slug_de = rtrim($tmp_punti_di_forza_slug_de, ',');


			DB::table('tblHotel')
			->where('id', $cliente->id)
			->update([
				'tmp_punti_di_forza_it' => $tmp_punti_di_forza_it,
				'tmp_punti_di_forza_en' => $tmp_punti_di_forza_en,
				'tmp_punti_di_forza_de' => $tmp_punti_di_forza_de,
				'tmp_punti_di_forza_fr' => $tmp_punti_di_forza_fr,
				'tmp_punti_di_forza_slug_it' => $tmp_punti_di_forza_slug_it,
				'tmp_punti_di_forza_slug_en' => $tmp_punti_di_forza_slug_en,
				'tmp_punti_di_forza_slug_de' => $tmp_punti_di_forza_slug_de,
				'tmp_punti_di_forza_slug_fr' => $tmp_punti_di_forza_slug_fr,
				]);
		} /* endforeach clienti*/

		$this->info("<br><br>Inserimento bulk delle pagine nella tabella!! Attendere...<br>");

		DB::table('tblCmsPagine')->insert($new_pages);

		$this->info("<br><br>FINE<br>");


	}


}
