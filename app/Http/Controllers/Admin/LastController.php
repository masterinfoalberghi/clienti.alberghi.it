<?php
namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\LastRequest;
use App\Motivazione;
use App\Offerta;
use App\OffertaLingua;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use SessionResponseMessages;

class LastController extends AdminBaseController
{

	private static $formule = ['sd' => 'Solo dormire', 'bb' => 'Bed & Breakfast', 'fb' => 'Pensione completa', 'hb' => 'Mezza pensione', 'ai' => 'All inclusive', 'tt' => 'Tutti'];



	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// QUESTE COSTANTI DEFINISCON IL LIMITE DEL TESTO SIA PER I CONTROLLI LATO CLIENT JS, SIA PER IL VALIDATOR DI LARAVEL //
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// minima data selezionabile come inizio validità
	const MIN_DAL = 0;

	// ESSENDO UN LAST MINUTE, la data di inizio NON PUO' ESSERE DOPO 20 gg a partire da oggi
	// "A Natale non posso fare un last minute che parte ad Agosto !!!"
	const MAX_DAL_NUMBER = 20;

	// massima data selezionabile come fine validità (1 mesi) - DEPRECATED
	// massima data selezionabile come fine validità (45 giorni)
	const MAX_AL_NUMBER = 45;  


	const LIMIT_TITOLO = 70;
	const LIMIT_TESTO = 500;

	const LIMIT_TITOLO_it = 70;
	const LIMIT_TESTO_it = 500;

	const LIMIT_TITOLO_en = 90;
	const LIMIT_TESTO_en = 520;

	const LIMIT_TITOLO_fr = 100;
	const LIMIT_TESTO_fr = 530;

	const LIMIT_TITOLO_de = 120;
	const LIMIT_TESTO_de = 550;



	private function sendMailModificaPerApprovazione($hotel,$titolo_offerta, $traduzione = 0,$perArchiviazione = 0, $note = null)
	{

	    Utility::swapToSendGrid();

	    $hotel_id = $hotel->id; 
		$nome_cliente = $hotel->nome;

	    $from = "assistenza@info-alberghi.com";
	    $nome = "Lo staff di Info Alberghi";

	    $oggetto = $perArchiviazione ? "Archiviazione Offerta Last Minute" : "Moderazione Offerta Last Minute";
		$tipo ="Last minute";
		$ancora = "&lastminute";

		$email_cliente = explode(',', $hotel->email);
		if ($hotel->email_secondaria != "")
			$email_cliente = explode(',', $hotel->email_secondaria);
		
		try {

			$mail_to_send = $perArchiviazione ? 'emails.archiviazione_offerta' : 'emails.moderazione_offerta';

		  	Mail::send($mail_to_send, 

		  		compact(

		  			'titolo_offerta',
		  			'nome_cliente', 
		  			'hotel_id',
		  			'tipo',
		  			'ancora',
		  			'traduzione',
		  			'oggetto',
		  			'note'

		  		), function ($message) use ($from, $oggetto, $nome, $email_cliente) {

					$message->from($from, $nome);
					$message->replyTo($from);
					$message->to($email_cliente);
					$message->subject($oggetto);
				
				});
	
		} catch (\Exception $e) {
			echo "Error " . $e->getMessage();
		}

		// SECONDA MAIL nicole@ per storico

		try 
		{
			$oggetto .= ' - ' . $nome_cliente;

			$to = 'offerte@info-alberghi.com';

			Mail::send($mail_to_send, 

			compact(

				'titolo_offerta',
				'nome_cliente', 
				'hotel_id',
				'tipo',
				'ancora',
				'traduzione',
				'oggetto',
				'note'

			), function ($message) use ($from, $oggetto, $nome, $to){

				$message->from($from, $nome);
				$message->replyTo($from);
				$message->to($to);
				$message->subject($oggetto);

			});
		} 
	catch (\Exception $e) 
		{
			echo "Error " . $e->getMessage();
		}

		
	}

	
	



	private function _pass_limitations_to_form(&$data, $offerta = null)
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



