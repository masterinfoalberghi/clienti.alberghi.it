<?php
	
/**
 * VetrinaOfferteTopController
 *
 * @author Info Alberghi Srl
 * 
 */
 
namespace App\Http\Controllers\Admin;

use App\CmsPagina;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\VetrineOfferteTopRequest;
use App\ScadenzaVot;
use App\Utility;
use App\VetrinaBambiniGratisTopLingua;
use App\VetrinaOffertaTop;
use App\VetrinaOffertaTopLingua;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SessionResponseMessages;

class VetrinaOfferteTopController extends SUPERVetrinaTopController
{
  
  
  
	/* ------------------------------------------------------------------------------------
	 * METODI PRIVATI
	 * ------------------------------------------------------------------------------------ */

	

	/**
	 *  Filtro le pagine da associare per lingua e per la macrolocalità dell'hotel + quelle TRASVERSALI, cioè senza località e senza macro
	 *  OPPURE CON RivieraRomagnola come macro e località
	 * un'offerta TOP di un hotel di RImini NON SARA' associata ad una pagina di Cervia //
	 * 
	 * @access private
	 * @param int $macrolocalita_id (default: 0)
	 * @param string $lang_id (default: 'it')
	 * @return CmsPagina
	 */
	 
	private function _getPages($macrolocalita_id = 0, $lang_id = 'it')
	{

		/**
		 * devo trovare le pagine già associate alle "OFFERTE BAMBINI GRATIS TOP" per ESCLUIDERLE
		 * SE UNA PAGINA HA VAAT NON PUO' AVERE VOT
		 */

		$ids_pagine_bambini_gratis_top =  VetrinaBambiniGratisTopLingua::where('pagina_id', '!=', 0)->distinct()->pluck('pagina_id');

		/**
		 * ECCEZIONE!!!!   
		 *
		 * le pagine hotel_riviera_romagnola/igea-marina/offerte_speciali.php e hotel_riviera_romagnola/igea-marina/last_minute.php NON SAREBBERO SELEZIONABILI
		 * PER AVERE VOT perché sono delle apgine di località E NON MACROLOCALITA. Però qui ci devo andare
		 * le stesse vot che sono nelle analoghe pagine di bellaria. Per dare la possibilità all'operatore di inserirle anche qui
		 * si aggiungono queste pagine FORZANDONE GLI ID NELLA QUERY!!!
		 */

		return CmsPagina::attiva()
			->lingua($lang_id)
			->listingMacroLocalitaOrTrasversali($macrolocalita_id)
			->vetrinaTopEnabled()
			->where('listing_bambini_gratis', 0)
			->whereNotIn('id', $ids_pagine_bambini_gratis_top)
			->orWhere('uri', $lang_id.'/hotel_riviera_romagnola/igea-marina/offerte_speciali.php')
			->orWhere('uri', $lang_id.'/hotel_riviera_romagnola/igea-marina/last_minute.php')
			->orderBy('uri', 'asc')
			->pluck('uri', 'id');

	}

	
	/**
	 * Crea l'oggetto offerta
	 * 
	 * @access private
	 * @param Request $request
	 * @param Offers &$offerta
	 * @return void
	 */
	 
	private function _creaOfferta($request, &$offerta)
	{

		$offerta->attivo = 1;
		$offerta->hotel_id = $this->getHotelId();
		$hotel_id = $offerta->hotel_id;
		Utility::clearCacheHotel($hotel_id);

		$offerta->per_giorni = $request->get('per_giorni');
		$offerta->prezzo_a_persona = $request->get('prezzo_a_persona');
		$offerta->prezzo_a_partire_da = $request->get('prezzo_a_partire_da');
		$offerta->label_costo_a_persona = $request->get('label_costo_a_persona');
		$offerta->per_persone = $request->get('per_persone');
		$offerta->formula = $request->get('formula');
		$offerta->nascondi_in_scheda = $request->filled('nascondi_in_scheda') ? $request->get('nascondi_in_scheda') : 0;

		if ($request->filled('prenota_entro')){
			$offerta->prenota_entro = Utility::getCarbonDate($request->get('prenota_entro'));
			}

		if ($request->filled('valido_dal'))
			$offerta->valido_dal = Utility::getCarbonDate($request->get('valido_dal'));

		if ($request->filled('valido_al'))
			$offerta->valido_al = Utility::getCarbonDate($request->get('valido_al'));

		$offerta->perc_sconto = $request->get('perc_sconto');
		$offerta->tipo = $request->get('tipo');
		$offerta->mese = implode(',', $request->get('mese'));

		$offerta->save();

	}


	
	
  
	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( VIEWS )
	 * ------------------------------------------------------------------------------------ */


	
	/**
	 * Lista offerte
	 *
	 * @param string $message
	 * @return View
	 */

