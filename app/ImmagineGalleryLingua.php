<?php

/**
 * ImmagineGalleryLingua
 *
 * @author Info Alberghi Srl
 *
 */

namespace App;


use App\ImmagineGallery;
use Illuminate\Database\Eloquent\Model;

class ImmagineGalleryLingua extends Model
{

	protected $table = 'tblImmaginiGalleryLang';
	protected $guarded = ['id'];
	protected $fillable = ['master_id', 'lang_id', 'caption', 'moderato'];

    
  
	/* ------------------------------------------------------------------------------------
	 * EAGER LOADING
	 * ------------------------------------------------------------------------------------ */




	public function immagineGallery()
	{
		return $this->belongsTo('App\ImmagineGallery', 'master_id', 'id');
	}


}