		$data["MIN_DAL"] = self::MIN_DAL;
		$data["MAX_AL"] = "+". self::MAX_AL_NUMBER . "D";
		$data["MAX_DAL"] = "+". self::MAX_DAL_NUMBER . "D";

	}



	// metodo richiamato solo nello store
	// perché serve PRIMA di PASSARE il teso AL TRADUTTORE GT
	private function _leggiTesto(&$titolo, &$testo)
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
	private function _scriviTesto(&$testo)
	{
		$testo = strip_tags($testo, "<br>"); // tolgo tutto html
		$testo = nl2br($testo); // sostituisco il br html per andare a capo sul web
		$testo = str_replace("\n", '', $testo); // remove new lines
		$testo = str_replace("\r", '', $testo); // remove carriage returns
		$testo = str_replace("\t", '', $testo); // remove carriage returns

		$testo = str_replace("<br /> ", '<br />', $testo); // remove spaces after <br>

		/* DOVE SONO PIU' DI 2 VOGLIO AL MASSIMO 1 br (a capo + 1 riga vuota)*/
		$pattern = '/(<br \/>){2,1000}/i';
		$replacement = '<br /><br />';

		$new_testo = preg_replace($pattern, $replacement, $testo);

		if (!is_null($new_testo)) {
			$testo = $new_testo;
		}

	}


	// metodo da chiamare SEMPRE quando si legge dal DB
	// ripristina i caratteri per visualizzazione corretta in text editor
	private function _preparaPerWeb(&$testo)
	{

		$testo = preg_replace('#<br\s*/?>#i', "\n", $testo); // rimetto gli a capo NON web

		//$testo = str_replace("&euro;",chr(128),$testo); // scrivo Euro con la corrispondente entità html This problem mostly has to do with different encodes of text. It’s very hard to covert an euro sign with a hard “€” within your replace code - See more at: http://www.mauserrifle.nl/php/php-and-replacing-euro-signs/#sthash.J3mYZdBQ.dpuf
	}


	// metodo richiamato solo nello store
	// sostituisce ## con lo span no-translate per il testo da NON tradurre
	private function _processNoTranslateTag(&$titolo, &$testo)
	{
		$content_processed = preg_replace_callback(
			'|#(.+?)#|s',
		function($matches) {
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



	private function _creaOffertaLast($request, &$offerta)
	{

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);
		$offerta->tipologia = 'lastminute';
		$offerta->attivo = 1;
		$offerta->hotel_id = $hotel_id;
		$offerta->per_giorni = $request->get('per_giorni');
		$offerta->prezzo_a_persona = $request->get('prezzo_a_persona');
		$offerta->prezzo_a_partire_da = $request->get('prezzo_a_partire_da');
		$offerta->label_costo_a_persona = $request->get('label_costo_a_persona');
		$offerta->per_persone = $request->get('per_persone');
		$offerta->per_giorni = $request->get('per_giorni');
		$offerta->formula = $request->get('formula');


		$offerta->valido_dal = Utility::getCarbonDate($request->get('valido_dal'));
		$offerta->valido_al = Utility::getCarbonDate($request->get('valido_al'));

		if ($request->get('label_costo_a_persona') == "1")
			$offerta->prezzo_totale = $offerta->prezzo_a_persona * $offerta->per_persone * $offerta->per_giorni;
		else
			$offerta->prezzo_totale = $offerta->prezzo_a_persona;
			
		

		$offerta->save();

	}


	private function _archiviaScadute($hotel_id = 0)
	{
		/*
      prendo quelle scadute e le archivio
      */

		$offerte_scadute_ids = Offerta::where('tipologia', '=', 'lastminute')->where('hotel_id', $hotel_id)->attivaScaduta()->pluck('id')->toArray();

		if (count($offerte_scadute_ids)) {
			Offerta::whereIn('id', $offerte_scadute_ids)->update(['attivo' => 0]);
			SessionResponseMessages::add("error", "ATTENZIONE!! Alcune offerte che ERANO SCADUTE sono state AUTOMATICAMENTE ARCHIVIATE dal sistema.");
			Session::put("SessionResponseMessages", SessionResponseMessages::$msgs);
		}
	}

	/**
	 * [_eliminaOlderThan elimino automaticamente le offerte più vecchie di $years anni]
	 * @param  integer $hotel_id [description]
	 * @return [type]            [description]
	 */
	private function _eliminaOlderThan($hotel_id = 0)
		{
		
		$years = 3;
		
		$offerte_to_del = Offerta::where('tipologia', '=', 'lastminute')->where('hotel_id', $hotel_id)->archiviata()->olderThan($years)->get();
		
		$to_remove = $offerte_to_del->count();
		if ($to_remove) 
			{
			foreach ($offerte_to_del as $offerta) 
				{
				OffertaLingua::where('master_id', $offerta->id)->delete();
				$offerta->delete();
				}
			SessionResponseMessages::add("error", "ATTENZIONE!! Il sistema ha AUTOMATICAMENTE RIMOSSO $to_remove promozioni più vecchie di $years anni.");
			Session::put("SessionResponseMessages", SessionResponseMessages::$msgs);
			}	

		}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($message = "")
	{
		$hotel_id = $this->getHotelId();
		
		self::_archiviaScadute($hotel_id);

		$offerte = Offerta::where('tipologia', '=', 'lastminute')->where('hotel_id', $hotel_id)->nonArchiviata()->with(['offerte_lingua'])->get();
		$oggi = Carbon::today();
		return view('admin.last_index', compact('offerte', 'oggi', 'message'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{

		$hotel_id = $this->getHotelId();
		$hotel = Hotel::find($hotel_id);
		if ($hotel->superatoLimiteLast()) {
			return $this->index($message = Hotel::LIMIT_LAST);
		}

		$data = [];

		$data['formule'] = self::$formule;
		$data['hotel'] = $hotel;

		$this->_pass_limitations_to_form($data);

		return view('admin.last_form', $data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(LastRequest $request)
	{
		// nuova Offerta
		$offerta = new Offerta;

		$this->_creaOffertaLast($request, $offerta);



		$titolo = $request->get('titolo');
		$testo = $request->get('testo');


		// inserisce i br e rimuove altri caratteri non html
		$this->_leggiTesto($titolo, $testo);

		// sostituisce ## con lo span no-translate per il testo da NON tradurre
		$this->_processNoTranslateTag($titolo, $testo);


		/////////////////////////////////////
		// TRADUZIONI CON GOOGLE TRANSLATE //
		/////////////////////////////////////
		$titolo_it = $titolo;
		$testo_it = $testo;

		$da_tradurre = array('testo', 'titolo');


		$traduzioni = [];
		$traduzioni['it']['titolo'] = $titolo_it;
		$traduzioni['it']['testo'] = $testo_it;

		foreach (Utility::linguePossibili() as $lingua) {

			foreach ($da_tradurre as $nome) {
				// da tradurre (sorgente)
				$text = $nome.'_it';


				if ($lingua != 'it') {
					// ES: $titolo_en = ...
					$traduzioni[$lingua][$nome] = Utility::translate($$text, $lingua);

					$testo = $traduzioni[$lingua][$nome];
					$this->_scriviTesto($testo);
					$traduzioni[$lingua][$nome] = $testo;

				}
				else {
					$testo = $traduzioni[$lingua][$nome];
					$this->_scriviTesto($testo);
					$traduzioni[$lingua][$nome] = $testo;
				}

			}
		}

		foreach ($traduzioni as $lang_id => $value) {
			$offertaLingua = new OffertaLingua;
			$offertaLingua->lang_id = $lang_id;
			$offertaLingua->titolo = $value['titolo'];
			$offertaLingua->testo = $value['testo'];
			if (Auth::user()->hasRole(["root","admin", "operatore"]))
				{	
				$offertaLingua->approvata = 1;
				$offertaLingua->data_approvazione = Carbon::now();
				}
			$offerta->offerte_lingua()->save($offertaLingua);
		}
		
		
		
		SessionResponseMessages::add("success", "Inserimento effettuato con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo.");
		return SessionResponseMessages::redirect("admin/last", $request);

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{

		//

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$data = [];

		// se ho perso la sessione dell'hotel NON devo poter modificare un'offerta che è la sua
		//$offerta = Offerta::with(['offerte_lingua'])->find($id);

		$hotel_id = $this->getHotelId();
		$hotel = Hotel::find($hotel_id);
		$offerta = Offerta::with(['offerte_lingua'])->where('hotel_id', $hotel_id)->where('id', $id)->get()->first();
		$data['motivazioni_associate_ids'] = [];

		$offertaLingua = [];
		
		foreach ($offerta->offerte_lingua as $offerta_lingua) {
			$arr = [];
			$titolo = $offerta_lingua->titolo;
			$testo = $offerta_lingua->testo;

			$this->_preparaPerWeb($titolo);
			$arr['titolo'] = $titolo;

			$this->_preparaPerWeb($testo);
			$arr['testo'] = $testo;

			$offertaLingua[$offerta_lingua->lang_id][] = $arr;
		}
		$data['motivazioni_associate_ids'] = $offerta->motivazioni()->pluck('id')->toArray();


		$data['formule'] = self::$formule;
		$data['offerta'] = $offerta;
		$data['offertaLingua'] = $offertaLingua;
		$data['showButtons'] = 1;
		$data['hotel'] = $hotel;

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// ATTENZIONE se sono un admin o un operatore VOGLIO IL PULSANTE ARCHIVIA DENTRO IL FORM DI MODIFICA DELL'OFFERTA //
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$data['showArchivia'] = Auth::user()->hasRole(["root","admin", "operatore"]); 
		$data['showSaveWithoutEmail'] = Auth::user()->hasRole(["root","admin", "operatore"]); 

		$data['hotel_id'] = $hotel_id;

		$this->_pass_limitations_to_form($data, $offerta);

		$data['motivazioni'] = Motivazione::pluck('motivazione','id');


		return view('admin.last_form', $data);

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(LastRequest $request, $id)
	{

		if($request->has('archiviazione') && $request->get('archiviazione') == 1)
			{
			$this->archivia($request, $id);
			}

		$cambio_tipologia = false;

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		// trovo offerta da aggiornare
		$offerta = Offerta::with('offerte_lingua')->find($id);

		if($offerta->hotel_id != $hotel_id)
			{
			$tipo = 'lastminute';
			// CONTROLLO CHE l'OFFERTA CHE STO MODIFICANDO APPARTENGA ALL'HOTEL IN SESSIONE
			Utility::NotifyOfferta($request, $offerta, $hotel_id, $tipo);

			SessionResponseMessages::add("error", "ATTENZIONE: ".$tipo." non appartiene all'hotel IMPERSONIFICATO !!!");
			return SessionResponseMessages::redirect("/admin", $request);

			}

		//riempio un array con i nuovi dati
		$new_offer = [
		'hotel_id' => $this->getHotelId(),
		'per_giorni' => $request->get('per_giorni'),
		'prezzo_a_persona' => $request->get('prezzo_a_persona'),
		'prezzo_a_partire_da' => $request->get('prezzo_a_partire_da'),
		'label_costo_a_persona' => $request->get('label_costo_a_persona'),
		'per_persone' => $request->get('per_persone'),
		'per_giorni' => $request->get('per_giorni'),
		'formula'=> $request->get('formula'),
		'note'=> $request->get('note'),
		'valido_dal' => Utility::getCarbonDate($request->get('valido_dal')),
		'valido_al' => Utility::getCarbonDate($request->get('valido_al'))
		];

		if ($request->get('salva_e_pubblica') == 1 && !Hotel::find($hotel_id)->superatoLimiteLast()) {
			$new_offer['attivo'] = 1;
		}
		
		if ($request->get('label_costo_a_persona') == "1")
			$new_offer['prezzo_totale'] = $offerta->prezzo_a_persona * $offerta->per_persone * $offerta->per_giorni;
		else
			$new_offer['prezzo_totale'] = $offerta->prezzo_a_persona;
		


		/////////////////////////
		// cambio di tipologia //
		/////////////////////////
		
		if ($request->has('tipologia') && $request->get('tipologia') != $offerta->tipologia) 
			{
			$new_offer['tipologia'] = $request->get('tipologia');
			$cambio_tipologia = true;
			}

		// riempio l'offerta con i nuovi dati e salvo
		$offerta->fill($new_offer);

		$offerta->save();

		$motivazioni_str = "";
		if($request->has('motivazioni'))
			{
			$offerta->motivazioni()->sync($request->get('motivazioni'));
			$motivazioni_str = $offerta->getMotivazioni();
			}


		($request->filled('traduci') && $request->get('traduci') == 1) ? $traduzione = 1 : $traduzione = 0;

		//////////////////////////////////////////////////////////////////////////////////////////
		// ATTENZIONE se sono un admin o un operatore l'offerta salvata/creata è ANCHE VALIDATA //
		//////////////////////////////////////////////////////////////////////////////////////////
		$da_approvare = Auth::user()->hasRole(["root","admin", "operatore"]); 

		if ($traduzione) 
			{
			
			// prendo titolo e testo in italiano
			$titolo = $request->get('titoloit'.$id);
			$testo = $request->get('testoit'.$id);

			// inserisce i br e rimuove altri caratteri non html
			$this->_leggiTesto($titolo, $testo);

			// sostituisce ## con lo span no-translate per il testo da NON tradurre
			$this->_processNoTranslateTag($titolo, $testo);


			/////////////////////////////////////
			// TRADUZIONI CON GOOGLE TRANSLATE //
			/////////////////////////////////////
			$titolo_it = $titolo;
			$testo_it = $testo;

			$da_tradurre = array('testo', 'titolo');


			$traduzioni = [];
			$traduzioni['it']['titolo'] = $titolo_it;
			$traduzioni['it']['testo'] = $testo_it;

			// loop sulle lingue e traduzione
			foreach (Utility::linguePossibili() as $lingua) 
				{

				foreach ($da_tradurre as $nome) 
					{
					// da tradurre (sorgente)
					$text = $nome.'_it';


					if ($lingua != 'it') 
						{
						// ES: $titolo_en = ...
						$traduzioni[$lingua][$nome] = Utility::translate($$text, $lingua);

						$testo = $traduzioni[$lingua][$nome];
						$this->_scriviTesto($testo);
						$traduzioni[$lingua][$nome] = $testo;

						}
					else 
						{
						$testo = $traduzioni[$lingua][$nome];
						$this->_scriviTesto($testo);
						$traduzioni[$lingua][$nome] = $testo;
						}

					}
				}

			// finito le traduzioni creo l'offertaLingua
			foreach ($traduzioni as $lang_id => $value) 
				{
				$offertaLingua = new OffertaLingua;
				$offertaLingua->lang_id = $lang_id;
				$offertaLingua->titolo = $value['titolo'];
				$offertaLingua->testo = $value['testo'];

				if ($da_approvare)
					{	
					$offertaLingua->approvata = 1;
					$offertaLingua->data_approvazione = Carbon::now();
					}
				else
					{
					$offertaLingua->approvata = 0;
					}
				$offerta->translate($lang_id)->first()->delete();
				$offerta->offerte_lingua()->save($offertaLingua);
				}

			}
		else 
			{

			$nuova_offerta_lingua = [];
			
			foreach ($offerta->offerte_lingua as $offerta_lingua) {

				if ($da_approvare)
					{	
					$offerta_lingua->approvata = 1;
					$offerta_lingua->data_approvazione = Carbon::now();
					}
				else
					{
					$offerta_lingua->approvata = 0;
					}
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

				unset($original_offerta_lingua['id']);
				$nuova_offerta_lingua[] = $original_offerta_lingua;

				$offerta_lingua->delete();
			}

			OffertaLingua::insert($nuova_offerta_lingua);

			} // end senza traduzione
		
		$hotel = Hotel::find($hotel_id);


		if ($da_approvare && $request->get('submit') == "save")
			{	
				///////////////////////////////////////////////////
				// INVIO MAIL NOTIFICA MODIFICA PER APPROVAZIONE //
				///////////////////////////////////////////////////
				try {
					
					$titolo_offerta = !is_null(Offerta::find($id)->translate('it')->first()) ? Offerta::find($id)->translate('it')->first()->titolo : '';

					$note = $motivazioni_str; 

					$note .= ' '.$request->get('note');


					self::sendMailModificaPerApprovazione($hotel,$titolo_offerta,$traduzione, $perArchiviazione = 0, $note);
					SessionResponseMessages::add("success", "Una mail di notifica della moderazione è stata inviata al cliente");
					
				} catch (\Exception $e) {
					SessionResponseMessages::add("error", "NON è stato possibile inviare la mail di notifica al cliente !!! [".$e->getMessage()."]" );
				}
			
			} else {
				
				SessionResponseMessages::add("success", "Nessuna notifica della moderazione è stata inviata al cliente");
				
			}

		if ($cambio_tipologia) 
			{
			SessionResponseMessages::add("success", "ATTENZIONE!!! Il lastminute è stato salvato come offerta.");
			}

		
		if ($request->get('salva_e_pubblica') == 1 && $hotel->superatoLimiteLast()) {
			SessionResponseMessages::add("success", "Modifiche salvate con successo MA OFFERTA NON ATTIVATA PER RAGGIUNTO NUMERO MASSIMO!!.");
			return SessionResponseMessages::redirect("admin/last/archiviati", $request);
		}
		else {
			SessionResponseMessages::add("success", "Modifiche salvate con successo.");
			if ($offerta->attivo) {
				return SessionResponseMessages::redirect("admin/last", $request);
			}
			else {
				return SessionResponseMessages::redirect("admin/last/archiviati", $request);
			}
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id)
	{
		OffertaLingua::where('master_id', $id)->delete();
		Offerta::destroy($id);

		SessionResponseMessages::add("success", "Last minute eliminato con successo.");

		
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo.");

		if ($request->get('archiviato')) {
			return SessionResponseMessages::redirect("/admin/last/archiviati", $request);
		}
		else {
			return SessionResponseMessages::redirect("/admin/last", $request);
		}
	}


	public function archiviati()
	{
		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		self::_archiviaScadute($hotel_id);

		// elimino le offerte archiviate più vecchie di 3 anni
		self::_eliminaOlderThan($hotel_id);

		$offerte = Offerta::where('tipologia', '=', 'lastminute')->where('hotel_id', $hotel_id)->archiviata()->with(['offerte_lingua'])->get();
		$oggi = Carbon::today();
		$archiviate = 1;
		return view('admin.last_index', compact('offerte', 'oggi', 'archiviate'));
	}


	public function archivia(Request $request, $id)
	{
		$offerta = Offerta::with('cliente')->find($id);
		$offerta->update(['attivo' => 0, 'note' => $request->get('note')]);
		$hotel = $offerta->cliente;

		$motivazioni_str = "";
		if($request->has('motivazioni'))
			{
			$offerta->motivazioni()->sync($request->get('motivazioni'));
			$motivazioni_str = $offerta->getMotivazioni();
			}
		
		
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo.");
		SessionResponseMessages::add("success", "Last minute archiviato con successo.");

		if (Auth::user()->hasRole(["root","admin", "operatore"]))
			{	
			////////////////////////////////////////
			// INVIO MAIL NOTIFICA ARCHIVIAZIONE //
			///////////////////////////////////////
			try {
				
				$note = $motivazioni_str; 

				$note .= ' '.$request->get('note');

				$titolo_offerta = !is_null($offerta->translate('it')->first()) ? $offerta->translate('it')->first()->titolo : '';
				self::sendMailModificaPerApprovazione($hotel,$titolo_offerta, $traduzione = 0, $perArchiviazione = 1, $note);
				SessionResponseMessages::add("success", "Una mail di notifica della moderazione è stata inviata al cliente");
				
			} catch (\Exception $e) {
				SessionResponseMessages::add("error", "NON è stato possibile inviare la mail di notifica al cliente !!! [".$e->getMessage()."]" );
			}
			
			}

		return SessionResponseMessages::redirect("/admin/last", $request);

	}


	public function ripristina(Request $request, $id)
	{
		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);
		
		
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo.");
		
		if (!Hotel::find($hotel_id)->superatoLimiteLast()) {

			// trovo offerta da ripristinare
			$offerta = Offerta::with('offerte_lingua')->find($id);

			//////////////////////////////////////////////////////////////////////////////////////////
			// ATTENZIONE se sono un admin o un operatore l'offerta salvata/creata è ANCHE VALIDATA //
			//////////////////////////////////////////////////////////////////////////////////////////
			$da_approvare = Auth::user()->hasRole(["root","admin", "operatore"]); 

			foreach ($offerta->offerte_lingua as $offerta_lingua)
				{
				if ($da_approvare)
					{	
					$offerta_lingua->approvata = 1;
					$offerta_lingua->data_approvazione = Carbon::now();
					}
				else
					{
					$offerta_lingua->approvata = 0;
					}
				}


			$offerta->attivo = 1;

			$offerta->save();

			SessionResponseMessages::add("success", "Last minute ripristinato con successo.");
			return SessionResponseMessages::redirect("/admin/last", $request);
		}
		else {
			return $this->index($message = Hotel::LIMIT_LAST);
		}

	}


}
