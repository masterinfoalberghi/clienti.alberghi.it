<?php

namespace App\Http\Controllers\Admin;

use App\Coupon;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CouponController;
use App\Http\Requests;
use App\Http\Requests\Admin\CouponScontoRequest;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use SessionResponseMessages;

class CouponScontoController extends AdminBaseController
{


	



	public function __construct()
	{

		$this->middleware('couponAttivo', ['only' => ['create']]);

	}


	private function _creaCoupon($request, &$coupon)
	{

		$coupon->attivo = 1;
		$coupon->hotel_id = $this->getHotelId();
		$coupon->valore = $request->get('valore');
		$coupon->periodo_dal = Utility::getCarbonDate($request->get('periodo_dal'));
		$coupon->periodo_al = Utility::getCarbonDate($request->get('periodo_al'));
		$coupon->durata_min = $request->get('durata_min');
		$coupon->adulti_min = 2;
		$coupon->numero = $request->get('numero');
		$coupon->referente = $request->get('referente');

		$coupon->save();
	}


	private function _archiviaCoupon(&$coupon)
	{
		$coupon->attivo = 0;
		$coupon->data_chiusura = Carbon::now();

		$coupon->save();
	}



	protected function LoadOwnedRecords()
	{

		// É importante che vengano solo caricati i record delle offerte appartenenti all'hotel corrente

		$hotel_id = $this->getHotelId();

		return Coupon::where("hotel_id", $hotel_id);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$hotel_id = $this->getHotelId();
		$coupon = Coupon::where('hotel_id', $hotel_id)->orderBy('created_at', 'desc')->get();
		return view('admin.coupon_index', compact('coupon'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$data = [];

		return view('admin.coupon_form', $data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CouponScontoRequest $request)
	{

		/* INSERIMENTO COUPON*/

		// nuova Coupon
		$coupon = new Coupon;

		/* coupon passato per variabile quindi da adesso $coupon è quello salvato e associato all'hotel !!!*/
		$this->_creaCoupon($request, $coupon);
		

		/* INVIO MAIL ALL'ALBERGATORE */
		$cliente = Hotel::find($this->getHotelId());


		$couponController = new CouponController();

		/*
    Genero all'albergatore un coupon d'esempio con fake codice e fake mail
     */
		$codice = '12ABCXYZ';
		$email_utente = 'mia_mail@mio_dominio.com';
		$filename = $couponController->crea_pdf_cliente($coupon, $codice, $email_utente);

		$couponController->invia_coupon_esempio_cliente($filename, $coupon, $codice, $email_utente);

		SessionResponseMessages::add("success", "Inserimento effettuato con successo. ATTENZIONE: Una mail con il coupon &egrave; stata spedita al tuo indirizzo di posta elettronica.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo.");
		return SessionResponseMessages::redirect("admin/coupon", $request);

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
		$coupon = $this->LoadOwnedRecords()
		->where("id", $id)
		->firstOrFail();


		return view('admin.coupon_form', compact('coupon'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(CouponScontoRequest $request, $id)
	{

		//  QUESTO COPUON VA ARCHIVIATO
		$coupon = $this->LoadOwnedRecords()
		->where("id", $id)
		->firstOrFail();

		// QUESTO COUPON VA CREATO
		$new_coupon = new Coupon;

		/* TRANSACTION */
		DB::beginTransaction();

		$status = 'ok';

		try
		{
			// coupon da archiviare
			$this->_archiviaCoupon($coupon);

			// coupon da creare
			$this->_creaCoupon($request, $new_coupon);
		}


		catch (Exception $e)
		{
			$status = 'ko';
			DB::rollback();
		}


		
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo.");


		if ($status == 'ok') {

			DB::commit();

			/*
        Genero all'albergatore un coupon d'esempio con fake codice e fake mail
         */

			$codice = '12ABCXYZ';
			$email_utente = 'mia_mail@mio_dominio.com';

			$couponController = new CouponController();

			$filename = $couponController->crea_pdf_cliente($new_coupon, $codice, $email_utente);

			$couponController->invia_coupon_esempio_cliente($filename, $new_coupon, $codice, $email_utente);

			SessionResponseMessages::add("success", "Modifica effettuata con successo. ATTENZIONE: Una mail con il coupon &egrave; stata spedita al tuo indirizzo di posta elettronica.");
			return SessionResponseMessages::redirect("admin/coupon", $request);
		}
		else {
			SessionResponseMessages::add("error", "Modifiche abortite. ATTENZIONE: Si è verificato un errore grave!!.");
			return SessionResponseMessages::redirect("admin/coupon", $request);
		}

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo.");

	}


	public function archivia(Request $request, $id)
	{

		$coupon = $this->LoadOwnedRecords()
		->where("id", $id)
		->firstOrFail();

		$this->_archiviaCoupon($coupon);

		

		SessionResponseMessages::add("success", "Coupon archiviato con successo.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata con successo.");
		return SessionResponseMessages::redirect("/admin/coupon", $request);

	}


	public function generati(Request $request, $id)
	{

		$coupon = $this->LoadOwnedRecords()
		->where("id", $id)
		->firstOrFail();

		$coupon_generati = $coupon
		->generati()
		->with(['utente'])
		->orderBy('created_at', 'desc')
		->get();

		return view('admin.coupon_generati', compact('coupon', 'coupon_generati'));

	}


}
