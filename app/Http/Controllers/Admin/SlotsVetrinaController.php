<?php
namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\SlotVetrinaRequest;
use App\SlotVetrina;
use App\Vetrina;
use Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SessionResponseMessages;
use App\library\ImageVersionHandler;
use File;
use Log;

class SlotsVetrinaController extends Controller
{

	private function _getCarbonDateFromRequest($data_str)
	{
		list($d, $m, $y) = explode('/', $data_str);
		return Carbon::createFromDate($y, $m, $d);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($vetrina_id = 0)
	{
		
		$vetrina = Vetrina::find($vetrina_id);
		$slots = $vetrina->slots()->with('cliente')->get();
		$data = ["records" => $slots];
		return view('admin.slotsvetrina_index', compact("data", "vetrina"));
		
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($vetrina_id = 0)
	{

		$vetrina = Vetrina::find($vetrina_id);
		$slot = new SlotVetrina;

		////////////////////////////////////////////////
		// elenco degli hotel a cui associare lo slot //
		////////////////////////////////////////////////
				
		$hotels_collection = Hotel::attivo()->get(['id', 'nome']);

		$hotels = [];

		foreach ($hotels_collection as $h) {
			$hotels[$h->id] = $h->id . ' - ' . $h->nome;
		}

		$data = ["record" => $slot];

		return view('admin.slotsvetrina_edit', compact("data", "vetrina", "hotels"));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($vetrina_id = 0, SlotVetrinaRequest $request)
	{
		
		$slotupdate = $request->all();
		$slotupdate['data_scadenza'] = $this->_getCarbonDateFromRequest($slotupdate['data_scadenza']);
		
		$cliente = Hotel::find($slotupdate['hotel_id']);
		$slot = new SlotVetrina($slotupdate);
		
		$slot->hotel_nome = $cliente->nome;
		$slot->hotel_categoria_id = $cliente->categoria_id;
		$slot->hotel_prezzo_min = $cliente->prezzo_min;
		$slot->hotel_prezzo_max = $cliente->prezzo_max;
		
		Vetrina::find($vetrina_id)->slots()->save($slot);
		return redirect()->route('vetrine.slots.index', [$vetrina_id]);

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

		//


	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 
	public function edit($vetrina_id = 0, $id)
	{
		$vetrina = Vetrina::find($vetrina_id);
		$slot = SlotVetrina::with(['cliente.localita.macrolocalita', 'cliente.stelle', ])->find($id);
		$data = ["record" => $slot];
		return view('admin.slotsvetrina_edit', compact("data", "vetrina"));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * The Request facade will grant you access to the current request that is bound in the container.
	 * Let’s stick with the dependency injection method.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 
	public function update($vetrina_id = 0, $id, SlotVetrinaRequest $request)
	{
		$slotupdate = $request->all();
		$slotupdate['data_scadenza'] = $this->_getCarbonDateFromRequest($slotupdate['data_scadenza']);
		$slot = SlotVetrina::with('cliente')->find($id);
		Utility::clearCacheHotel($id);
        
		$slot->update($slotupdate);
		return redirect()->route('vetrine.slots.index', [$vetrina_id]);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($vetrina_id = 0, $id, Request $request)
	{

		$slot = SlotVetrina::findOrFail($id);
		$slot->delete();
		SessionResponseMessages::add("success", "Lo slot è stato eliminato.");
		return SessionResponseMessages::redirect("admin/vetrine/$vetrina_id/slots", $request);
		
	}


	/**
	 * DEPRECATA
	 *
	 * Cancella l'immagine listing_img (file su filesystem e valore su db)
	 * @param  Request  $request
	 * @return Response
	 */
	/*public function removeImage(Request $request)
	{
		$id = $request->input("id");
		$slot = SlotVetrina::findOrFail($id);
		if ($slot->deleteImg()) {
			$slot->save();
			SessionResponseMessages::add("success", "Immagine cancellata con successo.");
		}
		else SessionResponseMessages::add("error", "Non è stato possibile cancellare l'immagine, verificare i diritti della directory " . public_path(SlotVetrina::SLOT_IMG_PATH));

		return SessionResponseMessages::redirect("/admin/vetrine/{$slot->vetrina->id}/slots/$id/edit", $request);
	}*/


}
