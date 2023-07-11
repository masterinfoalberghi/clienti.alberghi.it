<?php

namespace App\Http\Controllers\Admin;

use Langs;
use App\Hotel;
use App\Utility;
use Illuminate\Http\Request;
use SessionResponseMessages;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TrattamentiController extends AdminBaseController
{
  public function index(Request $request, $hotel_id = null)
    {
		
		if(is_null($hotel_id))
			{
			$hotel_id = $this->getHotelId();
			$hotel = Hotel::find($hotel_id);
			}
		else
			{
			/* AUTOMATICAMENTE MI IDENTIFICO CON L'HOTEL CHE HO PASSATO !!!!*/
			$hotel = Hotel::find($hotel_id);
			
			$ui_editing_hotel = "$hotel->id $hotel->nome";

			Auth::user()->setCommercialeUiEditingHotelId($hotel_id);
			Auth::user()->setUiEditingHotelId($hotel_id);
			Auth::user()->setUiEditingHotel($ui_editing_hotel);
            Auth::user()->setUiEditingHotelPriceList($hotel->hide_price_list);
			}
		
		
		$note = [];
		$nomi_trattamenti = [];
		foreach (['ai', 'pc', 'mp_spiaggia' , 'mp', 'bb_spiaggia', 'bb', 'sd_spiaggia' , 'sd'] as $field) 
      {
			$column1 = 'trattamento_'.$field;

			if($hotel->$column1)
				{
				foreach (Utility::linguePossibili() as $lang_id) 
					{
					$column2 = 'note_'.$field.'_'.$lang_id;
					$note[$lang_id][$column2] = $hotel->$column2;
					$nomi_trattamenti[$column2] = Utility::getTrattamentiNomi()[$column1];
					
					$column_altro = 'note_altro_'.$lang_id;
					$note_altro[$lang_id][$column_altro] = $hotel->$column_altro;


					$column_altro_trattamento = 'altro_trattamento_' . $lang_id;
					$altro_trattamento[$lang_id][$column_altro_trattamento] = $hotel->$column_altro_trattamento;


					}
				}
			}
		
		//dd($note);
		
		if(Auth::user()->hasRole("hotel")) {
			SessionResponseMessages::add("warning", "Contattaci per modificare i trattamenti!");
			return SessionResponseMessages::redirect("admin", $request);
		}

		Auth::user()->hasRole("hotel") ? $is_hotel = 1 : $is_hotel = 0;

		return view('admin.trattamenti_index', compact('note', 'nomi_trattamenti','note_altro', 'altro_trattamento','is_hotel'));
		
		}
		
	function store (Request $request) 
    {
		$hotel_id = $this->getHotelId();
		$hotel = Hotel::find($hotel_id);

		if($request->has('salvatraduci'))
			{

			foreach (['ai', 'pc', 'mp', 'bb', 'sd', 'mp_spiaggia', 'bb_spiaggia', 'sd_spiaggia','altro', 'altro_trattamento'] as $field) 
      	{
				
				$field != 'altro_trattamento' ? $param = 'note_'.$field : $param = $field;
				
				if($request->has($param.'_it'))
					{
						$sorgente = $request->get($param.'_it');

						if($sorgente != '')
							{

							foreach (Langs::getAll() as $lang) 
								{	
								if ($lang != 'it')
									{
									$request->merge([
										$param.'_'.$lang =>  Utility::translate($sorgente, $lang)
									]);
									}
								}

							}
					}
				
				}

			}
		
		$hotel->update($request->all());
		
		if($hotel->wasChanged())
			{
			if(Auth::user()->hasRole(["admin", "operatore"]))
				{
				$hotel->trattamenti_moderati = 1;
				}
			else
				{
				$hotel->trattamenti_moderati = 0;
				}
			$hotel->save();
			SessionResponseMessages::add("success", "Modifica effettuato con successo.");
			}
		else
			{
			if(Auth::user()->hasRole(["admin", "operatore"]))
				{
				$hotel->trattamenti_moderati = 1;
				$hotel->save();
				SessionResponseMessages::add("success", "Modifica effettuato con successo.");
				}
			else
				{
				SessionResponseMessages::add("success", "NESSUNA reale modifica effettuata!");
				}
			}

		if ($request->has('invia_mail') && $request->get('invia_mail') == 1) 
			{
			$hotel->notifyMeTrattamenti();
			SessionResponseMessages::add("success", "Una notifica è stata inviata all'utente");
			}
		
		
		return SessionResponseMessages::redirect("admin/trattamenti", $request);

		}   
 
}
