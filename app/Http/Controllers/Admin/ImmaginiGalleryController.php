<?php
/**
 * ImmaginiGalleryController
 *
 * @author Info Alberghi Srl
 * 
 */

namespace App\Http\Controllers\Admin;

use Log;
use File;
use App\Hotel;
use Validator;
use App\Utility;
use App\GruppoServizi;
use App\Http\Requests;
use App\ImmagineGallery;
use Illuminate\Http\Request;
use SessionResponseMessages;
use Illuminate\Http\Response;
use App\ImmagineGalleryLingua;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\library\ImageVersionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImmaginiGalleryController extends AdminBaseController
{

	const MAX_LEN_CAPTION = 60;
	const GALLERY_PATH = "images/gallery";    
	
	/* ------------------------------------------------------------------------------------
	 * METODI PRIVATI
	 * ------------------------------------------------------------------------------------ */
	

	



	/**
	 * Esegue il resize delle foto in base a ImmagineGallery::getImagesVersions()
	 * 
	 * @access private
	 * @param Hotel $cliente
	 * @param Request $request
	 * @return void
	 */
	 
	private function _getResizedImages(Hotel $cliente, Request $request)
	{
		
		/**
		 * Validazione
		 */
		 
		$rules = array('file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3000');

		$messages = [
			'image' => 'Il file selezionato deve essere un\'immagine! ',
			'max' => 'La dimensione massima del file deve essere :max KB',
		];

		$validation = Validator::make($request->all(), $rules, $messages);
		
		/**
		 * Restituisco un errore in caso di fallimento
		 */
		
		if ($validation->fails()) {
			
			//dd($validation->errors()->all());
			
			$errors = $validation->errors()->all();
			$msg = "";
			
			foreach ($errors as $error)
				$msg .= $error . "<br>";
			
			return ['msg' => rtrim($msg, '<br>')];
			
		}
		
		if (is_null($request->file('file'))) return ['msg' => 'file nullo'];
		
		/**
		 * Eseguo il resize
		 */
		
		try
		{

			$imagev = new ImageVersionHandler;

			if ($file = $request->file('file')) {

				$uploaded_filename = File::name($file->getClientOriginalName());
				$cliente_nome = Utility::stripLettereAccentate($cliente->nome);

				$imagev->setImageBasename( str_replace([' ', '\''], '_', "{$uploaded_filename}_{$cliente_nome}_".uniqid()) );

                //$backup_path = storage_path('original_images/gallery');
                $backup_path =  "/original_images/gallery/" . $cliente->id . "/";
                $imagev->enableOriginalBackup($backup_path);
                $imagev->loadOriginalFromUpload($request->file('file'));
				SessionResponseMessages::add("info", "Copia backup immagine originale salvata in $backup_path/".$imagev->getImageFilename());
				
				/**
				 * Processo le immagini
				 */

				foreach (ImmagineGallery::getImagesVersions() as $v) {
                 
					$imagev->process($v["mode"], $v["basedir"] . $cliente->id , $v["width"], $v["height"]);
					SessionResponseMessages::add("info", "Creata variante immagine {$v["width"]}x{$v["height"]} ({$v["mode"]})");
					
				}

				return $imagev->getImageFilename();
				
			}
			
		} catch (\Exception $e) {
			
			config('app.debug_log') ? Log::emergency("\n".'---> Errore UPLOAD IMMAGINI GALLERY: '.$e->getMessage().' <---'."\n\n") : "";
			return ['msg' => 'Errore upload del file:'. $e->getMessage()];
			
		}

	}


	/**
	 * Ritorna il Query Builder per il model ImmagineGallery pre-filtrato per l'hotel di appartenenza...
	 * forse non sono stato chiaro ma è molto importante per la sicurezza,
	 * altrimenti un malevolo potrebbe accedere le offerte di un altro
	 *
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	 
	protected function LoadOwnedRecords($locale = 'it')
	{

		/**
		 * É importante che vengano solo caricati i record delle offerte appartenenti all'hotel corrente
		 */

		$hotel_id = $this->getHotelId();

		return ImmagineGallery::with([
				'immaginiGallery_lingua' => function($query) use ($locale) {
					$query
					->where('lang_id', '=', $locale);
				}
			])->where("hotel_id", $hotel_id)->orderBy('position');
			
	}
	
	
  
	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( VIEWS )
	 * ------------------------------------------------------------------------------------ */

	 

	/**
	 * Vista principale
	 *
	 * @return Response
	 */
	 
	public function index()
	{		
		$locale = $this->getLocale();
		$immagini_gallery = $this->LoadOwnedRecords($locale)->get();
		$data = ["records" => $immagini_gallery, "limit" => Self::MAX_LEN_CAPTION, "locale" => $locale];
		
		$hotel_id = $this->getHotelId();
		$cliente = Hotel::find($hotel_id);
		$foto_listing = $cliente->listing_img;

		/**
		 * devo poter associare un gruppo di ricerca per associare l'immagine al relativo listing
		 * l'associazione riguarda l'immagine non la parte in lingua (tag), quindi la mostro solo in italiano
		 */
		
		$gruppi = GruppoServizi::pluck('nome', 'id');
		$gruppi = array('0' => '') + $gruppi->toArray();

		$data["gruppi"] = $gruppi;
		$data["foto_listing"] = $foto_listing;
		$data['hotel_id'] = $hotel_id;
		
		return view('admin.immagini-gallery_index_grid', compact("data"));
		
	}


	/**
	 * Vista modifica
	 *
	 * @return Response
	 */
	 
	public function create()
	{
		if (Auth::check()) {
			Log::emergency('Sono loggato in ADMIN');
		} else {
			Log::emergency('NON Sono loggato in ADMIN');
		}
		$hotel_id = $this->getHotelId();
		$hotel = Hotel::find($hotel_id);
		return view('admin.immagini-gallery_edit', compact("hotel"));
		
	}


	/**
	 * Carico le immagini sul server
	 *
	 * @param Request $request
	 * @return Response
	 */
	 
	public function uploadImage(Request $request)
	{

		$hotel_id = $this->getHotelId();
		$cliente = Hotel::find($hotel_id);
		Utility::clearCacheHotel($hotel_id);
		
		$immagine = $this->_getResizedImages($cliente, $request);
		
		if (is_array($immagine)) {
			
			$error_msg = $immagine['msg'];
			return response()->json($error_msg, 400);
			
		} else {
			
			DB::transaction(function() use ($hotel_id, $immagine){
                $pos = ImmagineGallery::where('hotel_id', $hotel_id)->max('position');
                if (is_null($pos)) {
                    $pos = 0;
                    $listing = 1;
                } else {
                    $listing = 0;
                }
                $pos++;
                ImmagineGallery::create(['hotel_id' => $hotel_id, 'foto' => $immagine, 'position' => $pos, 'listing_app' => $listing]);
			});

			return response()->json('OK', 200);
			
		}

	}

	/**
	 * DOPO che ha accettato la policy per la modifica dei titolo delle foto
	 * VIsualizza le foto del cliente con la possibilità solo di modificare i caption
	 *
	 * @return Response
	 */
	 
	public function modificaCaptionHotel()
	{
		
		$locale = $this->getLocale();
		$hotel_id = $this->getHotelId();
		$cliente = Hotel::find($hotel_id);
		Utility::clearCacheHotel($hotel_id);
		
		if (is_null($cliente->accettazioneCaption) && false) {
			
			return view('admin.immagini-gallery_accetta_policy');
			
		} else {
			
			$immagini_gallery = $this->LoadOwnedRecords($locale)->get();
			$gruppi = GruppoServizi::pluck('nome', 'id');
			$data = ["records" => $immagini_gallery, "limit" => Self::MAX_LEN_CAPTION, "locale" => $locale, "gruppi" => $gruppi];
			return view('admin.immagini-gallery_index_grid_cliente', compact("data"));
			
		}

	}


	/**
	 * DOPO che ha accettato la policy per la modifica dei titolo delle foto
	 * VIsualizza le foto del cliente con la possibilità solo di modificare i caption
	 *
	 * @return Response
	 */
	 
	public function commercialeModificaCaptionHotel()
	{

		$locale = $this->getLocale();
		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);
		$cliente = Hotel::find($hotel_id);
		

		if (is_null($cliente->accettazioneCaption) && false) {
			return view('admin.immagini-gallery_accetta_policy');
		} else {
		
			$immagini_gallery = $this->LoadOwnedRecords($locale)->get();
			$data = ["records" => $immagini_gallery, "limit" => Self::MAX_LEN_CAPTION, "locale" => $locale];
			$commerciale = 1;
			return view('admin.immagini-gallery_index_grid_cliente', compact("data","commerciale"));
		}

	}


	/**
	 * Cancella una fotografia
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 
	public function destroy($id = 0, Request $request)
	{

		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);
		$h = Hotel::find($hotel_id);

		// devo verificare se l'immagine che cancello è quella del listing dell'hotel
		$img = optional(ImmagineGallery::find($id))->foto;

		if($h->listing_img == $img)
			{
			$h->listing_img = '';
			$h->save();	
			}

		$h->deleteGallery($id);
		
		SessionResponseMessages::add("success", "L'immagine è stata eliminata.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata.");
		return SessionResponseMessages::redirect("admin/immagini-gallery", $request);
		
	}


	/**
	 * Canella tuaa la galleria
	 * 
	 * @access public
	 * @param Request $request
	 * @return void
	 */
	 
	public function deleteAll(Request $request)
	{
		
		$hotel_id = $this->getHotelId();
		$h = Hotel::find($hotel_id);

		$h->listing_img = '';
		$h->save();	
		
		$h->deleteGallery();
		Utility::clearCacheHotel($hotel_id);

		SessionResponseMessages::add("success", "La gallery è stata eliminata.");
		SessionResponseMessages::add("success", "La cache è stata aggiornata.");
		
		return SessionResponseMessages::redirect("admin/immagini-gallery", $request);
		
	}


	/**
	 * Ordinamento AJAX della galleria
	 * @return string
	 */
	 
	public function orderAjax(Request $request)
	{
		
		$recordsArray = $request->get('recordsArray');

		foreach ($recordsArray as $position => $id)
			ImmagineGallery::where('id', $id)->update(['position' => $position]);

		
		echo "ok";
		
	}


	/**
	 * Approva i titoli
	 * 
	 * @access public
	 * @param Request $request
	 * @return void
	 */
	 
	public function approveTitles(Request $request)
	{
		$immagini_gallery = $this->LoadOwnedRecords()->get();

		foreach ($immagini_gallery as $immagine)
			if (!$immagine->immaginiGallery_lingua->isEmpty())
				ImmagineGalleryLingua::where('id', $immagine->immaginiGallery_lingua->first()->id)->update(['moderato' => true]);

		
		SessionResponseMessages::add("success", "La cache è stata aggiornata.");
		
		return redirect("/admin/immagini-gallery");

	}


	/**
	 * Notfica titoli approvati
	 * 
	 * @access public
	 * @param Request $request
	 * @return void
	 */
	 
	public function notifyApprovedTitles(Request $request)
	{
		
		$hotel_id = $this->getHotelId();
		$hotel = Hotel::find($hotel_id);
		Utility::clearCacheHotel($hotel_id);
		$hotel->notifyMeApprovedTitles();

		SessionResponseMessages::add("success", "Una notifica è stata inviata all'utente");
		return SessionResponseMessages::redirect("admin/immagini-gallery", $request);
		
	}


	/**
	 * Assegna un gruppo alle immagini
	 * 
	 * @access public
	 * @param Request $request
	 * @return void
	 */
	 
	public function SetGruppoImmagineAjax(Request $request)
	{			
	
		$id_gruppo = $request->get('id_gruppo');
		$id_immagine = $request->get('id_immagine');
		$nome_immagine = $request->get('nome_immagine');
		$hotel_id = $this->getHotelId();
		Utility::clearCacheHotel($hotel_id);
		$error = false;
		
		/**
		 * Verifico se per questo hotel c'è già un'immagine per questo gruppo
		 */
		
		if($id_gruppo == 0)
			$immagineGruppo = collect([]);
		else
			$immagineGruppo = ImmagineGallery::where('hotel_id',$hotel_id)->where('gruppo_id', $id_gruppo)->get();
		
		/**
		 * Controllo che non ci sia già una immagine associata
		 */

		if ($immagineGruppo->count()) {

			echo "C'è già un'immagine associata a questo gruppo !";
			$error = true;

		}

        /**
		 * Controllo che esistena le immagini nei formati richiesti, 
		 * altrimenti le creo 
		 */

        // $path = config("app.cdn_s3") . "/" . self::GALLERY_PATH . "/220x148/" . $hotel_id . "/" . $nome_immagine;
        // if (!File::exists($path)) { Self::createImgMissing($id_immagine, "220x148"); }
		
		// $path =  config('app.cdn_s3') . '/' . self::GALLERY_PATH . "/360x200/" . $hotel_id . "/" . $nome_immagine;
		// if (!File::exists($path)) { Self::createImgMissing($id_immagine, "360x200"); }
			
		if (!$error) {

			ImmagineGallery::where('id', $id_immagine)->update(['gruppo_id' => $id_gruppo]);		
			echo "ok";

		} else
			echo "no";
		
			
	}


	/**
	 * Crea la caption immagini
	 *
	 * @param Request $request
	 * @return string $to_return
	 */
	 
	public function CreaCaptionAjax(Request $request)
	{

		$locale = $request->get('locale');
		$id = $request->get('id');
		$value = $request->get('value');
		$value = substr($value, 0, Self::MAX_LEN_CAPTION);
		
		if (trim($value) != '') {

			$immagineGalleryLang = ImmagineGalleryLingua::firstOrNew(array('master_id' => $id, 'lang_id' => $locale));
			$immagineGalleryLang->caption = $value;

			if (Auth::user()->hasRole(["admin", "operatore"])) {
				
				$immagineGalleryLang->moderato = true;
				$immagineGalleryLang->save();
				
			} else {
				
				$immagineGalleryLang->moderato = false;
				$immagineGalleryLang->save();

				/**
				 * AGGIORNO LA COLONNA UPDATE DEL PDARE
				 */
				 
				$immagineGalleryLang->immagineGallery->updated_at = $immagineGalleryLang->updated_at;
				$immagineGalleryLang->immagineGallery->save();
				
			}


			if ($value == '' || $value == '&nbsp;&nbsp;&nbsp;')
				$to_return = '';
			else
				$to_return = $value;

			/**
			 * FACCIO TRADURRE DA GOOGLE API TRANSLATE I TAG !!
			 */ 
			 
			if ($locale == 'it')
				foreach (Utility::linguePossibili() as $lingua) 
					if ($lingua != 'it') {
						$immagineGalleryLang = ImmagineGalleryLingua::firstOrNew(array('master_id' => $id, 'lang_id' => $lingua));
						$immagineGalleryLang->caption = Utility::translate($value, $lingua);
						$immagineGalleryLang->moderato = false;
						$immagineGalleryLang->save();
						
						/**
						 * AGGIORNO LA COLONNA UPDATE DEL PDARE
						 */
				 
						$immagineGalleryLang->immagineGallery->updated_at = $immagineGalleryLang->updated_at;
						$immagineGalleryLang->immagineGallery->save();				
					}

		} else {
			
			if ($locale == 'it')
				ImmagineGalleryLingua::where(array('master_id' => $id))->delete();
			else
				ImmagineGalleryLingua::where(array('master_id' => $id,'lang_id' => $locale))->delete();	

			$to_return = '';
		}
		
		

		echo $to_return;
	}


	/**
	 * Cancella la caption.
	 * 
	 * @access public
	 * @param Request $request
	 * @return void
	 */
	 
	public function DelCaptionAjax(Request $request)
	{
		
		$id = $request->get('id');
		ImmagineGalleryLingua::where(array('master_id' => $id, 'lang_id' => 'it'))->delete();
		

		echo "ok";

	}



	/*
	* cancella le immagini della gallery selezionare con checkbox
	*/
	function DelCheckedAjax(Request $request)
	{
		$ids = $request->get('ids');
		$hotel_id = $request->get('hotel_id');
		$hotel = Hotel::find($hotel_id);

		foreach ($ids as $key => $id) {
				
                // devo verificare se l'immagine che cancello è quella del listing dell'hotel
				$img = optional(ImmagineGallery::find($id))->foto;

				if($hotel->listing_img == $img) {
					$hotel->listing_img = '';
					$hotel->save();	
                }

				$hotel->deleteGallery($id);
		};

		$key++;
		echo $key;
		
	}

	

	/**
	 * Crea una immagine mancante.
	 * 
	 * @access public
	 * @return void
	 */
	 
	public function createImgMissing($id, $formato) {
		
		$img = ImmagineGallery::find($id);
		
		if ($path_img = $img->getImg("800x538")) {
            
            $hotel_id = $this->getHotelId();
			$file = base_path('static/' . $path_img);
			$imagev = new ImageVersionHandler;
			$uploaded_filename = File::name($file);
			$imagev->setImageBasename($uploaded_filename);
			$imagev->loadOriginalFromPath($file);
			
			foreach (ImmagineGallery::getImagesVersions() as $v) {
				$imagev->process($v["mode"], $v["basedir"] . $hotel_id, $v["width"], $v["height"]);
			}
			
			echo "Immagini ricreate" . PHP_EOL;
			
		} else {
			
			echo "Immagine originale mancante, ricaricare" . PHP_EOL;
			
		}
		
	}
	
	/**
	 * Crea l'immagine del listing a partire dalla prima immagine della gallery (position = min(position)).
	 * 
	 * @access public
	 * @param Request $request
	 * @return void
	 */
	
	
	// public function createImgListing($id=0, Request $request)
	// {

	// 	/**
	// 	 * Trovo l'hotel
	// 	 */
		 
	// 	$hotel_id = $this->getHotelId();
	// 	Utility::clearCacheHotel($hotel_id);

	// 	$hotel = Hotel::find($hotel_id);
	// 	$img = ImmagineGallery::find($id);

    //     dd($img);

	// 	if ($path_img = $img->getImg("800x538")) {

    //         // $file = base_path('static/'.$path_img);
    //         dd($path_img);
            
    //         try
	// 		{

	// 			$imagev = new ImageVersionHandler;
	// 			$uploaded_filename = File::name($path_img);

	// 			/**
	// 	         * Mantengo il filename esattamente invariato, cioè il file dell'immagine di listing,
	// 	         * si chiamerà esattamente allo stesso modo del file della prima immagine della gallery
	// 	         */
		         
	// 			$imagev->setImageBasename($uploaded_filename);
	// 			// $backup_path = storage_path('original_images/listing');
    //             $backup_path = config("app.storage_images_path") . "/original_images/listing";

	// 			$imagev->enableOriginalBackup($backup_path);
	// 			$imagev->loadOriginalFromPath($file);
				
	// 			foreach (ImmagineGallery::getImagesVersions() as $v)
	// 				$imagev->process($v["mode"], $v["basedir"], $v["width"], $v["height"]);

	// 			/**
	// 			 * cancello l'associazione con la vecchia immagine dal db
	// 			 * cancello fisicamente la vecchia immagine di listing
	// 			 */
				 
	// 			$hotel->deleteListingImg();
	// 			$hotel->update(["listing_img" => $img->foto]);
	// 			SessionResponseMessages::add("success", "Operazione completata con successo");
	// 			return SessionResponseMessages::redirect("admin/immagini-gallery", $request);
				
	// 		}

	// 		catch (\Exception $e) {
				
	// 			SessionResponseMessages::add("warning",  $e->getMessage());
	// 			return SessionResponseMessages::redirect("admin/immagini-gallery", $request);
				
	// 		}

	// 	}

	// 	/**
	// 	 *  questa foto si trova, nel formato 800x538 in ImmagineGallery::GALLERY_SUPERBIG_PATH
	// 	 */
		 
	// 	$foto_prima_gallery = ImmagineGallery::where('hotel_id', $hotel_id)->whereRaw('position = (select min(`position`) from tblImmaginiGallery where hotel_id ='. $hotel_id.' )')->first()->foto;


	// 	////////////////////////////////////////////////////////
	// 	// La directory GALLERY_SUPERBIG_PATH NON ESISTE PIU' //
	// 	////////////////////////////////////////////////////////

	// 	/**
	// 	 * la copio in Hotel::LISTING_IMG_PATH ed applico il resize
	// 	 */
		 
	// 	/*$image = Image::make(public_path(ImmagineGallery::GALLERY_SUPERBIG_PATH) . $foto_prima_gallery);
	// 	$height = $image->height();
	// 	$width = $image->width();*/

	// 	/**
	// 	 * se l'immagine della gallery è maggireo di quella del listing (SEMPRE!!)
	// 	 */
		 
	// 	/*if ($height > Hotel::L_H || $width > Hotel::L_W)
			
	// 		$image->resize(Hotel::L_W, Hotel::L_H, function ($constraint)
	// 			{
	// 				$constraint->aspectRatio();
	// 				$constraint->upsize();
	// 			});*/
		
	// 	////////////////////////////////////////////////////////
	// 	// La directory GALLERY_SUPERBIG_PATH NON ESISTE PIU' //
	// 	////////////////////////////////////////////////////////

	// 	$imageName = $foto_prima_gallery;
	// 	$saved = $image->save(base_path('static/' . Hotel::LISTING_IMG_PATH) . $imageName);

	// 	/**
	// 	 * cancello l'associazione con la vecchia immagine dal db
	// 	 * cancello fisicamente la vecchia immagine di listing
	// 	 */
		 
	// 	$hotel->deleteListingImg();

	// 	/** 
	// 	 * l'associazione la sostituisco con questa
	// 	 */
			
	// 	$hotel->update(['listing_img' => $imageName]);
	// 	$hotel->save();

	// 	SessionResponseMessages::add("success", "Operazione completata con successo");
	// 	SessionResponseMessages::add("success", "La cache è stata aggiornata");

	// 	return SessionResponseMessages::redirect("admin/immagini-gallery", $request);

	// }

    public function createImgListing($id=0, Request $request) {

        $hotel_id = $this->getHotelId();
        $img = ImmagineGallery::find($id);
        // $foto_prima_gallery = ImmagineGallery::where('hotel_id', $hotel_id)->whereRaw('position = (select min(`position`) from tblImmaginiGallery where hotel_id ='. $hotel_id.' )')->first()->foto;      

        $hotel = Hotel::find($hotel_id);
        $hotel->update(['listing_img' => $img->foto]);
        // $hotel->update(['listing_img' => $foto_prima_gallery]);
        $hotel->save();

        SessionResponseMessages::add("success", "Operazione completata con successo");
        SessionResponseMessages::add("success", "La cache è stata aggiornata");

        return SessionResponseMessages::redirect("admin/immagini-gallery", $request);

    }

}
