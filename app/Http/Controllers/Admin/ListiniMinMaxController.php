<?php
namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\ListinoMinMax;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ListiniMinMaxController extends AdminBaseController
{

	


	public function createEmptyListino($hotel_id, $dal = null, $al = null)
	{

		$listino = new ListinoMinMax;

		if (!is_null($dal)) {
			$listino->periodo_dal = $dal;
		}

		if (!is_null($al)) {
			$listino->periodo_al = $al;
		}

		Hotel::find($hotel_id)->listiniMinMax()->save($listino);

	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		$years = 2;

		$hotel_id = $this->getHotelId();


		$h = Hotel::find($hotel_id);


		// elimino il listino se è più vecchio di 2 anni
		$last_period = $h->listiniMinMax()->orderByRaw("periodo_dal desc")->first();
		if(!is_null($last_period))
			{
			if( !is_null($last_period->periodo_al) && $last_period->periodo_al->lessThan( Carbon::today()->subYears($years) ) )
				{
					$h->listiniMinMax()->delete();
				}
			}


		/*
      http://stackoverflow.com/questions/2051602/mysql-orderby-a-number-nulls-last

      MySQL has an undocumented syntax to sort nulls last. Place a minus sign (-) before the column name and switch the ASC to DESC:
      */
		$listini = $h->listiniMinMax()->orderByRaw("-periodo_dal desc")->get();

		if ($listini->isEmpty()) {

			/////////////////////////////
			// CREAZIONE LISTINO VUOTO //
			/////////////////////////////

			$this->createEmptyListino($hotel_id);

			$listini = $h->listiniMinMax;
		}


		$prefix = ['prezzo_sd', 'prezzo_bb', 'prezzo_mp', 'prezzo_pc', 'prezzo_ai'];
		$columns = [];

		foreach ($prefix as $pre) {
			foreach (['min', 'max'] as $suffix) {
				$columns[] = $pre . '_' . $suffix;
			}
		}

		return view('admin.listini-minmax_edit', compact("listini", "columns"));

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{


	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$type = $request->get('type');

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		if ($type == 'top') {
			// trovo il listino con la data periodo_dal minore
			$listino = ListinoMinMax::primoPeriodo($hotel_id);

			$al = is_null($listino->periodo_dal) ? null : $listino->periodo_dal->subDay();
			$dal = is_null($listino->periodo_dal) ? null : $listino->periodo_dal->subWeek();

			$this->createEmptyListino($hotel_id, $dal, $al);

			return $this->index();

		}
		elseif ($type == 'bottom') {
			// trovo il listino con la data periodo_dal minore
			$listino = ListinoMinMax::ultimoPeriodo($hotel_id);

			$dal = is_null($listino->periodo_al) ? null : $listino->periodo_al->addDay();
			$al = is_null($listino->periodo_al) ? null : $listino->periodo_al->addWeek();

			$this->createEmptyListino($hotel_id, $dal, $al);

			return $this->index();
		}
		else {
			$this->createEmptyListino($hotel_id);
			return $this->index();
		}


		


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

		

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{

		

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		////////////////////////////////////////////////////////////////////
		// VERIFICO CHE IL LISTINO SIA EFFETTIVAMENTE DELL'HOTEL CONNESSO //
		////////////////////////////////////////////////////////////////////
		$listino = ListinoMinMax::findOrFail($id);


		if ($listino->hotel_id == $this->getHotelId()) {
			$listino->delete();
		}

		

		return $this->index();

	}


	public function SalvaPrezzoAjax(Request $request)
	{
		// tipo <id_listino|nome_colonna>
		$id = $request->get('id');

		list($id_listino, $column) = explode('|', $id);

		////////////////////////////////////////////////////////////////////
		// VERIFICO CHE IL LISTINO SIA EFFETTIVAMENTE DELL'HOTEL CONNESSO //
		////////////////////////////////////////////////////////////////////
		$listino = ListinoMinMax::findOrFail($id_listino);


		if ($listino->hotel_id != $this->getHotelId()) {
			echo "error";
		}
		else {

			Utility::clearCacheHotel($listino->hotel_id);

			// valore inserito nella cella
			$value = $request->get('value');

			if ($value == '' || $value == '&nbsp;&nbsp;&nbsp;') {
				$to_return = '&nbsp;&nbsp;&nbsp;';
				$to_insert = floatval($value);
			}
			else {
				$value = str_replace(',', '.', $value);
				$to_return = floatval($value);
				$to_return = str_replace('.', ',', $to_return);
				$to_insert = $to_return;
			}

			// ATTENZIONE: la classe ha MUTATOR e ACCESSOR che trasformano il '.'' in ',' e viceversa
			// per visualizzare a salvare nel DB
			$listino->fill([$column => $to_insert])->save();

			
			echo $to_return;

		}

	}


	public function SalvaDataAjax(Request $request)
	{

		$id_listino = $request->get('listino_id');

		////////////////////////////////////////////////////////////////////
		// VERIFICO CHE IL LISTINO SIA EFFETTIVAMENTE DELL'HOTEL CONNESSO //
		////////////////////////////////////////////////////////////////////
		$listino = ListinoMinMax::findOrFail($id_listino);


		if ($listino->hotel_id != $this->getHotelId()) {
			$msg = "error";
		}
		else {
			$periodo_dal = $request->get('periodo_dal');
			$periodo_al = $request->get('periodo_al');

			list($d, $m, $y) = explode('/', $periodo_dal);
			$periodo_dal_carbon = Carbon::createFromDate($y, $m, $d);


			list($d, $m, $y) = explode('/', $periodo_al);
			$periodo_al_carbon = Carbon::createFromDate($y, $m, $d);


			if ($periodo_al_carbon->gt($periodo_dal_carbon)) {

				/* AGGIORNO LE DATE */
				$listino->fill(['periodo_dal' => $periodo_dal_carbon, 'periodo_al' => $periodo_al_carbon])->save();

				$msg = "ok";
			} else {
				$msg = "La data fine deve essere successiva a quella di inizio!";
			}

			$risp = array(
				'inizio' => $periodo_dal,
				'fine' => $periodo_al,
				'id_listino' => $id_listino,
				'msg' => $msg
			);

			echo json_encode($risp);

		}
		

	}


	public function ModificaStato(Request $request)
	{

		$type = $request->get('type');

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);

		if ($type == 'disattiva_listino') {
			ListinoMinMax::where('hotel_id', $hotel_id)->update(['attivo' => 0]);
		}
		else {
			ListinoMinMax::where('hotel_id', $hotel_id)->update(['attivo' => 1]);
		}

		
		return $this->index();

	}


}