	public function index($message = "")
	{

		$hotel_id = $this->getHotelId();
		$offerte = VetrinaOffertaTop::where('hotel_id', $hotel_id)
			->nonArchiviata()
			->with(['offerte_lingua.pagina'])
			->get();

		$mese = date('n').'-'.date('Y');
		return view('admin.vetrina_offerte_top_index', compact('offerte', 'mese', 'message'));
		
	}


	/**
	 * View per la creazioene dell'offerta
	 *
	 * @return View
	 */
	 
	public function create()
	{

		$hotel_id = $this->getHotelId();
		$hotel = Hotel::find($hotel_id);
		$macrolocalita_id = $hotel->localita->macrolocalita_id;
		$data = [];
		$data['formule'] = self::$formule;
		$anni = self::_getYears();
		$mesi = self::_getMesi($anni);
		$data['mesi'] = $mesi;
		$data['tipi'] = ['offerta' => 'offerta', 'lastminute' => 'lastminute', 'prenotaprima' => 'prenotaprima'];
		$data['pagine'] = $this->_getPages($macrolocalita_id);
		$data['macrolocalita_id'] = $macrolocalita_id;
		$data['hotel'] = $hotel;
		$this->_pass_limitations_to_form($data);
		return view('admin.vetrina_offerta_top_form', $data);

	}
	/**
	 * Pagina per la modifica delle offerte
	 *
	 * @param  int  $id
	 * @return View
	 */
	 
	public function edit($id)
	{
		$data = [];

		/**
		 * se ho perso la sessione dell'hotel NON devo poter modificare un'offerta che è la sua
		 */

		$hotel_id = $this->getHotelId();
		$hotel = Hotel::find($hotel_id);
		$macrolocalita_id = $hotel->localita->macrolocalita_id;
		
		$offerta = VetrinaOffertaTop::with(['offerte_lingua','scadenza'])->where('hotel_id', $hotel_id)->where('id', $id)->get()->first();
		$data['tipi'] = ['offerta' => 'offerta', 'lastminute' => 'lastminute', 'prenotaprima' => 'prenotaprima'];
		$offertaLingua = [];
		$pagine = [];

		

		foreach ($offerta->offerte_lingua as $offerta_lingua) {

			$arr = [];
			$titolo = $offerta_lingua->titolo;
			$testo = $offerta_lingua->testo;
			$this->_preparaPerWeb($titolo);
			$arr['titolo'] = $titolo;
			$this->_preparaPerWeb($testo);
			$arr['testo'] = $testo;
			$arr['pagina_id'] = $offerta_lingua->pagina_id;
			$offertaLingua[$offerta_lingua->lang_id][] = $arr;
			$pagine[$offerta_lingua->lang_id] = $this->_getPages($macrolocalita_id, $offerta_lingua->lang_id);

		}
		
		$data['formule'] = self::$formule;
		$data['offerta'] = $offerta;
		$data['offertaLingua'] = $offertaLingua;
		$data['pagine'] = $pagine;
		$data['showButtons'] = 1;
		$data['macrolocalita_id'] = $macrolocalita_id;
		$data['hotel'] = $hotel;

		$this->_pass_limitations_to_form($data, $offerta);
		$anni = self::_getYears();
		$mesi = self::_getMesi($anni);
		$data['mesi'] = $mesi;
		
		return view('admin.vetrina_offerta_top_form', $data);

	}

	/**
	 * Pagina per le offerte archiviate
	 * 
	 * @access public
	 * @return View
	 */
	 
	public function archiviate()
	{
		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		$offerte = VetrinaOffertaTop::where('hotel_id', $hotel_id)->archiviata()->with(['offerte_lingua.pagina'])->get();
		$mese = date('n');
		$archiviate = 1;
		return view('admin.vetrina_offerte_top_index', compact('offerte', 'mese', 'archiviate'));
	}


	
	
  
	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( CONTROLLERS )
	 * ------------------------------------------------------------------------------------ */



