<?php

namespace App\Http\Controllers\Admin;

use App\CmsPagina;
use App\Hotel;
use App\Http\Requests\Admin\VetrineBambiniGratisTopRequest;
use App\ScadenzaVtt;
use App\Utility;
use App\VetrinaBambiniGratisTop;
use App\VetrinaBambiniGratisTopLingua;
use App\VetrinaOffertaTopLingua;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Langs;
use SessionResponseMessages;

class VetrinaBambiniGratisTopController extends SUPERVetrinaTopController
{

	


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// filtro le pagine da associare per lingua e per la macrolocalità dell'hotel + quelle TRASVERSALI, cioè senza località e senza macro //
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////
	// un'offerta TOP di un hotel di RImini NON SARA' associata ad una pagina di Cervia //
	//////////////////////////////////////////////////////////////////////////////////////
	///
	private function _getPages($macrolocalita_id = 0, $lang_id = 'it')
	{

		/////////////////////////////////////////////////////////////////////////////
		// devo trovare le pagine già associate alle "OFFERTE TOP" per ESCLUDERLE  //
		/////////////////////////////////////////////////////////////////////////////

		//////////////////////////////////////////////
		// SE UNA PAGINA HA VAAT NON PUO' AVERE VOT //
		//////////////////////////////////////////////

		$ids_pagine_vetrine_top =  VetrinaOffertaTopLingua::where('pagina_id', '!=', 0)->distinct()->pluck('pagina_id');

		return CmsPagina::attiva()
		->lingua($lang_id)
		->listingMacroLocalitaOrTrasversali($macrolocalita_id)
		->vetrinaTopEnabled()
		->where('listing_bambini_gratis', 1)
		->whereNotIn('id', $ids_pagine_vetrine_top)
		->pluck('uri', 'id');

	}


	// metodo richiamato solo nello store
	// sostituisce ## con lo span no-translate per il testo da NON tradurre
	private function _processNoTranslateTagVAAT(&$note)
	{

		$content_processed = preg_replace_callback(
			'|#(.+?)#|s',
			function($matches){
				return "<span translate=\"no\">".$matches[1]."</span>";
			},
			$note
		);

		$note = $content_processed;
	}




	// metodo da chiamare SEMPRE prima di salvare nel DB
	// rimette i tag html e LIMITA il numeto di <BR>
	private function _scriviTestoVAAT(&$note)
	{
		$note = strip_tags($note, "<br>"); // tolgo tutto html
		$note = nl2br($note); // sostituisco il br html per andare a capo sul web
		$note = str_replace("\n", '', $note); // remove new lines
		$note = str_replace("\r", '', $note); // remove carriage returns
		$note = str_replace("\t", '', $note); // remove carriage returns

		$note = str_replace("<br /> ", '<br />', $note); // remove spaces after <br>

		/* DOVE SONO PIU' DI 1 VOGLIO AL MASSIMO 1 br (a capo + NESSUNA riga vuota)*/
		$pattern = '/(<br \/>){1,1000}/i';
		$replacement = '<br />';

		$new_testo = preg_replace($pattern, $replacement, $note);

		if (!is_null($new_testo)) {
			$note = $new_testo;
		}

	}


	// metodo richiamato solo nello store
	// perché serve PRIMA di PASSARE il teso AL TRADUTTORE GT
	private function _leggiTestoVAAT(&$note)
	{
		
		// prima di passarla al traduttore
		// la versione italiana deve avere i <br>
		$note = nl2br($note);
		$note = str_replace("\n", '', $note); // remove new lines
		$note = str_replace("\r", '', $note); // remove carriage returns

	}


	private function _creaOfferta($request, &$bambino_gratis)
	{

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);
		$bambino_gratis->attivo = 1;
		$bambino_gratis->hotel_id = $hotel_id;

		$bambino_gratis->mese = implode(',', $request->get('mese'));
		$bambino_gratis->fino_a_anni = (int)$request->input("fino_a_anni");
		$bambino_gratis->anni_compiuti = $request->input("anni_compiuti");
		$bambino_gratis->valido_dal = Utility::getCarbonDate($request->get('valido_dal'));
		$bambino_gratis->valido_al = Utility::getCarbonDate($request->get('valido_al'));
		$bambino_gratis->save();

	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($message = "")
	{
		$hotel_id = $this->getHotelId();
		$offerte = VetrinaBambiniGratisTop::where('hotel_id', $hotel_id)
		->nonArchiviata()
		->with(['offerte_lingua.pagina'])
		->get();

		$mese = date('n').'-'.date('Y');

		return view('admin.vetrina_bambini_gratis_top_index', compact('offerte', 'mese', 'message'));
	}


