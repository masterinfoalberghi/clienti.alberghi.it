<?php
namespace App\Http\Controllers\Admin;

use App\CmsPagina;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\VetrineTrattamentoTopRequest;
use App\Utility;
use App\VetrinaTrattamentoTop;
use App\VetrinaTrattamentoTopLingua;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Langs;
use SessionResponseMessages;

class VetrinaTrattamentoTopController extends SUPERVetrinaTopController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// filtro le pagine da associare per lingua e per la macrolocalità dell'hotel + quelle TRASVERSALI, cioè senza località e senza macro //
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////
	// un'offerta TOP di un hotel di RImini NON SARA' associata ad una pagina di Cervia //
	//////////////////////////////////////////////////////////////////////////////////////


	///////////////////////////////////////////////////////////////////////////////////////////
	// se sono in MODIFICA, tra le pagine da mostarre ci devono essere anche quelle correnti //
	///////////////////////////////////////////////////////////////////////////////////////////
	

	private function _getPages($macrolocalita_id = 0, $lang_id = 'it', $modifica = false)
	{

		////////////////////////////////////////////////////////////////////////////////////////////
		// devo trovare le pagine VetrineTopEnabled per ESCLUIDERLE                               //
		////////////////////////////////////////////////////////////////////////////////////////////
		$ids_pagine_to_exclude =  CmsPagina::attiva()->vetrinaTopEnabled()->pluck('id');


		///////////////////////////////////////////////////////////////////////////////
		// devo trovare le pagine con vtt già assegnate a questo hotel ed ESCLUDERLE //
		///////////////////////////////////////////////////////////////////////////////


		if(!$modifica)
			{
			///////////////////////////////////////////////////////////////////////////////
			//SE SONO IN INSERIMENTO devo trovare le pagine con vtt già assegnate a questo hotel ed ESCLUDERLE //
			///////////////////////////////////////////////////////////////////////////////

			// vetrine top hotel corrente
			$vtt_current_hotel = VetrinaTrattamentoTop::
			where('hotel_id', $this->getHotelId())
			->attiva()
			->with(
				['vetrine_lingua' => function($query) use ($lang_id)
				{
					$query->where('lang_id', $lang_id);
				}


				]
			)
			->get();


			$ids_pagine_already_vtt_to_exclude = [];

			foreach ($vtt_current_hotel as $vtt) {
				$ids_pagine_already_vtt_to_exclude[] = $vtt->vetrine_lingua->first()->pagina_id;
			}

			return CmsPagina::attiva()
			->lingua($lang_id)
			->listingMacroLocalitaOrTrasversali($macrolocalita_id)
			->whereNotIn('id', $ids_pagine_to_exclude)
			->whereNotIn('id', $ids_pagine_already_vtt_to_exclude)
			->where('listing_attivo', 1)
			->where('listing_trattamento', '!=', '')
			->orderBy('uri', 'asc')
			->pluck('uri', 'id');
			}
		else
			{
			return CmsPagina::attiva()
			->lingua($lang_id)
			->listingMacroLocalitaOrTrasversali($macrolocalita_id)
			->whereNotIn('id', $ids_pagine_to_exclude)
			->where('listing_attivo', 1)
			->where('listing_trattamento', '!=', '')
			->orderBy('uri', 'asc')
			->pluck('uri', 'id');
			}
	}



	private function _creaVetrina($request, &$vetrina)
	{

		$vetrina->attivo = 1;
		$vetrina->hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($vetrina->hotel_id);
		$vetrina->mese = implode(',', $request->get('mese'));
		$vetrina->save();
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($message = "")
	{
		$hotel_id = $this->getHotelId();

		$vetrine = VetrinaTrattamentoTop::where('hotel_id', $hotel_id)
		->nonArchiviata()
		->with(['vetrine_lingua.pagina'])
		->get();
		
		$mese = date('n').'-'.date('Y');

		return view('admin.vetrina_trattamento_top_index', compact('vetrine', 'mese', 'message'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{

		$hotel_id = $this->getHotelId();

		$macrolocalita_id = Hotel::find($hotel_id)->localita->macrolocalita_id;

		$data = [];

		$anni = self::_getYears();

		$mesi = self::_getMesi($anni);
		
		$data['mesi'] = $mesi;

		$pagine = [];
		foreach (Langs::getAll() as $lang) {
			$pagine[$lang] = $this->_getPages($macrolocalita_id, $lang);
		}

		$data['pagine'] = $pagine;

		return view('admin.vetrina_trattamento_top_form', $data);

	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(VetrineTrattamentoTopRequest $request)
	{

		// nuova vetrina
		$vetrina = new VetrinaTrattamentoTop;

		$mesi = self::preparaMesiPerRequest($request); 
		$request->request->add(['mese' => $mesi]);

		$this->_creaVetrina($request, $vetrina);


		foreach (Utility::linguePossibili() as $lang_id) {
			$vetrinaLingua = new VetrinaTrattamentoTopLingua;

			$vetrinaLingua->lang_id = $lang_id;
			$key = "pagina_id{$lang_id}";
			$pagina_id = $request->get($key);
			$vetrinaLingua->pagina_id = $pagina_id;

			$vetrina->vetrine_lingua()->save($vetrinaLingua);

		}

		SessionResponseMessages::add("success", "Inserimento effettuato con successo");
		return SessionResponseMessages::redirect("admin/vetrine-trattamento-top", $request);

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

		$macrolocalita_id = Hotel::find($hotel_id)->localita->macrolocalita_id;

		$vetrina = VetrinaTrattamentoTop::with(['vetrine_lingua'])->where('hotel_id', $hotel_id)->where('id', $id)->get()->first();

		$vetrinaLingua = [];
		$pagine = [];

		foreach ($vetrina->vetrine_lingua as $vetrina_lingua) {

			$vetrinaLingua[$vetrina_lingua->lang_id]['pagina_id'] = $vetrina_lingua->pagina_id;

			$pagine[$vetrina_lingua->lang_id] = $this->_getPages($macrolocalita_id, $vetrina_lingua->lang_id, $modifica = true);

		}


		/*

    $vetrinaLingua

    array:4 [▼
      "it" => array:1 [▼
        "pagina_id" => "180"
      ]
      "en" => array:1 [▼
        "pagina_id" => "2050"
      ]
      "fr" => array:1 [▼
        "pagina_id" => "2051"
      ]
      "de" => array:1 [▼
        "pagina_id" => "2052"
      ]
    ]

     */

		$data['vetrina'] = $vetrina;
		$data['vetrinaLingua'] = $vetrinaLingua;
		$data['pagine'] = $pagine;
		$data['showButtons'] = 1;


		$anni = self::_getYears();

		$mesi = self::_getMesi($anni);
		
		$data['mesi'] = $mesi;


		return view('admin.vetrina_trattamento_top_form', $data);

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(VetrineTrattamentoTopRequest $request, $id)
	{

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		// trovo offerta da aggiornare
		$vetrina = VetrinaTrattamentoTop::with('vetrine_lingua')->find($id);

		$mesi = self::preparaMesiPerRequest($request);

		$request->request->add(['mese' => $mesi]);

		//riempio un array con i nuovi dati
		$new_vetrina = [
		'hotel_id' => $this->getHotelId(),
		'mese' => implode(',', $request->get('mese')),
		];


		if ($request->get('salva_e_pubblica') == 1) {
			$new_vetrina['attivo'] = 1;
		}

		// riempio l'offerta con i nuovi dati e salvo
		$vetrina->fill($new_vetrina);
		$vetrina->save();


		$nuova_vetrina_lingua = [];

		foreach ($vetrina->vetrine_lingua as $vetrina_lingua) {
			$original_offerta_lingua = $vetrina_lingua->toArray();

			$key = "pagina_id{$vetrina_lingua->lang_id}";

			$pagina_id = $request->get($key);
			$original_offerta_lingua['pagina_id'] = $pagina_id;

			unset($original_offerta_lingua['id']);
			$nuova_vetrina_lingua[] = $original_offerta_lingua;

			$vetrina_lingua->delete();
		}

		VetrinaTrattamentoTopLingua::insert($nuova_vetrina_lingua);

		SessionResponseMessages::add("success", "Modifiche salvate con successo.");


		if ($vetrina->attivo) {
			return SessionResponseMessages::redirect("admin/vetrine-trattamento-top", $request);
		}
		else {
			return SessionResponseMessages::redirect("admin/vetrine-trattamento-top/archiviati", $request);
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

		VetrinaTrattamentoTopLingua::where('master_id', $id)->delete();
		VetrinaTrattamentoTop::destroy($id);
		SessionResponseMessages::add("success", "Vetrina trattamento TOP eliminata con successo.");
		return SessionResponseMessages::redirect("/admin/vetrine-trattamento-top", $request);


	}



	public function archiviate()
	{
		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);
		
		$vetrine = VetrinaTrattamentoTop::where('hotel_id', $hotel_id)->archiviata()->with(['vetrine_lingua.pagina'])->get();
		$mese = date('n');
		$archiviate = 1;
		return view('admin.vetrina_trattamento_top_index', compact('vetrine', 'mese', 'archiviate'));
	}


	public function archivia(Request $request, $id)
	{
		VetrinaTrattamentoTop::find($id)->update(['attivo' => 0]);
		SessionResponseMessages::add("success", "Vetrina trattamento TOP archiviata con successo.");
		return SessionResponseMessages::redirect("/admin/vetrine-trattamento-top", $request);

	}


	public function ripristina(Request $request, $id)
	{
		VetrinaTrattamentoTop::find($id)->update(['attivo' => 1]);
		SessionResponseMessages::add("success", "Vetrina trattamento TOP ripristinata con successo.");
		return SessionResponseMessages::redirect("/admin/vetrine-trattamento-top/archiviati", $request);

	}


	public function listPage(Request $request)
		{

		$id_hotel = $this->getHotelId();
		
		$vot = VetrinaTrattamentoTop::with([
								'vetrine_lingua' => function($query)
								{
									$query
									->where('lang_id', '=', 'it');
								},
								])
								->attiva()->nonArchiviata()
								->where('hotel_id', $id_hotel)->get();
		
		$data = [];
		$titolo = 'Trattamenti';


		foreach ($vot as $k => $v) 
			{
			$chiave = $k+1;
			if (!is_null($v->vetrine_lingua)) 
				{
				$vl = $v->vetrine_lingua->first();
				$pagina = CmsPagina::find($vl->pagina_id);
				if(!is_null($pagina))
					{
					$chiave = $pagina->ancora;
					$data[$chiave] = url($pagina->uri); 
					}
				}
			}
		return view('admin.elenco_evidenze_hotel', compact('data','titolo'));

		}


}