	/**
	 * Crea / modifica l'offerta
	 *
	 * @param  VetrineOfferteTopRequest  $request
	 * @return View
	 */
	 
	public function store(VetrineOfferteTopRequest $request)
	{

		// nuova Offerta
		$offerta = new VetrinaOffertaTop;
		$mesi = self::preparaMesiPerRequest($request);
		$request->request->add(['mese' => $mesi]);

		$this->_creaOfferta($request, $offerta);


		$this->_creaScadenza($request, $offerta);
		

		$titolo = $request->get('titolo');
		$testo = $request->get('testo');
		$pagina_id = $request->get('pagina_id');
		
		$this->_leggiTesto($titolo, $testo); // inserisce i br e rimuove altri caratteri non html
		$this->_processNoTranslateTag($titolo, $testo);// sostituisce ## con lo span no-translate per il testo da NON tradurre

		/**
		 * Traduzioni con google translate
		 */
		 
		$titolo_it = $titolo;
		$testo_it = $testo;

		$da_tradurre = array('testo', 'titolo');

		$traduzioni = [];
		$traduzioni['it']['titolo'] = $titolo_it;
		$traduzioni['it']['testo'] = $testo_it;

		/**
		 * Creo le traduzioni 
		 */


		foreach (Utility::linguePossibili() as $lingua) {

			foreach ($da_tradurre as $nome) {

				$text = $nome.'_it';

				if ($lingua != 'it') {

					$traduzioni[$lingua][$nome] = Utility::translate($$text, $lingua);

					$testo = $traduzioni[$lingua][$nome];
					$this->_scriviTesto($testo);
					$traduzioni[$lingua][$nome] = $testo;

				} else {
					
					$testo = $traduzioni[$lingua][$nome];
					$this->_scriviTesto($testo);
					$traduzioni[$lingua][$nome] = $testo;
					
				}

			}
		}

		foreach ($traduzioni as $lang_id => $value) {
			
			$offertaLingua = new VetrinaOffertaTopLingua;
			$offertaLingua->lang_id = $lang_id;
			$offertaLingua->titolo = $value['titolo'];
			$offertaLingua->testo = $value['testo'];
			// associo la pagina alla lingua ITALIANA
			if ($lang_id == 'it') {
				$offertaLingua->pagina_id = $pagina_id;
			}
			$offerta->offerte_lingua()->save($offertaLingua);
			
		}

		

		SessionResponseMessages::add("success", "Inserimento effettuato con successo. RICORDA DI ASSOCIARE LE PAGINE NELLE ALTRE LINGUE !!!!");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
		return SessionResponseMessages::redirect("admin/vetrine-offerte-top", $request);

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
						$scadenza = new ScadenzaVot;
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
	 * Aggiorna l'offerta selezionata
	 *
	 * @param  VetrineOfferteTopRequest  $request
	 * @param  int  $id
	 * @return SessionResponseMessages
	 */
	public function update(VetrineOfferteTopRequest $request, $id)
	{

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);
		$offerta = VetrinaOffertaTop::with(['offerte_lingua', 'scadenza'])->find($id);
		$mesi = self::preparaMesiPerRequest($request);
		$request->request->add(['mese' => $mesi]);

		/**
		 * riempio un array con i nuovi dati
		 */
		 
		$new_offer = [
			'hotel_id' => $this->getHotelId(),
			'per_giorni' => $request->get('per_giorni'),
			'per_persone' => $request->get('per_persone'),
			'prezzo_a_persona' => $request->get('prezzo_a_persona'),
			'prezzo_a_partire_da' => $request->get('prezzo_a_partire_da'),
			'label_costo_a_persona' => $request->get('label_costo_a_persona'),
			'formula' => $request->get('formula'),
			'mese' => implode(',', $request->get('mese')),
			'perc_sconto' => $request->get('perc_sconto'),
			'nascondi_in_scheda' => $request->get('nascondi_in_scheda'),
			'tipo' => $request->get('tipo')
			
		];
	
		$new_offer['prenota_entro'] = $request->filled('prenota_entro') && Utility::isValidYear(substr($request->get('prenota_entro'), 6)) ? Utility::getCarbonDate($request->get('prenota_entro')) : null;
		$new_offer['valido_dal'] = Utility::getCarbonDate($request->get('valido_dal'));
		$new_offer['valido_al'] = Utility::getCarbonDate($request->get('valido_al'));

		if ($request->get('salva_e_pubblica') == 1)
			$new_offer['attivo'] = 1;

		/**
		 * riempio l'offerta con i nuovi dati e salvo
		 */
		 
		$offerta->fill($new_offer);
		$offerta->save();


		$this->_creaScadenza($request, $offerta);
		

		if (is_null($request->get('traduci'))) {

			$nuova_offerta_lingua = [];
			foreach ($offerta->offerte_lingua as $offerta_lingua) {

				$original_offerta_lingua = $offerta_lingua->toArray();

				$key = "titolo{$offerta_lingua->lang_id}{$id}";

				if ($request->filled($key)) {
					$titolo = $request->get($key);
					$this->_scriviTesto($titolo);
					$original_offerta_lingua['titolo'] = $titolo;
				}

				$key = "testo{$offerta_lingua->lang_id}{$id}";

				if ($request->filled($key)) {
					$testo = $request->get($key);
					$this->_scriviTesto($testo);
					$original_offerta_lingua['testo'] = $testo;
				}


				$key = "pagina_id{$offerta_lingua->lang_id}{$id}";

				if ($request->filled($key)) {
					$pagina_id = $request->get($key);
					$original_offerta_lingua['pagina_id'] = $pagina_id;
				}


				unset($original_offerta_lingua['id']);
				$nuova_offerta_lingua[] = $original_offerta_lingua;

				$offerta_lingua->delete();
			}

			VetrinaOffertaTopLingua::insert($nuova_offerta_lingua);
			SessionResponseMessages::add("success", "Modifiche salvate con successo.");

		}
		else {

			/**
			 * RITRADUCO TUTTO COME PER INSERIMENTO!!!
			 */
			 
			$key_titolo = "titoloit{$id}";
			$key_testo = "testoit{$id}";
			$key_pagina = "pagina_idit{$id}";

			if ($request->filled($key_titolo) && $request->filled($key_testo)) {

				$titolo = $request->get($key_titolo);
				$testo = $request->get($key_testo);
				$pagina_id = $request->get($key_pagina);

				/**
				 * inserisce i br e rimuove altri caratteri non html
				 */
				 
				$this->_leggiTesto($titolo, $testo);

				/**
				 * sostituisce ## con lo span no-translate per il testo da NON tradurre
				 */
				 
				$this->_processNoTranslateTag($titolo, $testo);

				/**
				 * TRADUZIONI CON GOOGLE TRANSLATE
				 */
				 
				$titolo_it = $titolo;
				$testo_it = $testo;
				$pagina_id_it = $pagina_id;

				$da_tradurre = array('testo', 'titolo');

				$traduzioni = [];
				$traduzioni['it']['titolo'] = $titolo_it;
				$traduzioni['it']['testo'] = $testo_it;
				$traduzioni['it']['pagina_id'] = $pagina_id_it;

				foreach (Utility::linguePossibili() as $lingua) {

					foreach ($da_tradurre as $nome) {

						
						$text = $nome.'_it'; // da tradurre (sorgente)

						/**
						 * la pagina id non si traduce ma si legge dalla request
						 * QUELLA ITALIANA L'HO GIA' LETTA!!!
						 */

						if ($lingua != 'it') {

							$traduzioni[$lingua][$nome] = Utility::translate($$text, $lingua);

							$testo = $traduzioni[$lingua][$nome];
							$this->_scriviTesto($testo);
							$traduzioni[$lingua][$nome] = $testo;


							$key_pagina = "pagina_id{$lingua}{$id}";
							$pagina_id = $request->get($key_pagina);
							$traduzioni[$lingua]['pagina_id'] = $pagina_id;

						} else {
							
							$testo = $traduzioni[$lingua][$nome];
							$this->_scriviTesto($testo);
							$traduzioni[$lingua][$nome] = $testo;
							
						}

					}
				}

				/**
				 * PRIMA ELIMINO LA PARTE IN LINGUA DI QUESTA OFFERTA
				 */

				VetrinaOffertaTopLingua::where('master_id', $id)->delete();

				foreach ($traduzioni as $lang_id => $value) {
					
					$offertaLingua = new VetrinaOffertaTopLingua;
					$offertaLingua->lang_id = $lang_id;
					$offertaLingua->titolo = $value['titolo'];
					$offertaLingua->testo = $value['testo'];
					$offertaLingua->pagina_id = $value['pagina_id'];
					$offerta->offerte_lingua()->save($offertaLingua);
					
				}

				SessionResponseMessages::add("success", "Modifiche salvate con successo.");

			} // end if esiste italiano
			else {
				return redirect('admin/vetrine-offerte-top/'.$id.'/edit')
				->withInput();
			}

		}

		
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");

		if ($offerta->attivo) {
			if (is_null($request->get('traduci'))) {
				return SessionResponseMessages::redirect("admin/vetrine-offerte-top", $request);
			}
			else {
				return SessionResponseMessages::redirect("admin/vetrine-offerte-top/$id/edit", $request);
			}
		}
		else {
			return SessionResponseMessages::redirect("admin/vetrine-offerte-top/archiviati", $request);
		}

	}


