<?php

/**
 * BambiniGratisController
 *
 * @author Info Alberghi Srl
 * 
 */	

namespace App\Http\Controllers\Admin;

use App\BambinoGratis;
use App\BambinoGratisLingua;
use App\Hotel;
use App\Http\Requests\Admin\BambiniGratisRequest;
use App\Utility;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Langs;
use SessionResponseMessages;

class BambiniGratisController extends AdminBaseController
{
	const LIMIT_TESTO = 500;
	const LIMIT_TESTO_it = 500;
	const LIMIT_TESTO_en = 500;
	const LIMIT_TESTO_fr = 500;
	const LIMIT_TESTO_de = 500;

	// al massimo un'offerta può partire da 1 anno da oggi
	const MAX_DAL_NUMBER = 365;
  
  
	/* ------------------------------------------------------------------------------------
	 * METODI PRIVATI
	 * ------------------------------------------------------------------------------------ */
	

	/**
	 * aggiunge al data i limiti.
	 * 
	 * @access private
	 * @param array &$data
	 * @return void
	 */
	 
	private function _pass_limitations_to_form(&$data)
	{

		$data["LIMIT_TESTO"] = self::LIMIT_TESTO;
		$data["LIMIT_TESTO_it"] = self::LIMIT_TESTO_it;
		$data["LIMIT_TESTO_en"] = self::LIMIT_TESTO_en;
		$data["LIMIT_TESTO_fr"] = self::LIMIT_TESTO_fr;
		$data["LIMIT_TESTO_de"] = self::LIMIT_TESTO_de;

	}	 	 
	
	
	/**
	 * Spedisce l'email al cliente per comincare la moderazione o l'archiviazione
	 * 
	 * @access private
	 * @param Hotel $hotel
	 * @param string $titolo_offerta
	 * @param int $traduzione (default: 0)
	 * @param int $perArchiviazione (default: 0)
	 * @return void
	 */
	 