	/**
	 * Mostra l'interfaccia di editing
	 *
	 * @return Response
	 */
	public function edit($id)
	{

		$data = [];

		// se ho perso la sessione dell'hotel NON devo poter modificare un'offerta che è la sua
		//$offerta = Offerta::with(['offerte_lingua'])->find($id);

		$hotel_id = $this->getHotelId();

		$macrolocalita_id = Hotel::find($hotel_id)->localita->macrolocalita_id;

		$offerta = VetrinaBambiniGratisTop::with(['offerte_lingua','scadenza'])->where('hotel_id', $hotel_id)->where('id', $id)->get()->first();

		$offertaLingua = [];
		$pagine = [];
		foreach ($offerta->offerte_lingua as $offerta_lingua) {

			$arr = [];

			$note = $offerta_lingua->note;

			$this->_preparaPerWeb($note);

			$arr['note'] = $note;

			$arr['pagina_id'] = $offerta_lingua->pagina_id;

			$offertaLingua[$offerta_lingua->lang_id][] = $arr;

			$pagine[$offerta_lingua->lang_id] = $this->_getPages($macrolocalita_id, $offerta_lingua->lang_id);

		}

		$data['bambino_gratis'] = $offerta;
		$data['bambino_gratisLingua'] = $offertaLingua;
		$data['pagine'] = $pagine;
		$data['showButtons'] = 1;

		$anni = self::_getYears();

		$mesi = self::_getMesi($anni);
		
		$data['mesi'] = $mesi;

		return view('admin.vetrina_bambini_gratis_top_form', $data);
	}