	/**
	 * Rimuovo l'offerta
	 *
	 * @param  Request $request
	 * @param  int  $id
	 * @return SessionResponseMessages
	 */
	 
	public function destroy(Request $request, $id)
	{

		VetrinaOffertaTopLingua::where('master_id', $id)->delete();
		VetrinaOffertaTop::destroy($id);
		SessionResponseMessages::add("success", "Offerta TOP eliminata con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");

		if ($request->get('attivo'))
			return SessionResponseMessages::redirect("/admin/vetrine-offerte-top", $request);
		else
			return SessionResponseMessages::redirect("/admin/vetrine-offerte-top/archiviati", $request);

	}



	/**
	 * Clona l'offerta
	 * 
	 * @access public
	 * @param Request $request
	 * @param int $id
	 * @return SessionResponseMessages
	 */

	public function clona(Request $request, $id)
	{
		$translate = [
			'it' => 'COPIA',
			'en' => 'COPY',
			'fr' => 'COPIE',
			'de' => 'KOPIE'
		];

		$cloned = VetrinaOffertaTop::with(['offerte_lingua'])->find($id)->replicate();
		
		$cloned->save();

		foreach ($cloned->offerte_lingua as $offerta_lingua) {
			$cloned_lingua = $offerta_lingua->replicate();
			$cloned_lingua->pagina_id = 0;
			$cloned_lingua->master_id = $cloned->id;
			$cloned_lingua->titolo .= " " .  $translate[$cloned_lingua->lang_id];
			$cloned_lingua->save();
		}
		
		SessionResponseMessages::add("success", "Offerta TOP clonata con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
		return SessionResponseMessages::redirect("/admin/vetrine-offerte-top", $request);
	}


	/**
	 * Archivia l'offerta e manda l'email.
	 * 
	 * @access public
	 * @param Request $request
	 * @param int $id
	 * @return SessionResponseMessages
	 */
	 
	public function archivia(Request $request, $id)
	{
		
		VetrinaOffertaTop::find($id)->update(['attivo' => 0]);	
		SessionResponseMessages::add("success", "Offerta TOP archiviata con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
		return SessionResponseMessages::redirect("/admin/vetrine-offerte-top", $request);

	}

	/**
	 * Ripristina un'offerta
	 * 
	 * @access public
	 * @param Request $request
	 * @param int $id
	 * @return SessionResponseMessages
	 */
	
	public function ripristina(Request $request, $id)
	{
		
		VetrinaOffertaTop::find($id)->update(['attivo' => 1]);
		SessionResponseMessages::add("success", "Offerta TOP ripristinata con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
		return SessionResponseMessages::redirect("/admin/vetrine-offerte-top/archiviati", $request);

	}



	public function elenco_scadenze(Request $request)
	{

		$scadenze = ScadenzaVot::with(['offertaTop.cliente.localita','offertaTop.translate'])->nonInviata()->orderBy('scadenza_al','desc')->get();
		return view('admin.elenco_vot_index', compact('scadenze'));
		
	}





	public function listPage(Request $request)
		{

		$id_hotel = $this->getHotelId();
			
		$vot = VetrinaOffertaTop::with([
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
			if (!is_null($vl)) 
				{
				$data[$vl->titolo] = url(CmsPagina::find($vl->pagina_id)->uri); 
				}
			}

		$titolo = 'Offerte';
		return view('admin.elenco_evidenze_hotel', compact('data','titolo'));

		}



}