	private function _sendMailModificaPerApprovazione($hotel, $titolo_offerta,  $traduzione = 0, $perArchiviazione = 0,  $note = null)
	{

		Utility::swapToSendGrid();

		$hotel_id = $hotel->id;
		$nome_cliente = $hotel->nome;

		$from = "assistenza@info-alberghi.com";
		$nome = "Lo staff di Info Alberghi";

		$oggetto = "";

		$oggetto = $perArchiviazione ? "Archiviazione Offerta bambini gratis" : "Moderazione Offerta bambini gratis";

		$tipo ="Offerta bambini gratis";
		$ancora = "children-offers";

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


	// SECONDA mail nicole@ per storico
	
	try {

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

		), function ($message) use ($from, $oggetto, $nome, $to) {

			$message->from($from, $nome);
			$message->replyTo($from);
			$message->to($to);
			$message->subject($oggetto);
			
		});

	} catch (Exception $e) {
		echo "Error " . $e->getMessage();
	}



	}


	/**
	 * Spedisce l'email al cliente per comincare l'eliminazione
	 * 
	 * @access private
	 * @param Hotel $hotel
	 * @param string $titolo_offerta
	 * @return void
	 */
	 
	private function _sendMailEliminaOfferta($hotel, $titolo_offerta)
	{

		Utility::swapToSendGrid();

		$hotel_id = $hotel->id;
		$nome_cliente = $hotel->nome;

		$from = "assistenza@info-alberghi.com";
		$nome = "Lo staff di Info Alberghi";

		$oggetto = "Cancellazione Offerta";
		$tipo ="Offerta bambini gratis";

		$email_cliente = explode(',', $hotel->email);
		if ($hotel->email_secondaria != "")
			$email_cliente = explode(',', $hotel->email_secondaria);

		Mail::send('emails.eliminazione_offerta', compact('titolo_offerta', 'nome_cliente', 'hotel_id', 'tipo','oggetto'), function ($message) use ($from, $oggetto, $nome, $email_cliente)
			{
				$message->from($from, $nome);
				$message->replyTo($from);
				$message->to($email_cliente);
				$message->subject($oggetto);
			});

	}


	/**
	 * Ritorna il Query Builder per il model BambinoGratis pre-filtrato per l'hotel di appartenenza...<br>
	 * forse non sono stato chiaro ma è molto importante per la sicurezza,<br>
	 * altrimenti un malevolo potrebbe accedere le offerte di un altro
	 * @return Illuminate\Database\Eloquent\Builder
	 *
	 * @access protected
	 * @return BambinoGratis
	 */
	 
	protected function LoadOwnedRecords()
	{

		/**
		 * É importante che vengano solo caricati i record delle offerte appartenenti all'hotel corrente
		 */

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);
		return BambinoGratis::with('offerte_lingua')->where("hotel_id", $hotel_id);
		
	}


	/**
	 * [_eliminaOlderThan elimino automaticamente le offerte più vecchie di $years anni]
	 * @param  integer $hotel_id [description]
	 * @return [type]            [description]
	 */
	private function _eliminaOlderThan()
		{
		
		$years = 3;
		$hotel_id = $this->getHotelId();
		
		$offerte_to_del = BambinoGratis::where('hotel_id', $hotel_id)->olderThan($years)->get();
		
		$to_remove = $offerte_to_del->count();
		if ($to_remove) 
			{
			foreach ($offerte_to_del as $offerta) 
				{
				BambinoGratisLingua::where('master_id', $offerta->id)->delete();
				$offerta->delete();
				}
			SessionResponseMessages::add("error", "ATTENZIONE!! Il sistema ha AUTOMATICAMENTE RIMOSSO $to_remove promozioni più vecchie di $years anni.");
			Session::put("SessionResponseMessages", SessionResponseMessages::$msgs);
			}	

		}
	
	
  
	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( VIEWS )
	 * ------------------------------------------------------------------------------------ */



	 
	/**
	 * Lista offerte bambini gratis.
	 * 
	 * @access public
	 * @return View
	 */
	 
	public function index()
	{
		// elimino le offerte archiviate più vecchie di 3 anni
		self::_eliminaOlderThan();

		$bambini_gratis = $this->LoadOwnedRecords()->get();
		$data = ["records" => $bambini_gratis];
		return view('admin.bambini-gratis_index', compact("data"));
		
	}


	/**
	 * Modifica offerta BG.
	 * 
	 * @access public
	 * @param  int $id
	 * @return View
	 */
	public function edit($id)
	{
		$bambino_gratis = $this->LoadOwnedRecords()
		->where("id", $id)
		->firstOrFail();

		//$offertaLingua = [];
		foreach (Utility::linguePossibili() as $lingua) 
			{
			$offertaLingua[$lingua] = '';
			}

		foreach ($bambino_gratis->offerte_lingua as $offerta_lingua) 
			{
			$offertaLingua[$offerta_lingua->lang_id] = str_replace("<br />", "", $offerta_lingua->note);
			}

		$data = [
			"record" => $bambino_gratis,
			"valido_dal" => $bambino_gratis->valido_dal,
			"valido_al" => $bambino_gratis->valido_al
		];

		$this->_pass_limitations_to_form($data);

		$data['showArchivia'] = Auth::user()->hasRole(["root", "admin", "operatore"]);
		$data['showSaveWithoutEmail'] = Auth::user()->hasRole(["root", "admin", "operatore"]);

		return view('admin.bambini-gratis_edit', compact("data","offertaLingua"));

	}

	 
	/**
	 * Form per la creazione di una nuova offerta
	 * 
	 * @access public
	 * @return View
	 */
	 
	public function create()
	{
		
		$data = [
			"record" => new BambinoGratis,
			"valido_dal" => Carbon::createFromDate(date("Y"), 6, 1),
			"valido_al" => Carbon::createFromDate(date("Y"), 9, 30),
		];

		$data["LIMIT_TESTO"] = self::LIMIT_TESTO;
		return view('admin.bambini-gratis_edit', compact("data"));
		
	}

	
	
  
	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( CONTROLLERS )
	 * ------------------------------------------------------------------------------------ */


	 
	/**
	 * Salvataggio dei dati
	 * 
	 * @access public
	 * @param BambiniGratisRequest $request
	 * @return SessionResponseMessages
	 */
	public function store(BambiniGratisRequest $request)
	{			
		$id = $request->input("id");

		if($request->has('archiviazione') && $request->get('archiviazione') == 1)
			{
			$this->archivia($request, $id);
			}
				
		/**
		 * Inserimento
		 */
		if (!$id) {
			
			$bambino_gratis = new BambinoGratis;
			$bambino_gratis->hotel_id = $this->getHotelId();
			
		} else {
	
		/**
		 * Aggiornamento
		 */
			$bambino_gratis = $this->LoadOwnedRecords()
			->where("id", $id)
			->firstOrFail();
			
		}

	
		$bambino_gratis->fino_a_anni = $request->get('fino_a_anni');
		$bambino_gratis->anni_compiuti = $request->get('anni_compiuti');

		$bambino_gratis->solo_2_adulti = $request->get('solo_2_adulti');
		
	
		
		/**
		 * Sostituito con il nuovo datepicker
		 *
		 * if (preg_match("#(\d{1,2})/(\d{1,2})/(\d{4}) - (\d{1,2})/(\d{1,2})/(\d{4})#", $request->input("date_range_validita"), $m)) {
		 *		$bambino_gratis->valido_dal = Carbon::createFromDate($m[3], $m[2], $m[1]);
		 * 		$bambino_gratis->valido_al = Carbon::createFromDate($m[6], $m[5], $m[4]);
		 * }
		 */
		
		$bambino_gratis->valido_dal = Utility::getCarbonDate($request->get('valido_dal'));
		$bambino_gratis->valido_al 	= Utility::getCarbonDate($request->get('valido_al'));

		if ($request->get('salva_e_pubblica') == 1)
			{
			$bambino_gratis->attivo = 1;
			}

		if($bambino_gratis->hotel_id != $this->getHotelId())
			{
			$tipo = 'bg';

			// CONTROLLO CHE l'OFFERTA CHE STO MODIFICANDO APPARTENGA ALL'HOTEL IN SESSIONE
			Utility::NotifyOfferta($request, $bambino_gratis, $this->getHotelId(), $tipo);

			SessionResponseMessages::add("error", "ATTENZIONE: ".$tipo." non appartiene all'hotel IMPERSONIFICATO !!!");
			return SessionResponseMessages::redirect("/admin", $request);
			}
	

		$bambino_gratis->save();



		/**
	  * Inserimento
	  */
		if (!$id) 
			{
			/*
			GESTIONE LINGUE INSERIMENTO
			 */
			$note_ita = nl2br($request->get("note"));
			foreach (Utility::linguePossibili() as $lingua) 
				{

				$offertaLingua = new BambinoGratisLingua;
				$offertaLingua->lang_id = $lingua;
				
				if ($lingua == 'it') 
					{
					$offertaLingua->note = $note_ita;
					} 
				else 
					{
					$offertaLingua->note =  Utility::translate($note_ita, $lingua);
					}
				
				/**
				 * ATTENZIONE se sono un admin o un operatore l'offerta salvata/creata è ANCHE VALIDATA
				 */ 
				 
				$da_approvare = Auth::user()->hasRole(["root", "admin", "operatore"]);

				if ($da_approvare) 
					{
					$offertaLingua->approvata = 1;
					$offertaLingua->data_approvazione = Carbon::now();
					} 
				else 
					{
					$offertaLingua->approvata = 0;
					}

				$bambino_gratis->offerte_lingua()->save($offertaLingua);

				}


			} 
		else 
			{

			/*
			GESTIONE LINGUE MODIFICA
			 */
			$note_ita = nl2br($request->get("noteit".$bambino_gratis->id));
			($request->filled('traduci') && $request->get('traduci') == 1) ? $traduzione = 1 : $traduzione = 0;
			foreach (Utility::linguePossibili() as $lingua) 
				{

				$offertaLingua = new BambinoGratisLingua;
				$offertaLingua->lang_id = $lingua;
				
				if ($lingua == 'it') 
					{
					$offertaLingua->note = $note_ita;
					} 
				else 
					{
					if ($traduzione) 
						{
						 $offertaLingua->note =  Utility::translate($note_ita, $lingua);
						} 
					else 
						{
						$key = "note{$lingua}{$id}";
						$offertaLingua->note =  nl2br($request->get($key));
						}
					}
				

				/**
				 * ATTENZIONE se sono un admin o un operatore l'offerta salvata/creata è ANCHE VALIDATA
				 */ 
				 
				$da_approvare = Auth::user()->hasRole(["root", "admin", "operatore"]);

				if ($da_approvare) 
					{
					$offertaLingua->approvata = 1;
					$offertaLingua->data_approvazione = Carbon::now();
					} 
				else 
					{
					$offertaLingua->approvata = 0;
					}

				if (!is_null($bambino_gratis->translate($lingua)->first())) 
					{
					$bambino_gratis->translate($lingua)->first()->delete();
					}				
				$bambino_gratis->offerte_lingua()->save($offertaLingua);

				}

			}
		





		if ($id && $da_approvare && $bambino_gratis->isAttivo() && $request->get('submit') == "save") {

			$hotel = Hotel::find($this->getHotelId());

			/**
			 * Invio mail notifica modifica per approvazione
			 */
			 
			try {

				$note = $request->get('note');

				$titolo_offerta = "BAMBINI GRATIS fino a ".$bambino_gratis->_fino_a_anni() . $bambino_gratis->_anni_compiuti();
				self::_sendMailModificaPerApprovazione($hotel, $titolo_offerta,  $traduzione, $perArchiviazione = 0, $note);


				SessionResponseMessages::add("success", "Una mail di notifica della moderazione è stata inviata al cliente");

			} catch (\Exception $e) {

				SessionResponseMessages::add("error", "NON è stato possibile inviare la mail di notifica al cliente !!! [".$e->getMessage()."]" );
			}

		} else
			SessionResponseMessages::add("success", "Nessuna notifica della moderazione è stata inviata al cliente");
		
		
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");

		if (!SessionResponseMessages::hasErrors())
			SessionResponseMessages::add("success", "Modifiche salvate con successo.");

		
		return SessionResponseMessages::redirect("/admin/bambini-gratis", $request);
		
	}

	/**
	 * Rimuovo l'offerta
	 *
	 * @param  Request $request
	 * @return SessionResponseMessages
	 */
	 
	public function destroy(Request $request)
	{
		$id = $request->get('id');

		$bg = BambinoGratis::find($id);
		$attivo = $bg->isAttivo();
		$titolo_offerta = "BAMBINI GRATIS fino a ".$bg->_fino_a_anni() . $bg->_anni_compiuti();
		$hotel = Hotel::find($this->getHotelId());
		Utility::clearCacheHotel($this->getHotelId());

		BambinoGratisLingua::where('master_id', $id)->delete();
		BambinoGratis::destroy($id);
	

		if (Auth::user()->hasRole(["root", "admin", "operatore"]) && $attivo) {
			
			/**
			 * INVIO MAIL NOTIFICA ARCHIVIAZIONE
			 */
			 
			try
			{
				self::_sendMailEliminaOfferta($hotel, $titolo_offerta);
				SessionResponseMessages::add("success", "Una mail di notifica della cancellazione è stata inviata al cliente");
			}
			catch (\Exception $e) {
				SessionResponseMessages::add("error", "NON è stato possibile inviare la mail di notifica al cliente !!! [".$e->getMessage()."]" );
			}
				
		}

		

		SessionResponseMessages::add("success", "Modifiche salvate con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
		return SessionResponseMessages::redirect("/admin/bambini-gratis", $request);

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
		$bg = BambinoGratis::with('cliente')->find($id);
		$bg->update(['attivo' => 0]);
		$hotel = $bg->cliente;
		
		
		SessionResponseMessages::add("success", "Offerta archiviata con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");

		if (Auth::user()->hasRole(["root", "admin", "operatore"])) {
			
			/**
			 * Lnvio mail notifica archiviazione
			 */
			 
			try {

				$note = $request->get('note');

				$titolo_offerta = "BAMBINI GRATIS fino a ".$bg->_fino_a_anni() . $bg->_anni_compiuti();

				self::_sendMailModificaPerApprovazione($hotel, $titolo_offerta, $traduzione = 0, $perArchiviazione = 1, $note);
				SessionResponseMessages::add("success", "Una mail di notifica della moderazione è stata inviata al cliente");

			} catch (\Exception $e) {
				
				SessionResponseMessages::add("error", "NON è stato possibile inviare la mail di notifica al cliente !!! [".$e->getMessage()."]" );

			}

		}

		return SessionResponseMessages::redirect("/admin/bambini-gratis", $request);

	}

}