	private function _creaScadenza($request, &$offerta)
		{
				if($request->get('scadenza_al') != '')
					{

					if ($offerta->has('scadenza') && !is_null($offerta->scadenza)) 
						{
						$scadenza = $offerta->scadenza;	
						} 
					else 
						{
						$scadenza = new ScadenzaVtt;
						}
						$scadenza->inviata = false;
						$scadenza->scadenza_al = Utility::getCarbonDate($request->get('scadenza_al'));
		        $offerta->scadenza()->save($scadenza);

					}
				else
					{

					if ($offerta->has('scadenza') && !is_null($offerta->scadenza))
						{
						$offerta->scadenza()->delete();
						} 

					}
		}




	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(VetrineBambiniGratisTopRequest $request, $id)
	{

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		// trovo offerta da aggiornare
		$offerta = VetrinaBambiniGratisTop::with('offerte_lingua', 'scadenza')->find($id);

		$mesi = self::preparaMesiPerRequest($request);

		$request->request->add(['mese' => $mesi]);

		//riempio un array con i nuovi dati
		$new_offer = [
		'hotel_id' => $this->getHotelId(),
		'fino_a_anni' => (int)$request->input("fino_a_anni"),
		'anni_compiuti' => $request->input("anni_compiuti"),
		'valido_dal' => Utility::getCarbonDate($request->get('valido_dal')),
		'valido_al' => Utility::getCarbonDate($request->get('valido_al')),
		'mese' => implode(',', $request->get('mese'))
		];



		if ($request->get('salva_e_pubblica') == 1) {
			$new_offer['attivo'] = 1;
		}

		// riempio l'offerta con i nuovi dati e salvo
		$offerta->fill($new_offer);
		$offerta->save();



		$this->_creaScadenza($request, $offerta);
	


		if (is_null($request->get('traduci'))) {
			$nuova_offerta_lingua = [];
			foreach ($offerta->offerte_lingua as $offerta_lingua) {
				$original_offerta_lingua = $offerta_lingua->toArray();


				$key = "note{$offerta_lingua->lang_id}{$id}";
				$note = $request->get($key);

				$this->_scriviTestoVAAT($note);

				$original_offerta_lingua['note'] = $note;

				$key = "pagina_id{$offerta_lingua->lang_id}{$id}";
				$pagina_id = $request->get($key);
				$original_offerta_lingua['pagina_id'] = $pagina_id;

				unset($original_offerta_lingua['id']);
				$nuova_offerta_lingua[] = $original_offerta_lingua;

				$offerta_lingua->delete();
			}

			VetrinaBambiniGratisTopLingua::insert($nuova_offerta_lingua);

			SessionResponseMessages::add("success", "Modifiche salvate con successo.");
		}
		else {
			// RITRADUCO TUTTO COME PER INSERIMENTO!!!
			$key_note = "noteit{$id}";
			$key_pagina = "pagina_idit{$id}";

			$note = $request->get($key_note);
			$pagina_id = $request->get($key_pagina);

			// inserisce i br e rimuove altri caratteri non html
			$this->_leggiTestoVAAT($note);

			// sostituisce ## con lo span no-translate per il testo da NON tradurre
			$this->_processNoTranslateTagVAAT($note);


			/////////////////////////////////////
			// TRADUZIONI CON GOOGLE TRANSLATE //
			/////////////////////////////////////
			$note_it = $note;
			$pagina_id_it = $pagina_id;

			$da_tradurre = array('note');


			$traduzioni = [];
			$traduzioni['it']['note'] = $note_it;
			$traduzioni['it']['pagina_id'] = $pagina_id_it;

			foreach (Utility::linguePossibili() as $lingua) {

				foreach ($da_tradurre as $nome) {
					// da tradurre (sorgente)
					$text = $nome.'_it';

					// la pagina id non si traduce ma si legge dalla request
					// QUELLA ITALIANA L'HO GIA' LETTA!!!
					if ($lingua != 'it') {
						// ES: $titolo_en = ...
						$traduzioni[$lingua][$nome] = Utility::translate($$text, $lingua);

						$note = $traduzioni[$lingua][$nome];
						$traduzioni[$lingua][$nome] = $note;

						$key_pagina = "pagina_id{$lingua}{$id}";
						$pagina_id = $request->get($key_pagina);
						$traduzioni[$lingua]['pagina_id'] = $pagina_id;

					}
					else {
						$note = $traduzioni[$lingua][$nome];
						$traduzioni[$lingua][$nome] = $note;
					}

				}
			}

			////////////////////////////////////////////////////////
			// PRIMA ELIMINO LA PARTE IN LINGUA DI QUESTA OFFERTA //
			////////////////////////////////////////////////////////

			VetrinaBambiniGratisTopLingua::where('master_id', $id)->delete();

			foreach ($traduzioni as $lang_id => $value) {
				$bambino_gratis_Lingua = new VetrinaBambiniGratisTopLingua;
				$bambino_gratis_Lingua->lang_id = $lang_id;
				$bambino_gratis_Lingua->note = $value['note'];
				$bambino_gratis_Lingua->pagina_id = $value['pagina_id'];

				$offerta->offerte_lingua()->save($bambino_gratis_Lingua);
			}

			SessionResponseMessages::add("success", "Modifiche salvate con successo.");

		}

		
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");



		if ($offerta->attivo) 
			{
			if (is_null($request->get('traduci'))) 
				{
				return SessionResponseMessages::redirect("admin/vetrine-bg-top", $request);
				}
			else
				{
				return SessionResponseMessages::redirect("admin/vetrine-bg-top/$id/edit", $request);
				}
			}
		else 
			{
			return SessionResponseMessages::redirect("admin/vetrine-bg-top/archiviati", $request);
			}

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		$hotel_id = $this->getHotelId();
		$macrolocalita_id = Hotel::find($hotel_id)->localita->macrolocalita_id;

		$data = [];

		$anni = self::_getYears();
		
		$mesi = self::_getMesi($anni);
		
		$data['mesi'] = $mesi;
		
		$data['pagine'] = $this->_getPages($macrolocalita_id);

		return view('admin.vetrina_bambini_gratis_top_form', $data);
	}


	/**
	 * Store a newly created resource in storage.
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(VetrineBambiniGratisTopRequest $request)
	{

		/*
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
		 */

		// nuova offerta bambino gratis
		$bambino_gratis = new VetrinaBambiniGratisTop;

		$mesi = self::preparaMesiPerRequest($request);

		/*
		$mesi = array:6 [▼
						  0 => "11-2016"
						  1 => "12-2016"
						  2 => "1-2017"
						  3 => "2-2017"
						  4 => "6-2017"
						  5 => "7-2017"
						]
		 */

		$request->request->add(['mese' => $mesi]);


		$this->_creaOfferta($request, $bambino_gratis);


		$this->_creaScadenza($request, $bambino_gratis);


		$note = $request->get('note');

		// inserisce i br e rimuove altri caratteri non html
		$this->_leggiTestoVAAT($note);


		$pagina_id = $request->get('pagina_id');


		// sostituisce ## con lo span no-translate per il testo da NON tradurre
		$this->_processNoTranslateTagVAAT($note);

		/////////////////////////////////////
		// TRADUZIONI CON GOOGLE TRANSLATE //
		/////////////////////////////////////
		$note_it = $note;

		$da_tradurre = array('note');


		$traduzioni = [];
		$traduzioni['it']['note'] = $note_it;

