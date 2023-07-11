<?php

namespace App\Http\Controllers\Admin;

use App\ListinoCustom;
use App\ListinoCustomLingua;
use App\Utility;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Langs;
use SessionResponseMessages;

class ListiniCustomController extends AdminBaseController
{

	const EMPTY_TABLE = '<table>
  							<thead>
  								<tr>
  									<th width="20%"><br /></th>
  									<th width="16%"><br /></th>
  									<th width="16%"><br /></th>
  									<th width="16%"><br /></th>
  									<th width="16%"><br /></th>
  								</tr>
  							</thead>
  								<tbody>
  									<tr>
  										<td width="16%><br /></td>
  										<td width="16%><br /></td>
  										<td width="16%><br /></td>
  										<td width="16%><br /></td>
  										<td width="16%><br /></td>
  									</tr>
  								</tbody>
  							</table>';





	/**
	 * Ritorna il Query Builder per il model BambinoGratis pre-filtrato per l'hotel di appartenenza...<br>
	 * forse non sono stato chiaro ma è molto importante per la sicurezza,<br>
	 * altrimenti un malevolo potrebbe accedere le offerte di un altro
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	protected function LoadOwnedRecords()
	{

		// É importante che vengano solo caricati i record delle offerte appartenenti all'hotel corrente

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		return ListinoCustom
			::with("listiniCustomLingua")
			->where("hotel_id", $hotel_id);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$listini = $this->LoadOwnedRecords()
			->orderby("position", "asc")
			->get();

		$data = ["records" => $listini];

		if (Auth::user()->hasRole(["root", "admin", "operatore"])) {
			return view('admin.listini-custom_order', compact("data"));
		} else {
			return view('admin.listini-custom_index', compact("data"));
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function order()
	{
		$listini = $this->LoadOwnedRecords()
			->orderby("position", "asc")
			->get();

		$data = ["records" => $listini];

		return view('admin.listini-custom_order', compact("data"));
	}

	/**
	 * Mostra l'interfaccia di editing
	 *
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		$listino = $this->LoadOwnedRecords()
			->where("id", $id)
			->firstOrFail();



		/*
	     * La view tramite illuminate/html si aspetta un Model da passare in binding
	     * il Model sarebbe ListinoCustom, ma all'interno dello stesso tag form,
	     * avrò anche i model delle lingue (ListinoCustomLingua)
	     * Quindi mi faccio array che sarà passato in binding alla form che è l'unione delle due cose
	     */

		$i18n = [];
		$lang_fields = ["titolo", "tabella"];
		foreach ($listino->listiniCustomLingua as $rel)
			foreach ($lang_fields as $field) {
				if ($field === "tabella" && !$rel->$field)
					$i18n["{$field}_{$rel->lang_id}"] = self::EMPTY_TABLE;
				else
					$i18n["{$field}_{$rel->lang_id}"] = $rel->$field;
			}

		$advanced = Auth::user()->hasRole(["root", "admin", "operatore"]);

		$l = $request->get('l');

		$data = [
			"record" => $listino,
			"model" => $listino->toArray() + $i18n,
			"advanced" => $advanced,
			"l" => $l
		];


		return view('admin.listini-custom_edit', compact("data"));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$listino = new ListinoCustom;

		/*
     * La view tramite illuminate/html si aspetta un Model da passare in binding
     * il Model sarebbe ListinoCustom, ma all'interno dello stesso tag form,
     * avrò anche i model delle lingue (ListinoCustomLingua)
     * Quindi mi faccio array che sarà passato in binding alla form che è l'unione delle due cose
     */

		$i18n = [];
		$lang_fields = ["titolo", "tabella"];
		foreach (Langs::getAll() as $lang_id)
			foreach ($lang_fields as $field) {
				if ($field === "tabella")
					$i18n["{$field}_$lang_id"] = self::EMPTY_TABLE;
				else
					$i18n["{$field}_$lang_id"] = null;
			}

		$advanced = Auth::user()->hasRole(["root", "admin", "operatore"]);

		$data = [
			"record" => $listino,
			"model" => $listino->toArray() + $i18n,
			"advanced" => $advanced,
		];

