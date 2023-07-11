<?php

namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\NoteListinoRequest;
use App\NotaListino;
use App\NotaListinoLingua;
use App\User;
use App\Utility;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Langs;
use SessionResponseMessages;
use Illuminate\Support\Str;

class NoteListinoController extends Controller
{


	const LIMIT_NOTE_it = 5000;
	const LIMIT_NOTE_en = 5020;
	const LIMIT_NOTE_fr = 5030;
	const LIMIT_NOTE_de = 5050;
	

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{

		if (Auth::user()->hasRole("hotel"))
			$hotel_id = Auth::user()->hotel_id;
		else
			$hotel_id = Auth::user()->getUiEditingHotelId();


		if (!$hotel_id)
			SessionResponseMessages::add('error', "Per entrare nelle 'Note Listino' devi prima selezionare un hotel.");
		else {
			$data = [];

			if (Auth::user()->hasRole(["root", "admin", "operatore"])) {
				$results = NotaListino::with('notelistino_lingua')
				->where('hotel_id', '=', $hotel_id)
				->first();

				if (is_null($results)) {
					Hotel::find($hotel_id)->createEmptyNota();
					$results = NotaListino::with('notelistino_lingua')
					->where('hotel_id', '=', $hotel_id)
					->first();
				}

				$data['attivo'] = $results->attivo;

				if ($results) {
					foreach ($results->notelistino_lingua as $nota_listino)
						$data['record'][$nota_listino->lang_id] = $nota_listino;

					$data['id'] = $results->id;
				}
			}
			else {
				$results = NotaListino::with('notelistino_lingua')
					->where('hotel_id', '=', $hotel_id)
					->attivo()
					->first();

				if (is_null($results)) {
					Hotel::find($hotel_id)->createEmptyNota();
					$results = NotaListino::with('notelistino_lingua')
					->where('hotel_id', '=', $hotel_id)
					->attivo()
					->first();
				}

				if ($results) {
					foreach ($results->notelistino_lingua as $nota_listino)
						$data['record'][$nota_listino->lang_id] = $nota_listino;

					$data['id'] = $results->id;
				}
				else
					SessionResponseMessages::add('error', "Non puoi accedere in questa sezione.");
			}
		}

		if (SessionResponseMessages::hasErrors())
			return SessionResponseMessages::redirect('admin', $request);
		else
			return view('admin.note-listino', compact('data'));
	}


	/**
	 * Salve il contenuto delle note listino per tutte le lingue
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function save(Request $request)
	{

		if ($request->method() == 'POST') {

			// Solo gli utenti amministratori possono attivare o meno la nota listino
			if (Auth::user()->hasRole(["root", "admin", "operatore"])) {
				$notalistino = NotaListino::find($request->input('id'));
				$notalistino->attivo = $request->input('attivo');
				$notalistino->save();
			}

			foreach (Langs::getAll() as $lang_id) 
				{

				$testo = $request->input("testo." .$lang_id);


				//////////////////////////////////////////////////////////////////////////////////////////////////////
				// CHECK LUNGHEZZA - NON POSSO UTILIZZARE IL FORMREQUEST DI LARAVEL PERCHE' VOGLIO STRIPPARE L'HTML //
				//////////////////////////////////////////////////////////////////////////////////////////////////////
				$testo_da_contare = html_entity_decode($testo);
				$testo_da_contare = preg_replace("/\r\n|\r|\n/",'<br/>',$testo_da_contare);
				
				$lung = strlen(strip_tags($testo_da_contare));

				$const_note = 'LIMIT_NOTE_'.$lang_id;

				if ($lung > constant("App\Http\Controllers\Admin\NoteListinoController::$const_note")) 
					{
					$error = "Le note in " .Utility::getLanguage($lang_id)[0]." possono contenere al massimo " .constant("App\Http\Controllers\Admin\NoteListinoController::$const_note"). " caratteri, il tuo testo è lungo $lung caratteri";
				  	SessionResponseMessages::add("error",  $error);
					}
				
				
				//////////////////////////
				// CHECK CONTENUTO SPAM //
				//////////////////////////
				if (Str::contains(strtolower($testo), ['http', 'www','://','@','.it','.com']))
				  {
				  $error = "Il testo in " .Utility::getLanguage($lang_id)[0]." NON deve contenere INDIRIZZI EMAIL, INDIRIZZI INTERNET o NUMERI DI TELEFONO";
				  SessionResponseMessages::add("error",  $error);
				  } 




				} // fine check in tutte le lingue

				// se alla fine del check ho degli errori redirigo
				if (SessionResponseMessages::hasErrors()) 
					{
					return SessionResponseMessages::redirect("admin/note-listino", $request);
					}

			foreach (Langs::getAll() as $lang_id) {
				

				$id = $request->input("testo_id." .$lang_id);
				$testo = $request->input("testo." .$lang_id);

				$notalistino = NotaListinoLingua::find($id);
				$notalistino->testo = $testo;
				$notalistino->save();
			}

			SessionResponseMessages::add('success', 'Note listino aggiornate correttamente.');
		}
		
		
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");

		return SessionResponseMessages::redirect("admin/note-listino", $request);
	}


}
