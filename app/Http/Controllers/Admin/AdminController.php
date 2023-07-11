<?php

namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\Utility;
use App\HotelTagModificati;
use Illuminate\Http\Request;
use SessionResponseMessages;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use GrahamCampbell\Markdown\Facades\Markdown;
use App\Http\Requests\Admin\SelezionaHotelRequest;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AdminController extends AdminBaseController
{

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('admin.home');
	}
	
	public function selezionaHotel(SelezionaHotelRequest $request)
	{

		/*
        * Arriva nel formato
        * "17 Hotel Sabrina"
        * quindi passandolo per il casting int ottengo: 17
        */
		$hotel_id = (int)$request->input("ui_editing_hotel");
		$hotel = Hotel::find($hotel_id);
		$ui_editing_hotel = "$hotel->id $hotel->nome";
		Auth::user()->setUiEditingHotelId($hotel_id);
		Auth::user()->setUiEditingHotel($ui_editing_hotel);
		Auth::user()->setUiEditingHotelPriceList($hotel->hide_price_list);
		SessionResponseMessages::add("success", "Hai selezionato l'hotel: $ui_editing_hotel");
		return SessionResponseMessages::redirect('admin/home', $request);



	}


	public function commercialeSelezionaHotel(SelezionaHotelRequest $request)
	{

		/*
        * Arriva nel formato
        * "17 Hotel Sabrina"
        * quindi passandolo per il casting int ottengo: 17
        */
		$hotel_id = (int)$request->input("ui_editing_hotel");
		$hotel = Hotel::find($hotel_id);
		$ui_editing_hotel = "$hotel->id $hotel->nome";
		Auth::user()->setCommercialeUiEditingHotelId($hotel_id);
		Auth::user()->setUiEditingHotel($ui_editing_hotel);
        Auth::user()->setUiEditingHotelPriceList($hotel->hide_price_list);
		SessionResponseMessages::add("success", "Hai selezionato l'hotel: $ui_editing_hotel. Come commerciale puoi solo taggare le foto di questa struttura !");
		return SessionResponseMessages::redirect('admin', $request);



	}

	
	public function selezionaHotelDaIdAjax(Request $request, $id_offerta = 0)
	{

		/*
         * Arriva nel formato
         * "17 Hotel Sabrina"
         * quindi passandolo per il casting int ottengo: 17
         */
		$hotel_id = (int)$request->input("id");
		$hotel = Hotel::find($hotel_id);
		$ui_editing_hotel = "$hotel->id $hotel->nome";
		Auth::user()->setUiEditingHotelId($hotel_id);
		Auth::user()->setUiEditingHotel($ui_editing_hotel);
        Auth::user()->setUiEditingHotelPriceList($hotel->hide_price_list);
		return ($id_offerta == 0) ?  'ok' : $id_offerta;

	}

	public function saveHotelTagModificatiAjax(Request $request)
		{
		$hotel_id = (int)$request->input("id");
		

		////////////////////////////////////////////////////////////////////////////////////
		// firstOrFail: Execute the query and get the first result or throw an exception. //
		// inserisco un record solo se non c'è già quell'hotel_id													//
		////////////////////////////////////////////////////////////////////////////////////
	

   $ris = HotelTagModificati::where('hotel_id', $hotel_id)->first();

   if(is_null($ris))
   	{
   	$crm_response = [];

		$hotel = Hotel::find($hotel_id);

		$utente = Auth::user();

		$hotel_tag_arr =  [
  		    	'hotel_id' => $hotel_id, 
  		    	'hotel' => $hotel->nome,
  		    	'user_id' => Auth::id(),
  		    	'utente' => $utente->username,
  		   	];
	
		///////////////////////////////////////////////////////////////////
		// chiamata al crm che mi restituisce il commerciale del cliente //
		///////////////////////////////////////////////////////////////////
		$crm_response = Utility::getCommercialeFromCrm($hotel_id);

		if (count($crm_response)) 
			{
				$hotel_tag_arr['commerciale'] =  $crm_response['commerciale'];
			}

    HotelTagModificati::create(	
  		   $hotel_tag_arr
  		);
   	}
	
		
		echo 'ok';
		}


	public function cache(Request $request)
	{

		Cache::flush();
		SessionResponseMessages::add("success", "Cache eliminata con successo");
		return SessionResponseMessages::redirect('admin', $request);

	}


	public function featured()
	{

		$featured = File::get(app_path().'/../docs/sviluppi.md');
		$featured = Markdown::convertToHtml($featured); // <p>foo</p>
		return view('admin.featured', compact('featured'));

	}


	public function hotfix()
	{
		$hotfix   = File::get(app_path().'/../docs/hotfix.md');
		$hotfix = Markdown::convertToHtml($hotfix); // <p>foo</p>
		return view('admin.hotfix', compact('hotfix'));
	}




}