		foreach (Utility::linguePossibili() as $lingua) {

			foreach ($da_tradurre as $nome) {
				// da tradurre (sorgente)
				$text = $nome.'_it';


				if ($lingua != 'it') {
					// ES: $titolo_en = ...
					$traduzioni[$lingua][$nome] = Utility::translate($$text, $lingua);

					$note = $traduzioni[$lingua][$nome];
					$traduzioni[$lingua][$nome] = $note;

				}
				else {
					$note = $traduzioni[$lingua][$nome];
					$traduzioni[$lingua][$nome] = $note;
				}

			}
		}


		/*
    array:4 [▼
      "it" => array:2 [▼
        "note" => "Grande offerta<br />Benvenuti all'Hotel Sabrina Rimini, offerte per Mirabilandia"
      ]
      "en" => array:2 [▼
        "note" => "Great offer <br />Welcome to &#39; Hotel Sabrina Rimini , offers for Mirabilandia"

      ]
      "fr" => array:2 [▼
        "note" => "Grande offre <br />Bienvenue à Hotel Sabrina Rimini offre pour Mirabilandia"
      ]
      "de" => array:2 [▼
        "note" => "Tolles Angebot <br />Willkommen in Hotel Sabrina Rimini bietet für Mirabilandia"
      ]
    ]
     */

		foreach ($traduzioni as $lang_id => $value) {
			$bambino_gratis_Lingua = new VetrinaBambiniGratisTopLingua;
			$bambino_gratis_Lingua->lang_id = $lang_id;
			$bambino_gratis_Lingua->note = $value['note'];
			// associo la pagina alla lingua ITALIANA
			if ($lang_id == 'it') {
				$bambino_gratis_Lingua->pagina_id = $pagina_id;
			}
			$bambino_gratis->offerte_lingua()->save($bambino_gratis_Lingua);
		}

		


		SessionResponseMessages::add("success", "Inserimento effettuato con successo. RICORDA DI ASSOCIARE LE PAGINE NELLE ALTRE LINGUE !!!!");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
		return SessionResponseMessages::redirect("admin/vetrine-bg-top", $request);

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id)
	{

		VetrinaBambiniGratisTopLingua::where('master_id', $id)->delete();
		VetrinaBambiniGratisTop::destroy($id);
		SessionResponseMessages::add("success", "Offerta Bambini Gratis TOP eliminata con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");

		if ($request->get('attivo')) {
			return SessionResponseMessages::redirect("/admin/vetrine-bg-top", $request);
		}
		else {
			return SessionResponseMessages::redirect("/admin/vetrine-bg-top/archiviati", $request);
		}

	}


	public function archiviate()
	{

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		$offerte = VetrinaBambiniGratisTop::where('hotel_id', $hotel_id)->archiviata()->with(['offerte_lingua.pagina'])->get();
		$mese = date('n');
		$archiviate = 1;

		return view('admin.vetrina_bambini_gratis_top_index', compact('offerte', 'mese', 'archiviate'));

	}


	public function archivia(Request $request, $id)
	{

		VetrinaBambiniGratisTop::find($id)->update(['attivo' => 0]);
		SessionResponseMessages::add("success", "Offerta Bambini Gratis TOP archiviata con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
		return SessionResponseMessages::redirect("/admin/vetrine-bg-top", $request);

	}


	public function ripristina(Request $request, $id)
	{

		VetrinaBambiniGratisTop::find($id)->update(['attivo' => 1]);
		SessionResponseMessages::add("success", "Offerta Bambini Gratis TOP ripristinata con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
		return SessionResponseMessages::redirect("/admin/vetrine-bg-top/archiviati", $request);

	}


	public function elenco_scadenze(Request $request)
	{
	
		$scadenze = ScadenzaVtt::with(['offertaTop.cliente.localita','offertaTop.translate'])->nonInviata()->orderBy('scadenza_al','desc')->get();
		return view('admin.elenco_vtt_index', compact('scadenze'));
		
	}



	public function listPage(Request $request)
		{

		$id_hotel = $this->getHotelId();
		
		$vot = VetrinaBambiniGratisTop::with([
								'offerte_lingua' => function($query)
								{
									$query
									->where('lang_id', '=', 'it');
								},
								])
								->attiva()->nonArchiviata()
								->where('hotel_id', $id_hotel)->get();
		
		$data = [];
		foreach ($vot as $v) 
			{
			$vl = $v->offerte_lingua->first();
			if (!is_null($vl)) {
				$pagina = CmsPagina::find($vl->pagina_id);
			}
			if(isset($pagina))
				{
				$key = "BAMBINI GRATIS FINO A ". strtoupper($v->_fino_a_anni()) . " " . strtoupper($v->_anni_compiuti());
				$data[$key] = url($pagina->uri); 
				}
			}
		$titolo = 'Bambini gratis';
		return view('admin.elenco_evidenze_hotel', compact('data','titolo'));

		}



}