		return view('admin.listini-custom_edit', compact("data"));
	}

	/**
	 * Store a newly created resource in storage.
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{

		//dd($request->all());

		$id = $request->input("id");


		foreach (Langs::getAll() as $lang_id) {

			//////////////////////////
			// CHECK CONTENUTO SPAM //
			//////////////////////////
			// $testo = $request->input("descrizione_$lang_id", "");

			// if (str_contains(strtolower($testo), ['http', 'www','://','@','.it','.com']) || preg_match("/[0-9]{6,}/", $testo))
			//   {
			//   $error = "Il testo in " .Utility::getLanguage($lang_id)[0]." NON deve contenere INDIRIZZI EMAIL, INDIRIZZI INTERNET o NUMERI DI TELEFONO";
			//   SessionResponseMessages::add("error",  $error);
			//   } 



			//////////////////////////////////////////////////////
			// VERIFICO CHE LA TABELLA CONTENGA <thead></thead> //
			// SOLO IN INSERIMENTO 														  //
			//////////////////////////////////////////////////////

			// Inserimento
			if (!$id) {
				$tabella = $request->input("tabella_$lang_id", "");

				preg_match("/<thead>(.*)/im", $tabella, $matches);

				if (empty($matches)) {
					$error = "La tabella in " . Utility::getLanguage($lang_id)[0] . " DEVE CONTENERE la riga di intestazione !";
					SessionResponseMessages::add("error",  $error);
				}
			}
		} // fine check in tutte le lingue

		// se alla fine del check ho degli errori redirigo
		if (SessionResponseMessages::hasErrors()) {

			if (!$id)
				return SessionResponseMessages::redirect("admin/listini-custom/create", $request);
			else
				return SessionResponseMessages::redirect("admin/listini-custom/$id/edit", $request);
		}


		// Inserimento
		if (!$id) {
			$listino = new ListinoCustom;
			$listino->hotel_id = $this->getHotelId();

			$worst_listino = $this->LoadOwnedRecords()
				->orderby("position", "desc")
				->take("1")
				->first();

			// Lo metto in ultima posizione
			$listino->position = 1;
			if ($worst_listino) {
				$listino->position = $worst_listino->position + 1;
			}
		}

		// Aggiornamento
		else {
			$listino = $this->LoadOwnedRecords()

				->where("id", $id)
				->firstOrFail();
		}


		$listino->attivo = (int)$request->input("attivo");
		$listino->intestazione = (int)$request->input("intestazione");
		$listino->save();

		// Cancello tutti i rcord i18n per poi reinserirli
		if ($i18n_ids = $listino->listiniCustomLingua->pluck("id")) {
			ListinoCustomLingua::whereIn("id", $i18n_ids)->delete();
		}


		/*
     * Ora popolo un array con le traduzioni nelle varie lingue,
     * in questo modo posso fare una unica query di bulk insert
     * http://stackoverflow.com/questions/12702812/bulk-insertion-in-laravel-using-eloquent-orm
     */
		$i18n = [];

		$titolo_it = $request->input("titolo_it", "Listino Prezzi");

		foreach (Langs::getAll() as $lang) {
			$content = null;
			if (preg_match("#(" . preg_quote("<table>") . ".*" . preg_quote("</table>") . ")#isU", $request->input("tabella_$lang", ""), $m))
				$content = $m[1];


			if ($lang != 'it') {

				$titolo = $request->input("titolo_$lang", "");

				if ( !strlen( trim($titolo) ) ) {

					$titolo = Utility::translate($titolo_it, $lang);
				}
			} else {

				$titolo = $titolo_it;
			}

			$i18n[] = [
				"lang_id" => $lang,
				"master_id" => $listino->id,
				"titolo" => $titolo,
				//"sottotitolo" => $request->input("sottotitolo_$lang", ""),
				//"descrizione" => $request->input("descrizione_$lang", ""),
				"tabella" => $content,
				"created_at" => date("Y-m-d H:i:s"),
				"updated_at" => date("Y-m-d H:i:s")
			];
		}

		
		// Mi costa una unica query di bulk insert
		ListinoCustomLingua::insert($i18n);

		SessionResponseMessages::add("success", "Modifiche salvate con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata.");

		return SessionResponseMessages::redirect("/admin/listini-custom/{$listino->id}/edit?l=" .  $request->input("current_lang"), $request);
	}

	public function saveOrder(Request $request)
	{

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		$tmp = $request->input("ids");

		$ids = [];
		if (strpos($tmp, ',') !== false)
			$ids = explode(',', $tmp);

		if ($ids) {
			DB::table("tblListiniCustom")
				->whereIn("id", $ids)
				->update(['position' => 0]);

			$i = 0;
			foreach ($ids as $id) {
				$i++;

				DB::table("tblListiniCustom")
					->where("id", $id)
					->update(['position' => $i]);
			}
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function destroy(Request $request)
	{
		$id = $request->input("id");

		ListinoCustom::destroy($id);

		SessionResponseMessages::add("success", "Il record ID=$id è stato eliminato.");

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);


		SessionResponseMessages::add("success", "La cache è stata aggiornata.");


		return SessionResponseMessages::redirect("/admin/listini-custom", $request);
	}


	public function translateDataAjax(Request $request)
	{
		$content = $request->get('content');
		//dd($content);

		$matches = [];
		preg_match('/<thead>(.*?)<\/thead>/s', $content, $matches);

		$thead_tag = $matches[0];



		$traduzioni = [];
		foreach (Utility::linguePossibili() as $lingua) {
			if ($lingua != 'it') {
				$traduzione_thead = Utility::translate($thead_tag, $lingua);

				$traduzioni[$lingua] = preg_replace('/<thead>(.*?)<\/thead>/s', $traduzione_thead, $content);
			}
		}
		//dd($traduzioni);

		echo json_encode($traduzioni);
	}
}
