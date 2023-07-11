<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\VetrinaRequest;
use App\SlotVetrina;
use App\TipoVetrina;
use App\Vetrina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SessionResponseMessages;

class VetrineController extends AdminBaseController
{



	private function _index_vetrine($vetrine)
	{
	$data = ["records" => $vetrine];

	return view('admin.vetrine_index', compact("data"));

	} 

	public function index_principali()
	{	
		
		$vetrine = Vetrina::orderBy('nome')->principale()->get();

		return self::_index_vetrine($vetrine);

	}

	public function index_limitrofe()
	{	
		
		$vetrine = Vetrina::orderBy('nome')->limitrofe()->get();

		return self::_index_vetrine($vetrine);

	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$vetrine = Vetrina::orderBy('nome')->get();

		return self::_index_vetrine($vetrine);
		

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$data = [];
		$data["tipo"] = TipoVetrina::pluck("nome", "id")->toArray();
		
		return view('admin.vetrine_form', $data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(VetrinaRequest $request)
	{
		$vetrina = Vetrina::create($request->all());
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");


		return SessionResponseMessages::redirect("admin/vetrine", $request);
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
	public function edit($id)
	{
		$data = [];

		$vetrina = Vetrina::findOrFail($id);

		$data['vetrina'] = $vetrina;

		$data["tipo"] = TipoVetrina::pluck("nome", "id")->toArray();

		$data['showButtons'] = 1;
		return view('admin.vetrine_form', $data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, VetrinaRequest $request)
	{
		$vetrina = Vetrina::findOrFail($id);
		$vetrina->update($request->all());


		SessionResponseMessages::add("success", "Modifiche effettuate con successo.");

		return SessionResponseMessages::redirect("admin/vetrine", $request);

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request)
	{
		SlotVetrina::where('vetrina_id', $id)->delete();
		Vetrina::destroy($id);


		SessionResponseMessages::add("success", "Vetrina eliminata con successo.");

		return SessionResponseMessages::redirect("/admin/vetrine", $request);
	}


}
