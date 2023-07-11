<?php

namespace App;



use App\ParolaChiave;
use App\SlotVetrina;
use App\Vetrina;
use Config;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class SlotVetrina extends Model
{

	// è il path per le funzioni laravel tipo url()
	const SLOT_IMG_PATH = "images/vetrine/";

	// dimensioni immagine slot della vetrina
	const L_W = 360;
	const L_H = 200;


	// tabella in cui vengono salvati i record
	protected $table = 'tblSlotVetrine';
	// attributi NON mass-assignable
	protected $guarded = ['id'];

	protected $fillable = ['posizione', 'pointer', 'titolo', 'link', 'data_scadenza', 'attiva', 'immagine', 'hotel_id'];


	public $n_off_in_evidenza = false;




	/**
	 * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
	 */
	 
	public function getDates()
	{
		return ['data_scadenza'];
	}


	public function vetrina()
	{
		return $this->belongsTo('App\Vetrina', 'vetrina_id', 'id');
	}


	public function cliente()
	{
		return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
	}




	public function scopeAttivo($query) 
  	{
    return $query->whereAttiva(1);
    }

	/* ritorna gli slot associati ai clienti che hanno una certa categoria*/
	public function scopeCategoria($query, $categoria)
	{
		return $query->whereHas('cliente', function($q) use ($categoria)
			{
				$q->where('categoria_id', $categoria);
			});
	}



	public function scopeWithClienteLazyEagerLoaded($query, $cms_pagina, $order, $filter, $limit = 0)
	{
		
		/**
		 * Se ho una offerta in evidenza allora la mostro
		 */
		
		if( $listing_parolaChiave_id = Utility::checkOfferteInEvidenza($cms_pagina->lang_id) )
			{
				
			$parola_chiave = ParolaChiave::with("alias")->find($listing_parolaChiave_id);

			if (isset($parola_chiave->alias))
				foreach ($parola_chiave->alias as $term)
					$terms[] = $term->chiave;

			return $query->with([
				'cliente' => function($query) use ( $order, $limit,  $filter, $terms)
				{
					$query->where('attivo', '1');

					if ($limit > 0) {

						$query
						->limit($limit)
						->inRandomOrder();

					}

					if ($filter != "")
						$query->whereRaw($filter . "=1" );

				},
				'cliente.caparre',
				'cliente.stelle',
				'cliente.localita.macrolocalita',
				'cliente.numero_offerte_attive',
				'cliente.numero_last_attivi',
				'cliente.numero_pp_attivi',
				'cliente.numero_bambini_gratis_attivi',
				'cliente.caparreAttive',
				'cliente.labelCaparre',
				'cliente' => function ($query) {
					$query->withCount(['caparreAttive', 'immaginiGallery','serviziCovid'])->withFirstImage();
				},
				'cliente.offerteTop'  => function($query)
					{
						$query
						->visibileInScheda()
						->attiva();
					},
				'cliente.offerteTop.offerte_lingua' => function($query) use ($cms_pagina, $terms)
					{
						$query
						->where('lang_id', '=', $cms_pagina->lang_id)
						->multiTestoOrTitoloLike($terms);
					},
					
				'cliente.offerteBambiniGratisTop',

				'cliente.offerteLast'  => function($query) use ($order)
				{
					$query
					->attiva()
					->ordinaPer(null);
				},
				
				'cliente.offerteLast.offerte_lingua' => function($query) use ($cms_pagina, $terms)
				{
					$query
					->where('lang_id', '=', $cms_pagina->lang_id)
					->multiTestoOrTitoloLike($terms);
				},

				]);

			}
		else
			{

			return $query->with([
				'cliente' => function($query) use ( $order, $limit,  $filter)
				{
					$query->where('attivo', '1');

					if ($limit > 0) {

						$query
						->limit($limit)
						->inRandomOrder();

					}

					if ($filter != "")
						$query->whereRaw($filter . "=1" );

				},
				'cliente.stelle',
				'cliente.caparre',
				'cliente.localita.macrolocalita',
				'cliente.numero_offerte_attive',
				'cliente.numero_last_attivi',
				'cliente.numero_pp_attivi',
				'cliente.numero_bambini_gratis_attivi',
				'cliente.caparreAttive',
				'cliente.labelCaparre',
				'cliente' => function ($query) {
					$query->withCount(['caparreAttive', 'immaginiGallery','serviziCovid'])->withFirstImage();
				},
				'cliente.offerteTop'  => function($query)
					{
						$query
						->visibileInScheda()
						->attiva();
					},
				'cliente.offerteBambiniGratisTop',

				]);
				
			}




	}

	public function scopeWithClienteEagerLoaded($query, $locale = 'it')
	{

			if($listing_parolaChiave_id = Utility::checkOfferteInEvidenza($locale)) 
				{

				$parola_chiave = ParolaChiave::with("alias")->find($listing_parolaChiave_id);

				if (isset($parola_chiave->alias))
				  foreach ($parola_chiave->alias as $term)
				    $terms[] = $term->chiave;

				return $query->with([
					'cliente' => function($query)
					{
						$query->where('attivo', '1');
					},
					'cliente.stelle',
					'cliente.caparre',
					'cliente.localita.macrolocalita',
					'cliente.numero_offerte_attive',
					'cliente.numero_last_attivi',
					'cliente.numero_pp_attivi',
					'cliente.numero_bambini_gratis_attivi',
					'cliente' => function($query) {
						$query->withCount(['caparreAttive', 'immaginiGallery'])->withFirstImage();
					},
					'cliente.offerte'  => function($query) use ($locale)
					{
						$query
						->attiva()
						->orderByRaw("RAND()");
					},
					'cliente.offerte.offerte_lingua' => function($query) use ($locale)
					{
						$query
						->where('lang_id', '=', $locale);
					},
					'cliente.last'  => function($query) use ($locale)
					{
						$query
						->attiva()
						->orderByRaw("RAND()");
					},
					'cliente.last.offerte_lingua' => function($query) use ($locale)
					{
						$query
						->where('lang_id', '=', $locale);
					},
					'cliente.bambiniGratisAttivi' => function($query)
					{
						$query
						->orderByRaw("RAND()");
					},
					'cliente.offerteLast'  => function($query) 
					{
					  $query
					  ->attiva()
					  ->ordinaPer(null);
					},
					
					'cliente.offerteLast.offerte_lingua' => function($query) use ($locale, $terms)
					{
					  $query
					  ->where('lang_id', '=', $locale)
					  ->multiTestoOrTitoloLike($terms);
					},
					]);

				}
			else
				{

				return $query->with([
					'cliente' => function($query)
					{
						$query->where('attivo', '1');
					},
					'cliente.stelle',
					'cliente.caparre',
					'cliente.localita.macrolocalita',
					'cliente.numero_offerte_attive',
					'cliente.numero_last_attivi',
					'cliente.numero_pp_attivi',
					'cliente.numero_bambini_gratis_attivi',
					'cliente' => function ($query) {
						$query->withCount(['caparreAttive', 'immaginiGallery'])->withFirstImage();
					},
					'cliente.offerte'  => function($query) use ($locale)
					{
						$query
						->attiva()
						->orderByRaw("RAND()");
					},
					'cliente.offerte.offerte_lingua' => function($query) use ($locale)
					{
						$query
						->where('lang_id', '=', $locale);
					},
					'cliente.last'  => function($query) use ($locale)
					{
						$query
						->attiva()
						->orderByRaw("RAND()");
					},
					'cliente.last.offerte_lingua' => function($query) use ($locale)
					{
						$query
						->where('lang_id', '=', $locale);
					},
					'cliente.bambiniGratisAttivi' => function($query)
					{
						$query
						->orderByRaw("RAND()");
					},
					]);

				}

	}


	/**
	 * Ritorna il path dell'immagine, ideale per essere passato alle funzioni built-in di Laravel url() public_path()
	 * @param  boolean $image_not_found_placeholder true per fare in modo che se l'immagine non c'è, torna il path del placeholder "immagine non trovata"
	 * @return string|boolean
	 */
	public function getImg($version, $image_not_found_placeholder = false)
	{
		
		if (Config::get("image.image404") && $image_not_found_placeholder) 
			$image_not_found_placeholder = false;
		
		$ok = true;
		$path = self::SLOT_IMG_PATH."$version/{$this->immagine}";

		//echo public_path($path);

		// non è stata definita
		if (empty($this->immagine))
			$ok = false;

		// è stata definita ma è invalida (non c'è fisicamente sul filesystem o è illeggibile)
		elseif (!File::exists(public_path($path)))
			$ok = false;

		if ($ok)
			return $path;

		elseif ($image_not_found_placeholder)
			return self::SLOT_IMG_PATH."ia.jpg";

		elseif (!$image_not_found_placeholder)
			return $path;


		return false;
	}


	public function deleteImg()
	{
		$ok = 0;

		if ($this->getImg("360x200") !== false) {
			if (File::delete(public_path($this->getImg("360x200"))))
				$ok++;
		}

		if ($this->getImg("220x148") !== false) {
			if (File::delete(public_path($this->getImg("220x148"))))
				$ok++;
		}

		if ($this->getImg("720x400") !== false) {
			if (File::delete(public_path($this->getImg("720x400"))))
				$ok++;
		}

		if ($ok == 2) {
			$this->immagine = '';

			return true;
		}

		return false;
	}



}
